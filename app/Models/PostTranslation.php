<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $post_id
 * @property string $language_slug
 * @property string $title
 * @property string $slug
 * @property string|null $short_description
 * @property string|null $content
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property string|null $seo_keywords
 */
class PostTranslation extends Model
{
    protected $table = 'post_translations';

    protected $fillable = [
        'post_id',
        'language_slug',
        'title',
        'slug',
        'short_description',
        'content',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_slug', 'slug');
    }
}
