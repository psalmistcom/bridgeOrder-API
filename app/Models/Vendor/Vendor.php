<?php

namespace App\Models\Vendor;

use App\Models\Role;
use App\Models\Utility\{ActivityLog, CustomNotification, FireBaseDeviceToken, OtpVerification};
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphMany, MorphOne};
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\{DatabaseNotification, DatabaseNotificationCollection, Notifiable};
use Illuminate\Support\Carbon;
use Laravel\Passport\{Client, HasApiTokens, Token};

/**
 * App\Models\Vendor
 *
 * @property int $id
 * @property string $full_name
 * @property string $email
 * @property string $restaurant_name
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
 * @method static Builder|Vendor newModelQuery()
 * @method static Builder|Vendor newQuery()
 * @method static Builder|Vendor query()
 * @method static Builder|Vendor whereCreatedAt($value)
 * @method static Builder|Vendor whereEmail($value)
 * @method static Builder|Vendor whereFullName($value)
 * @method static Builder|Vendor whereId($value)
 * @method static Builder|Vendor wherePassword($value)
 * @method static Builder|Vendor whereRememberToken($value)
 * @method static Builder|Vendor whereRestaurantName($value)
 * @method static Builder|Vendor whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $restaurant_id
 * @property-read Restaurant|null $restaurant
 * @method static Builder|Vendor whereRestaurantId($value)
 * @property string|null $deleted_at
 * @property-read Menu|null $menus
 * @property-read Collection|OtpVerification[] $otps
 * @property-read int|null $otps_count
 * @method static Builder|Vendor whereDeletedAt($value)
 * @property int $role_id
 * @property-read Role $role
 * @method static Builder|Vendor whereRoleId($value)
 * @property string $status
 * @property string|null $image_public_id
 * @property string|null $image
 * @property string|null $logged_in_at
 * @property string|null $logged_out_at
 * @property-read Collection|CustomNotification[] $customNotifications
 * @property-read int|null $custom_notifications_count
 * @property-read FireBaseDeviceToken|null $deviceToken
 * @method static \Illuminate\Database\Query\Builder|Vendor onlyTrashed()
 * @method static Builder|Vendor whereImage($value)
 * @method static Builder|Vendor whereImagePublicId($value)
 * @method static Builder|Vendor whereLoggedInAt($value)
 * @method static Builder|Vendor whereLoggedOutAt($value)
 * @method static Builder|Vendor whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|Vendor withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Vendor withoutTrashed()
 * @property-read Collection|ActivityLog[] $activities
 * @property-read int|null $activities_count
 */
class Vendor extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    public const ROLE_VENDOR_ADMIN = 'vendor_admin';

    protected $guard = 'vendor';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'updated_at',
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
    ];

    public function activities(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'loggable');
    }

    public function deviceToken(): MorphOne
    {
        return $this->morphOne(FireBaseDeviceToken::class, 'firebaseable');
    }

    public function menus(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function otps(): MorphMany
    {
        return $this->morphMany(OtpVerification::class, 'otpverifiable');
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
