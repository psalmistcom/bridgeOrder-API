<?php

namespace App\Models\Finance;

use App\Enum\Status;
use App\Models\Customer\User;
use Eloquent;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Finance\PaymentCard
 *
 * @property-read User|null $user
 * @method static Builder|PaymentCard newModelQuery()
 * @method static Builder|PaymentCard newQuery()
 * @method static Builder|PaymentCard query()
 * @mixin Eloquent
 * @property int $id
 * @property int $user_id
 * @property string $authorization_code
 * @property string $signature
 * @property string $card_type
 * @property string $last4
 * @property string $email
 * @property string $exp_month
 * @property string $exp_year
 * @property string $bin
 * @property string $bank
 * @property string $channel
 * @property string $reusable
 * @property string $country_code
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static Builder|PaymentCard whereAuthorizationCode($value)
 * @method static Builder|PaymentCard whereBank($value)
 * @method static Builder|PaymentCard whereBin($value)
 * @method static Builder|PaymentCard whereCardType($value)
 * @method static Builder|PaymentCard whereChannel($value)
 * @method static Builder|PaymentCard whereCountryCode($value)
 * @method static Builder|PaymentCard whereCreatedAt($value)
 * @method static Builder|PaymentCard whereDeletedAt($value)
 * @method static Builder|PaymentCard whereEmail($value)
 * @method static Builder|PaymentCard whereExpMonth($value)
 * @method static Builder|PaymentCard whereExpYear($value)
 * @method static Builder|PaymentCard whereId($value)
 * @method static Builder|PaymentCard whereLast4($value)
 * @method static Builder|PaymentCard whereReusable($value)
 * @method static Builder|PaymentCard whereSignature($value)
 * @method static Builder|PaymentCard whereStatus($value)
 * @method static Builder|PaymentCard whereUpdatedAt($value)
 * @method static Builder|PaymentCard whereUserId($value)
 */
class PaymentCard extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param Authenticatable|User $user
     * @param array $transaction
     */
    public static function saveAuthorization(Authenticatable|User $user, array $transaction): void
    {
        self::create([
            'user_id' => $user['id'],
            'authorization_code' => $transaction['data']['authorization']['authorization_code'],
            'signature' => $transaction['data']['authorization']['signature'],
            'card_type' => $transaction['data']['authorization']['card_type'],
            'last4' => $transaction['data']['authorization']['last4'],
            'email' => $transaction['data']['customer']['email'],
            'exp_month' => $transaction['data']['authorization']['exp_month'],
            'exp_year' => $transaction['data']['authorization']['exp_year'],
            'bin' => $transaction['data']['authorization']['bin'],
            'bank' => $transaction['data']['authorization']['bank'],
            'channel' => $transaction['data']['authorization']['channel'],
            'reusable' => $transaction['data']['authorization']['reusable'],
            'country_code' => $transaction['data']['authorization']['country_code'],
        ]);
    }
}
