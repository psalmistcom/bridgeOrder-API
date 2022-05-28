<?php

namespace App\Models\Finance;

use App\Models\Customer\User;
use App\Traits\Uuids;
use Eloquent;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Finance\Wallet
 *
 * @property string $id
 * @property string $walletable_type
 * @property int $walletable_id
 * @property float $balance
 * @property bool $is_locked
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Model|Eloquent $walletable
 * @method static Builder|Wallet newModelQuery()
 * @method static Builder|Wallet newQuery()
 * @method static \Illuminate\Database\Query\Builder|Wallet onlyTrashed()
 * @method static Builder|Wallet query()
 * @method static Builder|Wallet whereBalance($value)
 * @method static Builder|Wallet whereCreatedAt($value)
 * @method static Builder|Wallet whereDeletedAt($value)
 * @method static Builder|Wallet whereId($value)
 * @method static Builder|Wallet whereIsLocked($value)
 * @method static Builder|Wallet whereUpdatedAt($value)
 * @method static Builder|Wallet whereWalletableId($value)
 * @method static Builder|Wallet whereWalletableType($value)
 * @method static \Illuminate\Database\Query\Builder|Wallet withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Wallet withoutTrashed()
 * @mixin Eloquent
 */
class Wallet extends Model
{
    use HasFactory;
    use Uuids;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'is_locked' => 'boolean',
    ];

    protected $hidden = [
        'updated_at', 'deleted_at', 'walletable_type', 'walletable_id'
    ];

    public function walletable(): MorphTo
    {
        return $this->morphTo();
    }
}
