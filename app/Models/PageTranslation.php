<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $page_id
 * @property string $language_slug
 * @property string $title
 * @property string $slug
 * @property string|null $content
 * @property array<string, mixed>|null $blocks
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property string|null $seo_keywords
 */
class PageTranslation extends Model
{
    protected $table = 'page_translations';

    protected $fillable = [
        'page_id',
        'language_slug',
        'title',
        'slug',
        'content',
        'blocks',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'blocks' => 'array',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_slug', 'slug');
    }
}
