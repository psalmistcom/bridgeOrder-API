<?php

namespace App\Models\Vendor;

use App\Models\Customer\User;
use Eloquent;
use Illuminate\Database\Eloquent\{Builder,
    Collection,
    Factories\HasFactory,
    Model,
    Relations\BelongsTo,
    Relations\HasMany,
    SoftDeletes};
use Illuminate\Support\Carbon;

/**
 * App\Models\Vendor\Order
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Restaurant|null $restaurant
 * @property-read User|null $user
 * @method static Builder|Order newModelQuery()
 * @method static Builder|Order newQuery()
 * @method static Builder|Order query()
 * @method static Builder|Order whereCreatedAt($value)
 * @method static Builder|Order whereDeletedAt($value)
 * @method static Builder|Order whereId($value)
 * @method static Builder|Order whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static \Illuminate\Database\Query\Builder|Order onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|Order withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Order withoutTrashed()
 * @property-read Collection|OrderMenu[] $items
 * @property-read int|null $items_count
 * @property string $order_number
 * @property int $user_id
 * @property int $restaurant_id
 * @property string $total_price
 * @property int $item_count
 * @property string $order_status
 * @property string $order_type
 * @property string $payment_status
 * @property string|null $payment_method
 * @method static Builder|Order whereItemCount($value)
 * @method static Builder|Order whereOrderNumber($value)
 * @method static Builder|Order whereOrderStatus($value)
 * @method static Builder|Order whereOrderType($value)
 * @method static Builder|Order wherePaymentMethod($value)
 * @method static Builder|Order wherePaymentStatus($value)
 * @method static Builder|Order whereRestaurantId($value)
 * @method static Builder|Order whereTotalPrice($value)
 * @method static Builder|Order whereUserId($value)
 * @property string|float|int|null $vendor_fee
 * @method static Builder|Order whereVendorFee($value)
 */
class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $hidden = ['updated_at', 'deleted_at'];

    public function items(): HasMany
    {
        return $this->hasMany(OrderMenu::class);
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
