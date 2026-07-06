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

        if ($allowOrigin !== null) {
            $credentials = (bool) config('middleware.cors_credentials', false);

            $defaultMethods = 'GET, POST, PUT, PATCH, DELETE, OPTIONS';
            $defaultHeaders = 'Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN, Accept';
            $methods = config('middleware.cors_methods', $defaultMethods);
            $headers = config('middleware.cors_headers', $defaultHeaders);
            $maxAge = config('middleware.cors_max_age', 86400);

            header('Access-Control-Allow-Origin: '.$allowOrigin);
            // Any time the response depends on the Origin, caches must vary on it.
            // Yanıt Origin'e bağlıysa cache'ler ona göre varyans göstermeli.
            if ($allowOrigin !== '*') {
                header('Vary: Origin');
            }
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
        $configured = config('middleware.cors_origin', '*');
        if (is_array($configured)) {
            $list = $configured;
        } else {
            $originStr = is_string($configured) ? $configured : '*';
            $list = array_values(array_filter(array_map('trim', explode(',', $originStr))));
        }

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
}
