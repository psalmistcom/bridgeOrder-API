<?php

namespace App\Models\Utility;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Utility\FireBaseDeviceToken
 *
 * @property int $id
 * @property string $firebaseable_type
 * @property int $firebaseable_id
 * @property string $device_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $firebaseable
 * @method static \Illuminate\Database\Eloquent\Builder|FireBaseDeviceToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FireBaseDeviceToken newQuery()
 * @method static \Illuminate\Database\Query\Builder|FireBaseDeviceToken onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|FireBaseDeviceToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|FireBaseDeviceToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FireBaseDeviceToken whereDeviceToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FireBaseDeviceToken whereFirebaseableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FireBaseDeviceToken whereFirebaseableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FireBaseDeviceToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FireBaseDeviceToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|FireBaseDeviceToken withTrashed()
 * @method static \Illuminate\Database\Query\Builder|FireBaseDeviceToken withoutTrashed()
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|FireBaseDeviceToken whereDeletedAt($value)
 */
class FireBaseDeviceToken extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function firebaseable(): MorphTo
    {
        return $this->morphTo();
    }
}
