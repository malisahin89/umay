<?php

declare(strict_types=1);

namespace Core\Middleware;

use Core\Auth as Authenticator;
use Core\Container;
use Core\Contracts\MiddlewareInterface;
use Core\Request;

/**
 * Auth — session-based authentication guard for web routes.
 * Auth — web route'ları için session tabanlı kimlik doğrulama guard'ı.
 *
 * Resolved from the middleware name 'auth' (→ Core\Middleware\Auth).
 * 'auth' middleware adından çözülür (→ Core\Middleware\Auth).
 *
 *   Route::get('/dashboard', 'DashboardController@index')->middleware('auth');
 *   Route::prefix('/admin')->middleware('auth')->group(function () { ... });
 *
 * Guests are sent to the login path (config('auth.login_path')) — or answered
 * with a 401 JSON body when the request expects JSON.
 *
 * Misafirler login yoluna (config('auth.login_path')) yönlendirilir — istek JSON
 * bekliyorsa 401 JSON gövdesi döner.
 */
class Auth implements MiddlewareInterface
{
    public function handle(Request $request, \Closure $next): mixed
    {
        $auth = Container::getInstance()->make(Authenticator::class);

        if ($auth instanceof Authenticator && $auth->check()) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            abort(401, 'Unauthenticated. // Kimlik doğrulanamadı.');
        }

        // Web request — send the guest to the configured login path. A developer
        // pointing this at a route name/URL should set auth.login_path accordingly.
        // Web isteği — misafiri yapılandırılmış login yoluna gönder.
        $loginPath = config('auth.login_path', '/login');
        redirect(is_string($loginPath) ? $loginPath : '/login');

        return null;
    }
}
