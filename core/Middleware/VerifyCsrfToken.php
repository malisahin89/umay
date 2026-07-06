<?php

declare(strict_types=1);

namespace Core\Middleware;

use Core\Contracts\MiddlewareInterface;
use Core\Csrf;
use Core\CsrfException;
use Core\Request;

class VerifyCsrfToken implements MiddlewareInterface
{
    public function handle(Request $request, \Closure $next): mixed
    {
        // Only check methods that perform modifications such as POST, PUT, PATCH, DELETE
        // Sadece POST, PUT, PATCH, DELETE gibi değişiklik yapılan metodlarda kontrol yap
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {

            // CSRF token check (mandatory for ALL POST requests)
            // CSRF token kontrolü (TÜM POST isteklerinde zorunlu)
            $token = $request->post('csrf_token') ?? $request->header('X-CSRF-TOKEN') ?? null;

            if (! Csrf::check($token)) {
                // ExceptionHandler tek noktadan formatlar (AJAX/API → JSON, web → HTML)
                // ExceptionHandler formats centrally (AJAX/API → JSON, web → HTML)
                throw new CsrfException('CSRF protection: Token mismatch. // CSRF koruması: Token doğrulanamadı.');
            }
        }

        return $next($request);
    }
}
