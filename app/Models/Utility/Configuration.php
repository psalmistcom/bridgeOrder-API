<?php

namespace App\Models\Utility;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Utility\Configuration
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string $default
 * @property string $value
 * @property string $value_type
 * @property int $editor_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Configuration newModelQuery()
 * @method static Builder|Configuration newQuery()
 * @method static Builder|Configuration query()
 * @method static Builder|Configuration whereCreatedAt($value)
 * @method static Builder|Configuration whereDefault($value)
 * @method static Builder|Configuration whereEditorId($value)
 * @method static Builder|Configuration whereId($value)
 * @method static Builder|Configuration whereName($value)
 * @method static Builder|Configuration whereTitle($value)
 * @method static Builder|Configuration whereUpdatedAt($value)
 * @method static Builder|Configuration whereValue($value)
 * @method static Builder|Configuration whereValueType($value)
 * @mixin \Eloquent
 * @property string|null $deleted_at
 * @method static Builder|Configuration whereDeletedAt($value)
 */
class Configuration extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'title', 'default', 'value', 'value_type', 'editor_id'
    ];

    /**
     * Get the current value of a configuration
     * Or return the preferred value if the configuration doesn't exist
     *
     * @param string $key
     * @param mixed|null $value
     * @return mixed
     */
    public static function value(string $key, mixed $value = null): mixed
    {
        $config = self::whereName($key)->first();

        return optional($config)->value ?? $value;
    }

    /**
     * Get the default value of a configuration
     * Or return the preferred value if the configuration doesn't exist
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public static function default(string $key, mixed $default = null): mixed
    {
        $config = self::whereName($key)->first();

        return optional($config)->default ?? $default;
    }
}
