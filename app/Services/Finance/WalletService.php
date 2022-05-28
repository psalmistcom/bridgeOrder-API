<?php

namespace App\Services\Finance;

use App\Enum\PaymentMethod;
use App\Enum\Status;
use App\Enum\TransactionType;
use App\Exceptions\HttpException;
use App\Models\Customer\User;
use App\Models\Vendor\Restaurant;
use Illuminate\Contracts\Auth\Authenticatable;

class WalletService
{
    /**
     * @param Restaurant|User $user
     */
    public static function createWallet(Restaurant|User $user): void
    {
        $user->wallet()->create([]);
    }

    /**
     * @param Restaurant|User $user
     * @param float|int $amount
     * @param string $model
     * @param string $description
     * @param int|null $customerId
     * @param string|null $category
     * @throws HttpException
     */
    public static function withdrawFromWallet(
        Restaurant|User $user,
        float|int $amount,
        string $model,
        string $description,
        int $customerId = null,
        string $category = null
    ): void {
        if ($user->wallet->balance < $amount) {
            throw new HttpException('insufficient wallet balance.');
        }

        if ($user->wallet->is_locked) {
            throw new HttpException('Your wallet is currently locked. Kindly reach out to the bridge order admin');
        }
        $user->wallet->balance -= $amount;
        $user->wallet->save();
        TransactionService::record(
            $model,
            $user,
            $description,
            $amount,
            Status::SUCCESS->value,
            TransactionType::WALLET_WITHDRAWAL->value,
            PaymentMethod::BRIDGE_WALLET->value,
            $user->wallet->id,
            $customerId,
            $category
        );
    }

    /**
     * @param string $model
     * @param Restaurant|User|Authenticatable $user
     * @param float|int $amount
     * @param string $status
     * @param string $type
     * @param string $description
     * @param string $paymentMethod
     * @param int|null $customerId
     * @param string|null $category
     */
    public static function topUpWallet(
        string $model,
        Restaurant|User|Authenticatable $user,
        float|int $amount,
        string $status,
        string $type,
        string $description,
        string $paymentMethod,
        int $customerId = null,
        string $category = null
    ): void {
        $user->wallet->balance += $amount;
        $user->wallet->save();
        TransactionService::record(
            $model,
            $user,
            $description,
            $amount,
            $status,
            $type,
            $paymentMethod,
            $user->wallet->id,
            $customerId,
            $category
        );
    }
}
