<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasTranslations;
use App\Support\Translatable;
use Core\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $order
 * @property string|null $cover_image
 * @property array<int, string>|null $gallery_images
 * @property bool $is_featured
 * @property int $status
 * @property string|null $brand
 * @property string|null $price
 * @property string|null $model
 * @property string|null $type
 * @property string|null $fuel_type
 * @property string|null $heating_type
 * @property string|null $product_url
 * @property Carbon|null $published_at
 * @property-read string|null $title
 * @property-read string|null $slug
 */
class Product extends Model implements Translatable
{
    use HasTranslations;

    protected $table = 'products';

    protected $fillable = [
        'order',
        'cover_image',
        'gallery_images',
        'is_featured',
        'status',
        'brand',
        'price',
        'model',
        'type',
        'fuel_type',
        'heating_type',
        'product_url',
        'published_at',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'gallery_images' => 'array',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'price' => 'decimal:2',
    ];

    /** @var array<int, string> */
    protected array $translatable = [
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

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }
}
