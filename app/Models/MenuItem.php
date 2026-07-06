<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasTranslations;
use App\Support\Translatable;
use Core\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $menu_id
 * @property int|null $parent_id
 * @property string $type
 * @property string|null $route_name
 * @property string|null $route_param
 * @property string|null $url
 * @property string $target
 * @property int $order
 * @property bool $is_active
 * @property-read string|null $label
 */
class MenuItem extends Model implements Translatable
{
    use HasTranslations;

    protected $table = 'menu_items';

    protected $fillable = [
        'menu_id',
        'parent_id',
        'type',
        'route_name',
        'route_param',
        'url',
        'target',
        'order',
        'is_active',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /** @var array<int, string> */
    protected array $translatable = [
        'label',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        $relation = $this->hasMany(self::class, 'parent_id');
        $relation->getQuery()->orderBy('order');

        return $relation;
    }
}
