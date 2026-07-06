<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasTranslations;
use App\Support\Translatable;
use Core\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string|null $color
 * @property int $status
 * @property int $usage_count
 * @property-read string|null $name
 * @property-read string|null $slug
 */
class Tag extends Model implements Translatable
{
    use HasTranslations;

    protected $table = 'tags';

    protected $fillable = [
        'color',
        'status',
        'usage_count',
    ];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_tag');
    }

    /** @var array<int, string> */
    protected array $translatable = [
        'name',
        'slug',
        'description',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];
}
