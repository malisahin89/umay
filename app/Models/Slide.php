<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasTranslations;
use App\Support\Translatable;
use Core\Model;

/**
 * @property int $id
 * @property string $type
 * @property string|null $media_file
 * @property string $text_position
 * @property int $label_size
 * @property int $title_size
 * @property int $subtitle_size
 * @property int $order
 * @property int $status
 * @property-read string|null $title
 * @property-read string|null $label
 */
class Slide extends Model implements Translatable
{
    use HasTranslations;

    protected $table = 'slides';

    protected $fillable = [
        'type',
        'media_file',
        'text_position',
        'label_size',
        'title_size',
        'subtitle_size',
        'order',
        'status',
    ];

    /** @var array<int, string> */
    protected array $translatable = [
        'label',
        'title',
        'subtitle',
        'button_text',
        'button_url',
    ];
}
