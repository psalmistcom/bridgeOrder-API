<?php

namespace App\Models\Customer;

use App\Enum\Status;
use App\Models\Favourite;
use App\Models\Finance\{PaymentCard, Transaction, Wallet};
use App\Models\Review;
use App\Models\Role;
use App\Models\Vendor\Reservation;
use App\Models\Utility\{ActivityLog, CustomNotification, FireBaseDeviceToken, OtpVerification};
use App\Models\Vendor\Order;
use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\{Builder, Collection, Factories\HasFactory, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\{HasMany, HasOne, MorphMany, MorphOne};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\{DatabaseNotification, DatabaseNotificationCollection, Notifiable};
use Illuminate\Support\Carbon;
use Laravel\Passport\{Client, HasApiTokens, Token};

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Client[] $clients
 * @property-read int|null $clients_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static UserFactory factory(...$parameters)
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string|null $deleted_at
 * @property-read Collection|Order[] $orders
 * @property-read int|null $orders_count
 * @property-read Collection|OtpVerification[] $otps
 * @property-read int|null $otps_count
 * @method static Builder|User whereDeletedAt($value)
 * @property string $full_name
 * @property int $role_id
 * @property-read Role $role
 * @method static Builder|User whereFullName($value)
 * @method static Builder|User whereRoleId($value)
 * @property string $status
 * @property string|null $image_public_id
 * @property string|null $image
 * @property Carbon|null $logged_in_at
 * @property Carbon|null $logged_out_at
 * @property-read Collection|CustomNotification[] $customNotifications
 * @property-read int|null $custom_notifications_count
 * @property-read FireBaseDeviceToken|null $deviceToken
 * @property-read Wallet|null $wallet
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static Builder|User whereImage($value)
 * @method static Builder|User whereImagePublicId($value)
 * @method static Builder|User whereLoggedInAt($value)
 * @method static Builder|User whereLoggedOutAt($value)
 * @method static Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 * @property-read Collection|PaymentCard[] $paymentCards
 * @property-read int|null $payment_cards_count
 * @property-read PaymentCard|null $activePaymentCard
 * @property-read Collection|Reservation[] $reservations
 * @property-read int|null $reservations_count
 * @property-read Collection|Favourite[] $favourites
 * @property-read int|null $favourites_count
 * @property-read Collection|Review[] $reviews
 * @property-read int|null $reviews_count
 * @property string|null $longitude
 * @property string|null $latitude
 * @method static Builder|User whereLatitude($value)
 * @method static Builder|User whereLongitude($value)
 * @property-read Collection|ActivityLog[] $activities
 * @property-read int|null $activities_count
 * @property-read Collection|Transaction[] $transactions
 * @property-read int|null $transactions_count
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    protected $guard = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'status',
        'password',
        'logged_in_at',
        'logged_out_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at',
        'image_public_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'logged_in_at' => 'datetime',
        'logged_out_at' => 'datetime',
    ];

    public function activities(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'loggable');
    }

    public function activePaymentCard(): HasOne
    {
        return $this->hasOne(PaymentCard::class);
    }

    public function activePaymentCards(): Collection
    {
        return $this->paymentCards->where('status', Status::ACTIVE->value);
    }

    public function customNotifications(): MorphMany
    {
        return $this->morphMany(CustomNotification::class, 'notificable');
    }

    public function deviceToken(): MorphOne
    {
        return $this->morphOne(FireBaseDeviceToken::class, 'firebaseable');
    }

    public function favourites(): HasMany
    {
        return $this->hasMany(Favourite::class);
    }

    public function inactivePaymentCards(): Collection
    {
        return $this->paymentCards->where('status', Status::INACTIVE->value);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function otps(): MorphMany
    {
        return $this->morphMany(OtpVerification::class, 'otpverifiable');
    }

    public function paymentCards(): HasMany
    {
        return $this->hasMany(PaymentCard::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactable');
    }

    public function wallet(): MorphOne
    {
        return $this->morphOne(Wallet::class, 'walletable');
    }
}
