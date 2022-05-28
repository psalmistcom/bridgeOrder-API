<?php

namespace App\Models\Utility;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Utility\CustomNotification
 *
 * @property int $id
 * @property string $notificable_type
 * @property int $notificable_id
 * @property string $title
 * @property string $data
 * @property string $read_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $notificable
 * @method static Builder|CustomNotification newModelQuery()
 * @method static Builder|CustomNotification newQuery()
 * @method static \Illuminate\Database\Query\Builder|CustomNotification onlyTrashed()
 * @method static Builder|CustomNotification query()
 * @method static Builder|CustomNotification whereCreatedAt($value)
 * @method static Builder|CustomNotification whereData($value)
 * @method static Builder|CustomNotification whereId($value)
 * @method static Builder|CustomNotification whereNotificableId($value)
 * @method static Builder|CustomNotification whereNotificableType($value)
 * @method static Builder|CustomNotification whereReadAt($value)
 * @method static Builder|CustomNotification whereTitle($value)
 * @method static Builder|CustomNotification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|CustomNotification withTrashed()
 * @method static \Illuminate\Database\Query\Builder|CustomNotification withoutTrashed()
 * @mixin Eloquent
 * @property string|null $deleted_at
 * @method static Builder|CustomNotification whereDeletedAt($value)
 */
class CustomNotification extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function notificable(): MorphTo
    {
        return $this->morphTo();
    }
}
