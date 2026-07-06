<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;
use Illuminate\Database\Capsule\Manager;

/**
 * @property int $id
 * @property string $name
 * @property string $label
 */
class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = ['name', 'label'];

    public $timestamps = false;

    /**
     * Return the names of permissions assigned to a role.
     * Bir role atanmış izinlerin adlarını döndür.
     *
     * @return array<int, string>
     */
    public static function forRole(string $role): array
    {
        /** @var array<int, string> $names */
        $names = Manager::table('role_permissions')
            ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->where('role_permissions.role', $role)
            ->pluck('permissions.name')
            ->all();

        return $names;
    }
}
