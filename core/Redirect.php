<?php

declare(strict_types=1);

namespace Core;

class Redirect
{
    public static function to(string $url): void
    {
        // Strip CR/LF — header injection koruması
        $url = str_replace(["\r", "\n"], '', $url);

        // Protocol-relative (//evil.com, /\evil.com) tarayıcıda mutlak URL gibi davranır —
        // "güvenli relative" sayılmamalı. Güvenli fallback: ana sayfa.
        // Protocol-relative (//evil.com, /\evil.com) acts as an absolute URL in browsers —
        // must NOT be treated as a "safe relative". Safe fallback: homepage.
        if (preg_match('#^/[\\\\/]#', $url) === 1) {
            header('Location: /');
            throw new RedirectException;
        }

        // Relative URL (/ ile başlıyorsa) — güvenli, doğrudan geç
        if (str_starts_with($url, '/')) {
            header('Location: '.$url);
            throw new RedirectException;
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
            header('Location: /'.$url);
            throw new RedirectException;
        }

        // Absolute URL → yalnızca APP_URL host'u ile eşleşiyorsa izin ver.
        // APP_URL tanımsızsa veya host farklıysa external sayılır → deny.
        // Absolute URL → allow only when it matches the APP_URL host.
        // If APP_URL is unset or the host differs it is external → deny.
        $appHost = parse_url($_ENV['APP_URL'] ?? '', PHP_URL_HOST);
        $urlHost = parse_url($url, PHP_URL_HOST);

        if ($appHost && $urlHost && strtolower($urlHost) === strtolower($appHost)) {
            header('Location: '.$url);
            throw new RedirectException;
        }

        // Güvenli fallback: ana sayfaya yönlendir
        header('Location: /');
        throw new RedirectException;
    }

    public static function route(string $name): void
    {
        $url = Route::url($name);
        self::to($url);
    }
}
