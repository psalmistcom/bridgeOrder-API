<?php

namespace App\Services\Finance;

use App\Models\Customer\User;
use App\Models\Finance\Transaction;
use App\Models\Vendor\Restaurant;
use Illuminate\Contracts\Auth\Authenticatable;

class TransactionService
{
    /**
     * @param string $model
     * @param Restaurant|User|Authenticatable $transactable
     * @param string $description
     * @param int|float $amount
     * @param string $status
     * @param string $type
     * @param string $paymentMethod
     * @param string|null $walletId
     * @param int|null $customerId
     */
    public static function record(
        string $model,
        Restaurant|User|Authenticatable $transactable,
        string $description,
        int|float $amount,
        string $status,
        string $type,
        string $paymentMethod,
        string $walletId = null,
        int $customerId = null,
        string $category = null
    ): void {
        Transaction::create([
            'transactable_type' => $model,
            'transactable_id' => $transactable->id,
            'description' => $description,
            'amount' => $amount,
            'status' => $status,
            'type' => $type,
            'payment_method' => $paymentMethod,
            'wallet_id' => $walletId,
            'customer_id' => $customerId,
            'category' => $category,
        ]);
    }
}
