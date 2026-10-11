<?php

declare(strict_types=1);

namespace Core;

class Redirect
{
    public static function to(string $url): void
    {
        header('Location: '.self::resolve($url));
        throw new RedirectException;
    }

    public static function route(string $name): void
    {
        $url = Route::url($name);
        self::to($url);
    }

    /**
     * Resolve a safe HTTP Location target. Pure & testable — any caller that
     * must inspect the decision (tests, diagnostics) can call this directly.
     *
     * Güvenli bir HTTP Location hedefi çöz. Saf & test edilebilir — kararı
     * incelemesi gereken her çağırıcı (testler, teşhis) bunu doğrudan kullanabilir.
     */
    public static function resolve(string $url): string
    {
        // Strip CR/LF — header injection koruması
        $url = str_replace(["\r", "\n"], '', $url);

        // Protocol-relative (//evil.com, /\evil.com) ve TEK backslash (\\evil.com —
        // önce '/'.$url ile "/\evil.com" olur, tarayıcıda "//evil.com"a dönüşür)
        // tarayıcıda mutlak URL gibi davranır — "güvenli relative" sayılmamalı.
        // Güvenli fallback: ana sayfa.
        // Protocol-relative (//evil.com, /\evil.com) and a SINGLE leading backslash
        // (\\evil.com — "/".$url turns it into "/\evil.com", which browsers normalize
        // to "//evil.com") act as absolute URLs in browsers — they must NOT be
        // treated as "safe relative". Safe fallback: homepage.
        if (preg_match('#^/[\\\\/]#', $url) === 1 || str_starts_with($url, '\\')) {
            return '/';
        }

        // Relative URL (/ ile başlıyorsa) — güvenli, doğrudan geç
        if (str_starts_with($url, '/')) {
            return $url;
        }

        // Şemasız, slash'sız göreli yol ("dashboard") — başına '/' ekleyerek güvenli
        // kabul et; önceden sessizce ana sayfaya düşüyordu (debug'ı zor davranış).
        // Şema içerenler (http:, javascript:, mailto: …) aşağıdaki mutlak-URL
        // denetimine iner; "evil.com/x" gibi host görünümlü değerler "/evil.com/x"
        // olur ve tarayıcıda yalnızca yerel bir yoldur.
        // A scheme-less, slash-less relative path ("dashboard") — treat as safe by
        // prefixing '/'; previously it silently fell through to the homepage (hard to
        // debug). Anything carrying a scheme (http:, javascript:, mailto: …) drops to
        // the absolute-URL check below; host-looking values like "evil.com/x" become
        // "/evil.com/x", which is only a local path in the browser.
        if ($url !== '' && ! preg_match('#^[a-zA-Z][a-zA-Z0-9+.\-]*:#', $url)) {
            return '/'.$url;
        }

        // Absolute URL → yalnızca APP_URL host'u ile eşleşiyorsa izin ver.
        // APP_URL tanımsızsa veya host farklıysa external sayılır → deny.
        // Absolute URL → allow only when it matches the APP_URL host.
        // If APP_URL is unset or the host differs it is external → deny.
        $appHost = parse_url($_ENV['APP_URL'] ?? '', PHP_URL_HOST);
        $urlHost = parse_url($url, PHP_URL_HOST);

        if ($appHost && $urlHost && strtolower($urlHost) === strtolower($appHost)) {
            return $url;
        }

        // Güvenli fallback: ana sayfaya yönlendir
        return '/';
    }
}