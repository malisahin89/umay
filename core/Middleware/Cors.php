<?php

declare(strict_types=1);

namespace Core\Middleware;

use Core\Contracts\MiddlewareInterface;
use Core\Request;
use Core\TerminateException;

/**
 * CORS Middleware — Cross-Origin Resource Sharing headers.
 * CORS Middleware — Cross-Origin Resource Sharing header'ları.
 *
 * Config (config/middleware.php):
 *   'cors_origin'      → '*' | 'https://a.com' | 'https://a.com,https://b.com' | [..]
 *   'cors_methods'     → allowed methods string
 *   'cors_headers'     → allowed request headers string
 *   'cors_credentials' → bool — send Access-Control-Allow-Credentials
 *   'cors_max_age'     → preflight cache seconds
 *
 * Behaviour / Davranış:
 *   - '*' (no credentials)            → echoes '*'.
 *   - explicit list                   → reflects the request Origin only when it is
 *                                        in the list, and adds `Vary: Origin`.
 *   - credentials enabled             → never sends '*'; reflects the concrete Origin
 *                                        (per the CORS spec, '*' + credentials is illegal).
 *   - Origin not allowed              → no CORS headers (the browser blocks it).
 *   - Preflight (OPTIONS)             → answered with 204.
 */
class Cors implements MiddlewareInterface
{
    public function handle(Request $request, \Closure $next): mixed
    {
        $requestOrigin = $request->header('Origin');
        $allowOrigin = $this->resolveAllowedOrigin($requestOrigin);

        // When CORS is origin-specific (an explicit allow-list rather than '*'),
        // the response varies by Origin — advertise it EVEN when this particular
        // Origin was denied, otherwise a shared cache could hand one origin's
        // response (with/without CORS headers) to another.
        // CORS origin'e özgüyse (joker '*' yerine açık bir liste), yanıt Origin'e
        // göre değişir — bu Origin reddedilmiş olsa BİLE bildir; aksi halde paylaşılan
        // cache bir origin'in yanıtını (CORS header'lılığıyla) diğerine verebilir.
        if ($this->isOriginSpecific()) {
            header('Vary: Origin');
        }

        if ($allowOrigin !== null) {
            $credentials = (bool) config('middleware.cors_credentials', false);

            $defaultMethods = 'GET, POST, PUT, PATCH, DELETE, OPTIONS';
            $defaultHeaders = 'Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN, Accept';
            $methods = config('middleware.cors_methods', $defaultMethods);
            $headers = config('middleware.cors_headers', $defaultHeaders);
            $maxAge = config('middleware.cors_max_age', 86400);

            header('Access-Control-Allow-Origin: '.$allowOrigin);
            if ($credentials && $allowOrigin !== '*') {
                header('Access-Control-Allow-Credentials: true');
            }
            header('Access-Control-Allow-Methods: '.(is_string($methods) ? $methods : $defaultMethods));
            header('Access-Control-Allow-Headers: '.(is_string($headers) ? $headers : $defaultHeaders));
            header('Access-Control-Max-Age: '.(is_numeric($maxAge) ? (int) $maxAge : 86400));
        }

        // Respond to Preflight (OPTIONS) requests quickly
        // Preflight (OPTIONS) isteklerini hızlıca yanıtla
        if ($request->method() === 'OPTIONS') {
            http_response_code(204);
            throw new TerminateException;
        }

        return $next($request);
    }

    /**
     * Decide which value to send in Access-Control-Allow-Origin (null = none).
     * Access-Control-Allow-Origin'de gönderilecek değeri belirle (null = gönderme).
     */
    private function resolveAllowedOrigin(?string $requestOrigin): ?string
    {
        $list = $this->configuredOrigins();

        $credentials = (bool) config('middleware.cors_credentials', false);

        if (in_array('*', $list, true)) {
            // Wildcard origin. With credentials this is forbidden by the CORS spec AND
            // reflecting an arbitrary Origin while sending Allow-Credentials would grant
            // EVERY site authenticated cross-origin access. Fail closed: require an explicit
            // allow-list when credentials are enabled (set cors_origin to your domains).
            // Wildcard origin. Credentials ile bu, CORS spec'i tarafından yasaktır VE
            // rastgele bir Origin'i yansıtıp Allow-Credentials göndermek HER siteye kimlik
            // doğrulamalı cross-origin erişim verirdi. Fail-closed: credentials açıkken açık
            // bir allow-list zorunlu (cors_origin'i kendi domain'lerinize ayarlayın).
            if ($credentials) {
                return null;
            }

            return '*';
        }

        if ($requestOrigin !== null && in_array($requestOrigin, $list, true)) {
            return $requestOrigin;
        }

        return null;
    }

    /**
     * The configured allow-list as an array (accepts a comma-separated string,
     * an array, or the wildcard '*').
     * Yapılandırılmış izin listesini dizi olarak döner (virgüllü string, dizi veya
     * joker '*' kabul eder).
     *
     * @return array<int, string>
     */
    private function configuredOrigins(): array
    {
        $configured = config('middleware.cors_origin', '*');

        if (is_array($configured)) {
            $origins = array_map(fn ($o): string => is_scalar($o) ? (string) $o : '', $configured);
        } elseif (is_string($configured)) {
            $origins = explode(',', $configured);
        } else {
            $origins = ['*'];
        }

        return array_values(array_filter(array_map('trim', $origins), fn ($o) => $o !== ''));
    }

    /**
     * True when CORS is tied to specific origins (no wildcard), so responses
     * must advertise `Vary: Origin`.
     * CORS belirli origin'lere bağlıysa (joker yok) true; yanıtlar
     * `Vary: Origin` bildirmelidir.
     */
    private function isOriginSpecific(): bool
    {
        return ! in_array('*', $this->configuredOrigins(), true);
    }
}
