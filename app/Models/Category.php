<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasTranslations;
use App\Support\Translatable;
use Core\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int|null $parent_id
 * @property int $level
 * @property string|null $path
 * @property string|null $color
 * @property string|null $icon
 * @property int $status
 * @property bool $show_in_nav
 * @property int $nav_order
 * @property int $sort_order
 * @property-read string|null $name
 * @property-read string|null $slug
 */
class Category extends Model implements Translatable
{
    use HasTranslations;

    protected $table = 'categories';

    protected $fillable = [
        'parent_id',
        'level',
        'path',
        'color',
        'icon',
        'status',
        'show_in_nav',
        'nav_order',
        'sort_order',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'show_in_nav' => 'boolean',
    ];

    /** @var array<int, string> */
    protected array $translatable = [
        'name',
        'slug',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_category');
    }
}
