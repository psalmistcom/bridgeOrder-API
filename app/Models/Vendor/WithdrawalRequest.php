<?php

namespace App\Models\Vendor;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Vendor\WithdrawalRequest
 *
 * @property int $id
 * @property int $restaurant_id
 * @property float $amount
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Restaurant $restaurant
 * @method static Builder|WithdrawalRequest newModelQuery()
 * @method static Builder|WithdrawalRequest newQuery()
 * @method static Builder|WithdrawalRequest query()
 * @method static Builder|WithdrawalRequest whereAmount($value)
 * @method static Builder|WithdrawalRequest whereCreatedAt($value)
 * @method static Builder|WithdrawalRequest whereDeletedAt($value)
 * @method static Builder|WithdrawalRequest whereId($value)
 * @method static Builder|WithdrawalRequest whereRestaurantId($value)
 * @method static Builder|WithdrawalRequest whereStatus($value)
 * @method static Builder|WithdrawalRequest whereUpdatedAt($value)
 * @mixin Eloquent
 */
class WithdrawalRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    protected $hidden = [
        'updated_at', 'deleted_at'
    ];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}
