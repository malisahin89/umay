<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $product_id
 * @property string $language_slug
 * @property string $title
 * @property string $slug
 * @property string|null $short_description
 * @property array<string, mixed>|null $specifications
 * @property string|null $content
 * @property string|null $features
 * @property string|null $attributes
 * @property string|null $documents
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property string|null $seo_keywords
 */
class ProductTranslation extends Model
{
    protected $table = 'product_translations';

    protected $fillable = [
        'product_id',
        'language_slug',
        'title',
        'slug',
        'short_description',
        'specifications',
        'content',
        'features',
        'attributes',
        'documents',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'specifications' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_slug', 'slug');
    }
}
