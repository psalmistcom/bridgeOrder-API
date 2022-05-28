<?php

namespace App\Models\Vendor;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Vendor\OrderMenu
 *
 * @property-read Menu $menu
 * @property-read Order|null $order
 * @method static Builder|OrderMenu newModelQuery()
 * @method static Builder|OrderMenu newQuery()
 * @method static Builder|OrderMenu query()
 * @mixin Eloquent
 * @property int $id
 * @property int $order_id
 * @property int $menu_id
 * @property int $quantity
 * @property string $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|OrderMenu whereCreatedAt($value)
 * @method static Builder|OrderMenu whereId($value)
 * @method static Builder|OrderMenu whereMenuId($value)
 * @method static Builder|OrderMenu whereOrderId($value)
 * @method static Builder|OrderMenu wherePrice($value)
 * @method static Builder|OrderMenu whereQuantity($value)
 * @method static Builder|OrderMenu whereUpdatedAt($value)
 * @property int|null $variant_id
 * @property-read \App\Models\Vendor\Variant|null $variant
 * @method static Builder|OrderMenu whereVariantId($value)
 * @property string|null $deleted_at
 * @method static Builder|OrderMenu whereDeletedAt($value)
 */
class OrderMenu extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }
}
