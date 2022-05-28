<?php

namespace App\Models\Utility;

use App\Models\Vendor\Restaurant;
use Illuminate\Database\Eloquent\{Builder, Factories\HasFactory, Model};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo};
use Eloquent;
use Illuminate\Support\Carbon;

/**
 * App\Models\Utility\ActivityLog
 *
 * @property int $id
 * @property string|null $loggable_type
 * @property int|null $loggable_id
 * @property int|null $target_id
 * @property string|null $subject
 * @property string|null $type
 * @property string $description
 * @property array|null $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $loggable
 * @property-read Restaurant|null $restaurant
 * @method static Builder|ActivityLog newModelQuery()
 * @method static Builder|ActivityLog newQuery()
 * @method static Builder|ActivityLog query()
 * @method static Builder|ActivityLog whereCreatedAt($value)
 * @method static Builder|ActivityLog whereData($value)
 * @method static Builder|ActivityLog whereDescription($value)
 * @method static Builder|ActivityLog whereId($value)
 * @method static Builder|ActivityLog whereLoggableId($value)
 * @method static Builder|ActivityLog whereLoggableType($value)
 * @method static Builder|ActivityLog whereTargetId($value)
 * @method static Builder|ActivityLog whereType($value)
 * @method static Builder|ActivityLog whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string|null $deleted_at
 * @method static Builder|ActivityLog whereDeletedAt($value)
 */
class ActivityLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'data' => 'json'
    ];

    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'target_id');
    }

    public static function log($model, $modelId, $data, $type, $description, $targetId = null): void
    {
        self::create([
            'loggable_type' => $model,
            'loggable_id' => $modelId,
            'target_id' => $targetId,
            'type' => $type,
            'description' => $description,
            'data' => $data,
        ]);
    }
}
