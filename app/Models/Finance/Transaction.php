<?php

namespace App\Models\Finance;

use App\Models\Customer\User;
use App\Models\Vendor\Restaurant;
use Illuminate\Database\Eloquent\{Builder, Factories\HasFactory, Model, Relations\BelongsTo, Relations\MorphTo};
use Eloquent;
use Illuminate\Support\Carbon;

/**
 * App\Models\Finance\Transaction
 *
 * @property int $id
 * @property string $amount
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Restaurant $restaurant
 * @property-read User $user
 * @method static Builder|Transaction newModelQuery()
 * @method static Builder|Transaction newQuery()
 * @method static Builder|Transaction query()
 * @method static Builder|Transaction whereAmount($value)
 * @method static Builder|Transaction whereCreatedAt($value)
 * @method static Builder|Transaction whereDeletedAt($value)
 * @method static Builder|Transaction whereId($value)
 * @method static Builder|Transaction whereStatus($value)
 * @method static Builder|Transaction whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string $transactable_type
 * @property int $transactable_id
 * @property int|null $customer_id
 * @property string|null $wallet_id
 * @property string $description
 * @property string $type
 * @property string|null $category
 * @property string|null $payment_method
 * @property-read User|null $customer
 * @property-read Model|\Eloquent $transactable
 * @method static Builder|Transaction whereCategory($value)
 * @method static Builder|Transaction whereCustomerId($value)
 * @method static Builder|Transaction whereDescription($value)
 * @method static Builder|Transaction wherePaymentMethod($value)
 * @method static Builder|Transaction whereTransactableId($value)
 * @method static Builder|Transaction whereTransactableType($value)
 * @method static Builder|Transaction whereType($value)
 * @method static Builder|Transaction whereWalletId($value)
 */
class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function transactable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
