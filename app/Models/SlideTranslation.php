<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $slide_id
 * @property string $language_slug
 * @property string|null $label
 * @property string|null $title
 * @property string|null $subtitle
 * @property string|null $button_text
 * @property string|null $button_url
 */
class SlideTranslation extends Model
{
    protected $table = 'slide_translations';

    protected $fillable = [
        'slide_id',
        'language_slug',
        'label',
        'title',
        'subtitle',
        'button_text',
        'button_url',
    ];

    public function slide(): BelongsTo
    {
        return $this->belongsTo(Slide::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_slug', 'slug');
    }
}
