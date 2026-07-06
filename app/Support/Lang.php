<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Static UI-string translator for views (buttons, labels, chrome).
 * View'lar için statik arayüz metni çevirmeni (buton, etiket, kabuk).
 *
 * Reads lang/{locale}.php dictionaries picked by the active Locale. This is the
 * chrome layer; model *content* is translated separately by HasTranslations.
 *
 * Aktif Locale'e göre lang/{locale}.php sözlüklerini okur. Bu, arayüz katmanıdır;
 * model *içeriği* ayrıca HasTranslations tarafından çevrilir.
 *
 * Lang::t('read')                 → "Oku" / "Read"
 * Lang::t('greeting', ['name' => 'Ali']) → ":name" yerine "Ali" konur
 */
final class Lang
{
    /** @var array<string, array<mixed>> Loaded dictionaries per locale. */
    private static array $cache = [];

    /**
     * Translate a key for the active locale. Dot notation reaches nested keys.
     * Aktif dil için bir anahtarı çevir. Nokta notasyonu iç içe anahtarlara ulaşır.
     *
     * Resolution: active locale → fallback locale → the key itself (so a missing
     * translation is visible, not blank).
     * Çözüm sırası: aktif dil → yedek dil → anahtarın kendisi (eksik çeviri boş
     * değil, görünür kalır).
     *
     * @param  array<string, string|int|float>  $replace  :placeholder → value
     */
    public static function t(string $key, array $replace = []): string
    {
        $value = self::lookup($key, Locale::get());

        if ($value === null && Locale::fallback() !== Locale::get()) {
            $value = self::lookup($key, Locale::fallback());
        }

        return self::replace(is_string($value) ? $value : $key, $replace);
    }

    /**
     * Pluralized translation. The value holds "singular|plural" forms; the plural
     * is used for every count other than 1. `:count` is filled from $count.
     * Çoğullu çeviri. Değer "tekil|çoğul" biçimlerini tutar; 1 dışındaki her sayı
     * için çoğul kullanılır. `:count`, $count ile doldurulur.
     *
     * choice('count.posts', 3) → "3 posts" / "3 yazı"
     *
     * @param  array<string, string|int|float>  $replace
     */
    public static function choice(string $key, int $count, array $replace = []): string
    {
        $line = self::t($key);
        $parts = explode('|', $line);
        $form = ($count === 1) ? $parts[0] : ($parts[1] ?? $parts[0]);

        return self::replace($form, $replace + ['count' => $count]);
    }

    /**
     * Substitute :placeholder tokens in a line.
     * Bir satırdaki :placeholder belirteçlerini değiştir.
     *
     * @param  array<string, string|int|float>  $replace
     */
    private static function replace(string $line, array $replace): string
    {
        foreach ($replace as $search => $with) {
            $line = str_replace(':'.$search, (string) $with, $line);
        }

        return $line;
    }

    /**
     * Look a dot-notation key up in one locale's dictionary; null if absent.
     * Bir dilin sözlüğünde nokta-notasyonlu anahtarı ara; yoksa null.
     */
    private static function lookup(string $key, string $locale): ?string
    {
        $dict = self::load($locale);

        $value = $dict;
        foreach (explode('.', $key) as $part) {
            if (! is_array($value) || ! array_key_exists($part, $value)) {
                return null;
            }
            $value = $value[$part];
        }

        return is_string($value) ? $value : null;
    }

    /**
     * Load and cache lang/{locale}.php; empty array when the file is missing.
     * lang/{locale}.php'yi yükle ve önbelleğe al; dosya yoksa boş dizi.
     *
     * @return array<mixed>
     */
    private static function load(string $locale): array
    {
        if (! array_key_exists($locale, self::$cache)) {
            $base = defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__, 2);
            if (! is_string($base)) {
                $base = dirname(__DIR__, 2);
            }
            $path = $base."/lang/{$locale}.php";

            $loaded = file_exists($path) ? require $path : [];
            self::$cache[$locale] = is_array($loaded) ? $loaded : [];
        }

        return self::$cache[$locale];
    }
}
