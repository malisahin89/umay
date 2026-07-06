<?php

declare(strict_types=1);

namespace Core\Middleware;

use Core\Contracts\MiddlewareInterface;
use Core\Csp;
use Core\RedirectException;
use Core\Request;

class SecurityHeaders implements MiddlewareInterface
{
    public function handle(Request $request, \Closure $next): mixed
    {
        // Legacy XSS auditor — disabled per current OWASP guidance. The old
        // "1; mode=block" filter is removed from modern browsers and could itself
        // introduce vulnerabilities; the nonce-based CSP below is the real defense.
        // Eski XSS denetleyici — güncel OWASP önerisine göre devre dışı. Eski
        // "1; mode=block" filtresi modern tarayıcılardan kaldırıldı ve kendisi
        // zafiyet doğurabilir; asıl koruma aşağıdaki nonce tabanlı CSP'dir.
        header('X-XSS-Protection: 0');

        // Content type sniffing protection
        // Content type sniffing koruması
        header('X-Content-Type-Options: nosniff');

        // Clickjacking protection
        // Clickjacking koruması
        header('X-Frame-Options: DENY');

        // Referrer policy
        // Referrer policy
        header('Referrer-Policy: strict-origin-when-cross-origin');

        // HSTS — instruct browsers to use HTTPS on every future request (mitigates
        // SSL-strip after the first visit). Sent ONLY over an active HTTPS connection
        // — a header on plain HTTP is ignored and would wrongly pin a local/dev host —
        // and never in local dev. 1 year, include subdomains. isSecure() also
        // recognises TLS terminated at a trusted proxy (X-Forwarded-Proto).
        // HSTS — tarayıcıya sonraki her istekte HTTPS kullanmasını söyle (ilk ziyaret
        // sonrası SSL-strip'i azaltır). YALNIZCA aktif bir HTTPS bağlantısında gönderilir
        // — düz HTTP'deki header yok sayılır ve local/dev host'u yanlışlıkla sabitlerdi —
        // ve local dev'de asla. 1 yıl, subdomain'ler dahil. isSecure(), güvenilir
        // proxy'de sonlandırılan TLS'i de tanır (X-Forwarded-Proto).
        if (($_ENV['APP_ENV'] ?? 'production') !== 'local' && isSecure()) {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }

        // Generate a request-local CSP nonce. Kept out of the shared session so
        // concurrent requests can't race and emit a header nonce that mismatches the
        // one rendered into the page. Csp::nonce() lazily generates + caches it; the
        // View `nonce()` helper reads the same value back.
        // Request bazlı CSP nonce üret. Paylaşılan session'da tutulmaz; böylece
        // eşzamanlı istekler yarışıp sayfaya basılanla uyuşmayan bir header nonce'u
        // gönderemez. Csp::nonce() tembel üretip önbellekler; View `nonce()` helper'ı
        // aynı değeri geri okur.
        Csp::reset();
        $nonce = Csp::nonce();

        // Content Security Policy (With Nonce - more secure)
        // Content Security Policy (Nonce ile - daha güvenli)
        // Only allow unsafe-inline when APP_ENV=local; all other environments use strict CSP.
        // Sadece APP_ENV=local iken unsafe-inline izinli; diğer tüm ortamlar strict CSP kullanır.
        // This ensures production-safe behavior even if APP_ENV is undefined or misconfigured.
        // Bu sayede APP_ENV tanımsız veya yanlış ayarlı olsa bile production-safe davranış korunur.
        if (($_ENV['APP_ENV'] ?? 'production') === 'local') {
            // Local development: unsafe-inline allowed (for ease of development)
            // Local development: unsafe-inline izinli (geliştirme kolaylığı için)
            header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'nonce-{$nonce}' https://cdn.tailwindcss.com https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://fonts.googleapis.com; font-src 'self' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://fonts.gstatic.com; img-src 'self' data: blob: https://flagcdn.com; connect-src 'self';");
        } else {
            // Production / staging: Strict, nonce-based CSP.
            // The Tailwind Play CDN (cdn.tailwindcss.com) is intentionally NOT allowed here:
            // it executes arbitrary JS to compile CSS at runtime, which would defeat the
            // nonce strategy and is unsupported for production by Tailwind itself.
            // Self-host/compile your CSS for production. unsafe-inline stays on style-src only.
            //
            // Production / staging: Sıkı, nonce tabanlı CSP.
            // Tailwind Play CDN (cdn.tailwindcss.com) burada bilerek izinli DEĞİL:
            // CSS'i runtime'da derlemek için keyfi JS çalıştırır, bu da nonce stratejisini
            // boşa çıkarır ve Tailwind tarafından production için desteklenmez.
            // Production'da CSS'inizi self-host edin/derleyin. unsafe-inline yalnızca style-src'de kalır.
            header("Content-Security-Policy: default-src 'self'; script-src 'self' 'nonce-{$nonce}' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://fonts.googleapis.com; font-src 'self' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://fonts.gstatic.com; img-src 'self' data: blob: https://flagcdn.com; connect-src 'self'; object-src 'none'; base-uri 'self'; form-action 'self'; frame-ancestors 'none';");
        }

        // HTTPS redirect (for production). isSecure() prevents an infinite redirect
        // loop behind a TLS-terminating proxy, where $_SERVER['HTTPS'] is never set.
        // HTTPS yönlendirme (production için). isSecure(), $_SERVER['HTTPS']'in hiç
        // set edilmediği TLS sonlandıran proxy arkasında sonsuz yönlendirme
        // döngüsünü önler.
        if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'production' && ! isSecure()) {
            // Build the host from the configured canonical URL, never from the
            // client-controlled Host header (host-header injection / cache poisoning).
            // Host'u client-kontrollü Host header'ından değil, yapılandırılmış kanonik
            // URL'den türet (host-header injection / cache poisoning koruması).
            $appUrl = config('app.url');
            $host = (is_string($appUrl) ? parse_url($appUrl, PHP_URL_HOST) : null) ?: 'localhost';
            $uri = $_SERVER['REQUEST_URI'] ?? '/';
            $uri = is_string($uri) ? $uri : '/';
            header('Location: https://'.$host.$uri, true, 301);
            throw new RedirectException;
        }

        return $next($request);
    }
}
