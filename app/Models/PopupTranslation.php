<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $popup_id
 * @property string $language_slug
 * @property string|null $title
 * @property string|null $content
 * @property string|null $image
 * @property string|null $button_text
 * @property string|null $button_url
 */
class PopupTranslation extends Model
{
    protected $table = 'popup_translations';

    protected $fillable = [
        'popup_id',
        'language_slug',
        'title',
        'content',
        'image',
        'button_text',
        'button_url',
    ];

    public function popup(): BelongsTo
    {
        return $this->belongsTo(Popup::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_slug', 'slug');
    }
}
