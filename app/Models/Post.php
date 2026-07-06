<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasTranslations;
use App\Support\Translatable;
use Core\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $order
 * @property string|null $cover_image
 * @property array<int, string>|null $gallery_images
 * @property bool $is_featured
 * @property bool $comment_enabled
 * @property int $status
 * @property int $view_count
 * @property Carbon|null $published_at
 * @property-read string|null $title
 * @property-read string|null $slug
 * @property-read string|null $content
 */
class Post extends Model implements Translatable
{
    use HasTranslations;

    protected $table = 'posts';

    protected $fillable = [
        'user_id',
        'order',
        'cover_image',
        'gallery_images',
        'is_featured',
        'comment_enabled',
        'status',
        'view_count',
        'published_at',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'gallery_images' => 'array',
        'is_featured' => 'boolean',
        'comment_enabled' => 'boolean',
        'published_at' => 'datetime',
    ];

    /** @var array<int, string> */
    protected array $translatable = [
        'title',
        'slug',
        'short_description',
        'content',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'post_category');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }
}
