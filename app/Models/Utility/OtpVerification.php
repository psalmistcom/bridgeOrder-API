<?php

namespace App\Models\Utility;

use App\Enum\Status;
use Closure;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * App\Models\Utility\OtpVerification
 *
 * @property int $id
 * @property string $otpverifiable_type
 * @property int $otpverifiable_id
 * @property string $recipient
 * @property string $purpose
 * @property string $code
 * @property string $type
 * @property string $status
 * @property string|null $verified_at
 * @property string $expires_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Model|Eloquent $otpverifiable
 * @method static \Illuminate\Database\Eloquent\Builder|OtpVerification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OtpVerification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OtpVerification query()
 * @method static \Illuminate\Database\Eloquent\Builder|OtpVerification whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtpVerification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtpVerification whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtpVerification whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtpVerification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtpVerification whereOtpverifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtpVerification whereOtpverifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtpVerification wherePurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtpVerification whereRecipient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtpVerification whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtpVerification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtpVerification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OtpVerification whereVerifiedAt($value)
 * @mixin Eloquent
 * @method static Builder|OtpVerification onlyTrashed()
 * @method static Builder|OtpVerification withTrashed()
 * @method static Builder|OtpVerification withoutTrashed()
 */
class OtpVerification extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const PURPOSE_PASSWORD_RESET = 'password_reset';
    public const PURPOSE_EMAIL_VERIFICATION = 'email_verification';
    public const PURPOSE_WITHDRAWAL_REQUEST = 'withdrawal_request';

    public const TYPE_MAIL = 'mail';
    public const TYPE_SMS = 'sms';

    /**
     * Generates is_expired attribute.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        $expiresAt = new Carbon($this['expires_at']);
        return $expiresAt->lt(now());
    }

    public function markAsExpired(): bool
    {
        return $this->forceFill(['status' => Status::EXPIRED])->save();
    }

    public function markAsVerified(): bool
    {
        return $this->forceFill([
            'status' => Status::VERIFIED,
            'verified_at' => now()
        ])->save();
    }

    /**
     * @throws Throwable
     */
    public function otpExpired(?Closure $callback): void
    {
        if ($this->isExpired()) {
            $this->markAsExpired();
            DB::commit();
            is_callable($callback) && $callback();
        }
    }

    public function otpverifiable(): MorphTo
    {
        return $this->morphTo();
    }
}
