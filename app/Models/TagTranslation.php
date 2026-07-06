<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $tag_id
 * @property string $language_slug
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property string|null $seo_keywords
 */
class TagTranslation extends Model
{
    protected $table = 'tag_translations';

    protected $fillable = [
        'tag_id',
        'language_slug',
        'name',
        'slug',
        'description',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_slug', 'slug');
    }
}
