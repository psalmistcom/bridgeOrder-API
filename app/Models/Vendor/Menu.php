<?php

namespace App\Models\Vendor;

use App\Models\Favourite;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Vendor\Menu
 *
 * @property int $id
 * @property int $category_id
 * @property int $vendor_id
 * @property string $item
 * @property float $price
 * @property string|null $variant
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Category|null $category
 * @property-read Restaurant|null $restaurant
 * @property-read Vendor|null $vendor
 * @method static Builder|Menu newModelQuery()
 * @method static Builder|Menu newQuery()
 * @method static Builder|Menu query()
 * @method static Builder|Menu whereCategoryId($value)
 * @method static Builder|Menu whereCreatedAt($value)
 * @method static Builder|Menu whereDeletedAt($value)
 * @method static Builder|Menu whereId($value)
 * @method static Builder|Menu whereItem($value)
 * @method static Builder|Menu wherePrice($value)
 * @method static Builder|Menu whereUpdatedAt($value)
 * @method static Builder|Menu whereVariant($value)
 * @method static Builder|Menu whereVendorId($value)
 * @mixin Eloquent
 * @property int $restaurant_id
 * @property string $item_name
 * @property string|null $image_public_id
 * @property string|null $image
 * @property-read Collection|Variant[] $variants
 * @property-read int|null $variants_count
 * @method static Builder|Menu whereImage($value)
 * @method static Builder|Menu whereImagePublicId($value)
 * @method static Builder|Menu whereItemName($value)
 * @method static Builder|Menu whereRestaurantId($value)
 * @property-read Collection|Favourite[] $favourites
 * @property-read int|null $favourites_count
 * @property bool $in_stock
 * @method static Builder|Menu whereInStock($value)
 */
class Menu extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = [
        'updated_at', 'deleted_at', 'image_public_id'
    ];

    protected $casts = [
        'in_stock' => 'boolean'
    ];

    protected $with = ['variants'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function favourites(): HasMany
    {
        return $this->hasMany(Favourite::class);
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
