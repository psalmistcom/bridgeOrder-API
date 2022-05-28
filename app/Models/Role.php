<?php

namespace App\Models;

use App\Models\Admin\Admin;
use App\Models\Customer\User;
use App\Models\Vendor\Vendor;
use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Collection|Admin[] $admins
 * @property-read int|null $admins_count
 * @property-read Collection|User[] $users
 * @property-read int|null $users_count
 * @property-read Collection|Vendor[] $vendors
 * @property-read int|null $vendors_count
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string $type
 * @method static \Illuminate\Database\Query\Builder|Role onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereType($value)
 * @method static \Illuminate\Database\Query\Builder|Role withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Role withoutTrashed()
 */
class Role extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const ADMIN_ROLES = [
        'SUPER_ADMIN' => 'super_admin'
    ];

    public const VENDOR_ROLES = [
        'VENDOR_ADMIN' => 'vendor_admin',
        'VENDOR_SERVICE' => 'vendor_service',
        'VENDOR_ACCOUNTING' => 'vendor_accounting'
    ];

    public const ADMIN_TYPE = 'admin';
    public const VENDOR_TYPE = 'vendor';

    public function admins(): HasMany
    {
        return $this->hasMany(Admin::class);
    }

    public function vendors(): HasMany
    {
        return $this->hasMany(Vendor::class);
    }
}
