<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Active locale holder for the current request.
 * Mevcut istek için aktif dil taşıyıcısı.
 *
 * Set by LocaleMiddleware from the URL {locale}; read by HasTranslations to pick
 * which translation row to expose. Falls back to config('locale.*').
 *
 * LocaleMiddleware tarafından URL {locale}'den set edilir; HasTranslations hangi
 * çeviri satırının gösterileceğini seçmek için okur. config('locale.*')'a düşer.
 */
final class Locale
{
    private static ?string $current = null;

    public static function set(string $slug): void
    {
        self::$current = $slug;
    }

    public static function get(): string
    {
        return self::$current ?? self::default();
    }

    public static function default(): string
    {
        return is_string($v = config('locale.default')) ? $v : 'tr';
    }

    public static function fallback(): string
    {
        return is_string($v = config('locale.fallback')) ? $v : self::default();
    }

    /**
     * Locale slug → ISO 3166-1 alpha-2 country code for flag images (flagcdn.com).
     * A stored 2-letter override wins; otherwise a small map; otherwise the slug.
     * Locale slug → bayrak görselleri (flagcdn.com) için ISO ülke kodu. Kayıtlı 2-harfli
     * değer öncelikli; yoksa küçük eşleme; yoksa slug.
     */
    public static function flag(string $slug, ?string $stored = null): string
    {
        if (is_string($stored) && preg_match('/^[a-zA-Z]{2}$/', $stored)) {
            return strtolower($stored);
        }
        $map = [
            'tr' => 'tr', 'en' => 'gb', 'de' => 'de', 'fr' => 'fr', 'es' => 'es', 'it' => 'it',
            'ru' => 'ru', 'ar' => 'sa', 'zh' => 'cn', 'ja' => 'jp', 'nl' => 'nl', 'pl' => 'pl', 'pt' => 'pt',
        ];

        return $map[strtolower($slug)] ?? strtolower($slug);
    }
}
