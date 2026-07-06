<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $flag
 * @property int $status
 * @property bool $is_default
 */
class Language extends Model
{
    protected $table = 'languages';

    protected $fillable = [
        'name',
        'slug',
        'flag',
        'status',
        'is_default',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'is_default' => 'boolean',
        'status' => 'integer',
    ];

    // is_default_flag is a DB-generated (virtual) column — never write to it.
    // is_default_flag DB tarafından üretilen (virtual) bir kolon — asla yazma.
    protected $guarded = [
        'is_default_flag',
    ];

    /** Active languages only. / Sadece aktif diller. */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 1);
    }

    /** @var Collection<int, self>|null Request-level memo. */
    private static ?Collection $activeMemo = null;

    /**
     * Active languages (default first), memoized per request. Every front request needs
     * this list several times (LocaleMiddleware, language switcher, localized URLs); the
     * memo collapses those N identical queries into a single one.
     *
     * Aktif diller (varsayılan önce), istek başına memoize. Her front isteği bu listeyi
     * birkaç kez ister; memo bu N aynı sorguyu tek sorguya indirir.
     *
     * @return Collection<int, self>
     */
    public static function active(): Collection
    {
        if (self::$activeMemo === null) {
            $query = static::query()->where('status', 1);
            $query->orderByDesc('is_default');
            /** @var Collection<int, self> $langs */
            $langs = $query->get();
            self::$activeMemo = $langs;
        }

        return self::$activeMemo;
    }

    /** Drop the memo (after any language change within the same request). */
    public static function flushActive(): void
    {
        self::$activeMemo = null;
    }

    /** The single default language. / Tek varsayılan dil. */
    public static function default(): ?self
    {
        /** @var self|null $lang */
        $lang = static::query()->where('is_default', true)->first();

        return $lang;
    }
}
