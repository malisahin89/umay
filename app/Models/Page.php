<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasTranslations;
use App\Support\Translatable;
use Core\Model;

/**
 * @property int $id
 * @property string $template
 * @property int $status
 * @property int $sort_order
 * @property-read string|null $title
 * @property-read string|null $slug
 * @property-read string|null $content
 */
class Page extends Model implements Translatable
{
    use HasTranslations;

    protected $table = 'pages';

    protected $fillable = [
        'template',
        'status',
        'sort_order',
    ];

    /** @var array<int, string> */
    protected array $translatable = [
        'title',
        'slug',
        'content',
        'blocks',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];
}
