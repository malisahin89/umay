<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasTranslations;
use App\Support\Translatable;
use Core\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property Carbon|null $start_date
 * @property Carbon|null $end_date
 * @property bool $is_annual
 * @property string $display_frequency
 * @property array<int, string>|null $target_routes
 * @property bool $is_active
 * @property-read string|null $title
 * @property-read string|null $content
 */
class Popup extends Model implements Translatable
{
    use HasTranslations;

    protected $table = 'popups';

    protected $fillable = [
        'start_date',
        'end_date',
        'is_annual',
        'display_frequency',
        'target_routes',
        'is_active',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_annual' => 'boolean',
        'target_routes' => 'array',
        'is_active' => 'boolean',
    ];

    /** @var array<int, string> */
    protected array $translatable = [
        'title',
        'content',
        'image',
        'button_text',
        'button_url',
    ];

    /**
     * Active popups whose schedule currently allows them to show, translations
     * eager-loaded. Frequency (once/session/always) is enforced client-side.
     * Programı şu an gösterime izin veren aktif popup'lar, çevirileri eager-load'lu.
     * Sıklık (once/session/always) istemci tarafında uygulanır.
     *
     * @return Collection<int, Popup>
     */
    public static function activeNow(): Collection
    {
        /** @var Collection<int, Popup> $rows */
        $rows = static::query()
            ->where('is_active', true)
            ->with('translations')
            ->orderBy('id')
            ->get();

        /** @var Collection<int, Popup> $active */
        $active = $rows->filter(static fn (Popup $p): bool => $p->isWithinSchedule())->values();

        return $active;
    }

    /**
     * Whether "now" falls inside the popup's start/end window. Null bounds are
     * open-ended; is_annual compares only month-day so a window recurs yearly and
     * may wrap the new year (e.g. 20 Dec → 5 Jan).
     * "Şimdi" popup'ın başlangıç/bitiş aralığında mı. Null sınırlar açık uçludur;
     * is_annual yalnızca ay-günü karşılaştırır, böylece aralık her yıl tekrarlar ve
     * yıl dönümünü sarabilir (örn. 20 Ara → 5 Oca).
     */
    public function isWithinSchedule(?Carbon $now = null): bool
    {
        $now = $now ?? Carbon::now();
        $start = $this->start_date;
        $end = $this->end_date;

        if ($start === null && $end === null) {
            return true;
        }

        if ($this->is_annual) {
            $md = static fn (Carbon $d): int => (int) $d->format('md');
            $n = $md($now);
            $s = $start !== null ? $md($start) : 101;
            $e = $end !== null ? $md($end) : 1231;

            return $s <= $e ? ($n >= $s && $n <= $e) : ($n >= $s || $n <= $e);
        }

        if ($start !== null && $now->lt($start)) {
            return false;
        }

        if ($end !== null && $now->gt($end)) {
            return false;
        }

        return true;
    }
}
