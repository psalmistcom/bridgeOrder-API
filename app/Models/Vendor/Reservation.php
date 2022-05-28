<?php

namespace App\Models\Vendor;

use App\Models\Customer\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Vendor\Reservation
 *
 * @property int $id
 * @property int $restaurant_id
 * @property int $user_id
 * @property string $number_of_guests
 * @property string $date
 * @property string $check_in
 * @property string $reservation_type
 * @property string|null $special_request
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Vendor\Restaurant $restaurant
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation newQuery()
 * @method static \Illuminate\Database\Query\Builder|Reservation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereCheckIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereNumberOfGuests($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereReservationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereRestaurantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereSpecialRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Reservation withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Reservation withoutTrashed()
 * @mixin \Eloquent
 * @property string $status
 * @property string $payment_method
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereStatus($value)
 */
class Reservation extends Model
{
    use HasFactory;
    use SoftDeletes;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
