<?php

namespace App\Models\Vendor;

use App\Enum\Status;
use App\Models\{Favourite,
    Finance\Transaction,
    Finance\Wallet,
    Review,
    Utility\ActivityLog,
    Utility\CustomNotification};
use Eloquent;
use Illuminate\Database\Eloquent\{Builder,
    Collection,
    Factories\HasFactory,
    Model,
    Relations\BelongsToMany,
    Relations\HasMany,
    Relations\HasOne,
    Relations\MorphMany,
    Relations\MorphOne,
    SoftDeletes};
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * App\Models\Vendor\Restaurant
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Category[] $categories
 * @property-read int|null $categories_count
 * @property-read Collection|Vendor[] $vendors
 * @property-read int|null $vendors_count
 * @method static Builder|Restaurant newModelQuery()
 * @method static Builder|Restaurant newQuery()
 * @method static Builder|Restaurant query()
 * @method static Builder|Restaurant whereCreatedAt($value)
 * @method static Builder|Restaurant whereId($value)
 * @method static Builder|Restaurant whereName($value)
 * @method static Builder|Restaurant whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string $slug
 * @property string|null $deleted_at
 * @property-read Collection|Menu[] $menus
 * @property-read int|null $menus_count
 * @property-read Collection|Order[] $orders
 * @property-read int|null $orders_count
 * @method static Builder|Restaurant whereDeletedAt($value)
 * @method static Builder|Restaurant whereSlug($value)
 * @property string $status
 * @property string|null $image_public_id
 * @property string|null $image
 * @property-read Wallet|null $wallet
 * @method static \Illuminate\Database\Query\Builder|Restaurant onlyTrashed()
 * @method static Builder|Restaurant whereImage($value)
 * @method static Builder|Restaurant whereImagePublicId($value)
 * @method static Builder|Restaurant whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|Restaurant withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Restaurant withoutTrashed()
 * @property string|null $account_name
 * @property string|null $account_number
 * @property string|null $bank_code
 * @method static Builder|Restaurant whereAccountName($value)
 * @method static Builder|Restaurant whereAccountNumber($value)
 * @method static Builder|Restaurant whereBankCode($value)
 * @property bool $allow_reservation
 * @property float $reservation_price
 * @property string|null $longitude
 * @property string|null $latitude
 * @property-read Collection|Reservation[] $reservations
 * @property-read int|null $reservations_count
 * @property-read Collection|WithdrawalRequest[] $withdrawalRequests
 * @property-read int|null $withdrawal_requests_count
 * @method static Builder|Restaurant whereAllowReservation($value)
 * @method static Builder|Restaurant whereLatitude($value)
 * @method static Builder|Restaurant whereLongitude($value)
 * @method static Builder|Restaurant whereReservationPrice($value)
 * @property-read Collection|Favourite[] $favourites
 * @property-read int|null $favourites_count
 * @property-read Collection|Review[] $reviews
 * @property-read int|null $reviews_count
 * @property-read Collection|ActivityLog[] $activities
 * @property-read int|null $activities_count
 * @property-read Collection|ActivityLog[] $restaurantTargetedActivities
 * @property-read int|null $restaurant_targeted_activities_count
 * @property-read Collection|CustomNotification[] $customNotifications
 * @property-read int|null $custom_notifications_count
 * @property-read Collection|Transaction[] $transactions
 * @property-read int|null $transactions_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 */
class Restaurant extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    protected $hidden = [
        'updated_at', 'deleted_at', 'image_public_id'
    ];

    protected $with = ['categories'];

    protected $casts = [
        'allow_reservation' => 'boolean',
        'reservation_price' => 'double',
    ];

    public function activities(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'loggable');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_restaurant')
            ->withTimestamps();
    }

    public function customNotifications(): MorphMany
    {
        return $this->morphMany(CustomNotification::class, 'notificable');
    }

    public function favourites(): HasMany
    {
        return $this->hasMany(Favourite::class);
    }

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function pendingWithdrawalRequest(): HasOne
    {
        return $this->hasOne(WithdrawalRequest::class)
            ->where('status', Status::PENDING->value);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function restaurantTargetedActivities(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'target_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactable');
    }

    public function vendors(): HasMany
    {
        return $this->hasMany(Vendor::class);
    }

    public function wallet(): MorphOne
    {
        return $this->morphOne(Wallet::class, 'walletable');
    }

    public function withdrawalRequests(): HasMany
    {
        return $this->hasMany(WithdrawalRequest::class);
    }
}
