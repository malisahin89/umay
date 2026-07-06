<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $key
 * @property string $name
 */
class Menu extends Model
{
    protected $table = 'menus';

    protected $fillable = [
        'key',
        'name',
    ];

    /** All items belonging to this menu (flat). / Bu menüye ait tüm öğeler (düz). */
    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }

    /** Top-level items only. / Sadece kök seviye öğeler. */
    public function rootItems(): HasMany
    {
        $relation = $this->hasMany(MenuItem::class);
        $relation->getQuery()->whereNull('parent_id')->orderBy('order');

        return $relation;
    }
}
