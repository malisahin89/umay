<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Models\Language;
use App\Support\Locale;
use Core\Contracts\MiddlewareInterface;
use Core\Facades\View;
use Core\Request;

/**
 * Resolves the active locale from the URL {locale} segment and validates it
 * against the active languages. Invalid locale → redirect to the default one.
 * Aktif dili URL'deki {locale} segmentinden çözer ve aktif dillere karşı doğrular.
 * Geçersiz dil → varsayılan dile yönlendirir.
 *
 * Wire with the /{locale} prefix group:
 * /{locale} prefix grubuyla kullanılır:
 *   Route::prefix('{locale}')->middleware('locale')->group(function () { ... });
 */
class LocaleMiddleware implements MiddlewareInterface
{
    /**
     * Localized route groups have a literal locale prefix (/es, /de …) with no
     * {locale} route param, so they pass it as a middleware argument (locale:es).
     * The canonical {locale} fallback group passes nothing and reads the route param.
     * Localized route grupları literal locale prefix'ine sahip (/es, /de …) ve
     * {locale} route param'ı yok; bu yüzden dili middleware argümanı olarak geçerler
     * (locale:es). Kanonik {locale} fallback grubu ise route param'ından okur.
     */
    public function __construct(private ?string $locale = null) {}

    public function handle(Request $request, \Closure $next): mixed
    {
        $requested = $this->locale ?? (is_string($l = $request->route('locale')) ? $l : '');

        /** @var array<int, string> $active */
        $active = Language::active()->pluck('slug')->all();

        // No languages configured yet → don't block, fall back to config default.
        // Henüz dil tanımlı değil → engelleme, config varsayılanına düş.
        if ($active === []) {
            Locale::set(Locale::default());

            return $next($request);
        }

        // Unknown locale → send the visitor to the default-locale home.
        // Bilinmeyen dil → ziyaretçiyi varsayılan-dil ana sayfasına gönder.
        if (! in_array($requested, $active, true)) {
            redirect('/'.Locale::default());

            return null;
        }

        Locale::set($requested);

        // Default language-switcher map shared with every front view: each active
        // locale → its home. Content pages that know their translated slug override
        // 'langUrls' via view() data (share < page data), so detail pages still deep-link.
        // Her ön yüz view'ine paylaşılan varsayılan dil-değiştirici haritası: her aktif
        // locale → kendi ana sayfası. Çevirili slug'ını bilen içerik sayfaları 'langUrls'ı
        // view() verisiyle ezer (share < sayfa verisi); böylece detay sayfaları yine derin bağlar.
        $langUrls = [];
        foreach ($active as $loc) {
            $langUrls[$loc] = '/'.$loc;
        }
        View::share('langUrls', $langUrls);

        return $next($request);
    }
}
