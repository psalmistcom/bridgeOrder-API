<?php

namespace App\Models\Admin;

use App\Models\Role;
use App\Models\Utility\{ActivityLog, CustomNotification, FireBaseDeviceToken, OtpVerification};
use Eloquent;
use Illuminate\Database\Eloquent\{Builder,
    Collection,
    Factories\HasFactory,
    Relations\BelongsTo,
    Relations\MorphMany,
    Relations\MorphOne,
    SoftDeletes};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\{DatabaseNotification, DatabaseNotificationCollection, Notifiable};
use Illuminate\Support\Carbon;
use Laravel\Passport\{Client, HasApiTokens, Token};

/**
 * App\Models\Admin\Admin
 *
 * @property int $id
 * @property string $full_name
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
 * @method static Builder|Admin newModelQuery()
 * @method static Builder|Admin newQuery()
 * @method static Builder|Admin query()
 * @method static Builder|Admin whereCreatedAt($value)
 * @method static Builder|Admin whereEmail($value)
 * @method static Builder|Admin whereEmailVerifiedAt($value)
 * @method static Builder|Admin whereFullName($value)
 * @method static Builder|Admin whereId($value)
 * @method static Builder|Admin wherePassword($value)
 * @method static Builder|Admin whereRememberToken($value)
 * @method static Builder|Admin whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string|null $deleted_at
 * @property-read Collection|OtpVerification[] $otps
 * @property-read int|null $otps_count
 * @method static Builder|Admin whereDeletedAt($value)
 * @property int $role_id
 * @property-read Role $role
 * @method static Builder|Admin whereRoleId($value)
 * @property string $status
 * @property string|null $image_public_id
 * @property string|null $image
 * @property string|null $logged_in_at
 * @property string|null $logged_out_at
 * @property-read Collection|CustomNotification[] $customNotifications
 * @property-read int|null $custom_notifications_count
 * @property-read FireBaseDeviceToken|null $deviceToken
 * @method static \Illuminate\Database\Query\Builder|Admin onlyTrashed()
 * @method static Builder|Admin whereImage($value)
 * @method static Builder|Admin whereImagePublicId($value)
 * @method static Builder|Admin whereLoggedInAt($value)
 * @method static Builder|Admin whereLoggedOutAt($value)
 * @method static Builder|Admin whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|Admin withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Admin withoutTrashed()
 * @property-read Collection|ActivityLog[] $activities
 * @property-read int|null $activities_count
 */
class Admin extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    public const ROLE_SUPER_ADMIN = 'super_admin';
    protected $guard = 'admin';

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

    public function customNotifications(): MorphMany
    {
        return $this->morphMany(CustomNotification::class, 'notificable');
    }

    public function deviceToken(): MorphOne
    {
        return $this->morphOne(FireBaseDeviceToken::class, 'firebaseable');
    }

    public function otps(): MorphMany
    {
        return $this->morphMany(OtpVerification::class, 'otpverifiable');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
