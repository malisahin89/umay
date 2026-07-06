<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $menu_item_id
 * @property string $language_slug
 * @property string $label
 */
class MenuItemTranslation extends Model
{
    protected $table = 'menu_item_translations';

    protected $fillable = [
        'menu_item_id',
        'language_slug',
        'label',
    ];

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_slug', 'slug');
    }
}
