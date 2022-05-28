<?php

namespace App\Services\Finance;

use App\Enum\PaymentMethod;
use App\Enum\Status;
use App\Enum\TransactionType;
use App\Exceptions\HttpException;
use App\Models\Customer\User;
use App\Models\Finance\PaymentCard;
use Illuminate\Contracts\Auth\Authenticatable;

class PaymentCardService
{
    /**
     * @param Authenticatable|User $user
     * @param string $reference
     * @throws HttpException
     */
    public static function addCard(Authenticatable|User $user, string $reference): void
    {
        $transaction = app(Paystack::class)->verifyTransaction($reference);
        if (
            $user->paymentCards()->whereSignature(
                $transaction['data']['authorization']['signature']
            )->first()
        ) {
            throw new HttpException('You have already added this payment card. Kindly add another.');
        }
        if ($user->activePaymentCards()->count()) {
            $user->activePaymentCards()->first()->update(['status' => Status::INACTIVE->value]);
        }
        PaymentCard::saveAuthorization($user, $transaction);
    }

    /**
     * @param Authenticatable|User $user
     * @param PaymentCard $paymentCard
     */
    public static function makeCardActive(Authenticatable|User $user, PaymentCard $paymentCard): void
    {
        if ($user->activePaymentCards()->count()) {
            $user->activePaymentCards()->first()->update(['status' => Status::INACTIVE->value]);
        }
        $paymentCard->refresh();
        $paymentCard->update(['status' => Status::ACTIVE->value]);
    }

    /**
     * @param Authenticatable|User $user
     * @param PaymentCard $paymentCard
     * @throws HttpException
     */
    public static function deleteCard(Authenticatable|User $user, PaymentCard $paymentCard): void
    {
        if ($user->paymentCards->count() < 2) {
            throw new HttpException('You must have at least one payment card available.');
        }
        $paymentCard->delete();
        $user->inactivePaymentCards()->first()->update(['status' => Status::ACTIVE->value]);
    }

    /**
     * @param Authenticatable|User $user
     * @param PaymentCard $paymentCard
     * @param float|int $amount
     * @param string $model
     * @throws HttpException
     */
    public static function topUpWalletWithCard(
        Authenticatable|User $user,
        PaymentCard $paymentCard,
        float|int $amount,
        string $model
    ): void {
        $charge = app(Paystack::class)->chargeCard($paymentCard, ($amount * 100));
        if (!$charge['status']) {
            throw new HttpException('Error occurred while trying to charge payment card.');
        }
        WalletService::topUpWallet(
            $model,
            $user,
            $amount,
            Status::SUCCESS->value,
            TransactionType::WALLET_TOP_UP->value,
            'top up wallet with debit card',
            PaymentMethod::BRIDGE_CARD->value
        );
    }

    /**
     * @param Authenticatable|User $user
     * @param float|int $amount
     * @param string $model
     * @param string $description
     * @param int|null $customerId
     * @param string|null $category
     * @throws HttpException
     */
    public static function payWithCard(
        Authenticatable|User $user,
        float|int $amount,
        string $model,
        string $description,
        int $customerId = null,
        string $category = null
    ): void {
        if (is_null($user->activePaymentCard)) {
            throw new HttpException('You have not added a payment card yet.');
        }
        $card = PaymentCard::whereId($user->activePaymentCard->id)->first();
        $charge = app(Paystack::class)->chargeCard($card, ($amount * 100));
        if (!$charge['status']) {
            throw new HttpException('Error occurred while trying to pay with payment card.');
        }
        TransactionService::record(
            $model,
            $user,
            $description,
            $amount,
            Status::SUCCESS->value,
            TransactionType::CARD_PAYMENT->value,
            PaymentMethod::BRIDGE_CARD->value,
            null,
            $customerId,
            $category
        );
    }
}
