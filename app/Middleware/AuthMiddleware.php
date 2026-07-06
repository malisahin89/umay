<?php

declare(strict_types=1);

namespace App\Middleware;

use Core\Contracts\MiddlewareInterface;
use Core\Facades\Auth;
use Core\Request;

/**
 * Guard for authenticated routes.
 * Kimliği doğrulanmış route'lar için koruma.
 *
 * Identity resolution is delegated to the core (Core\Auth via the Auth facade),
 * which already understands the session + remember-me cookie. This middleware adds
 * the app-level policy on top: absolute + idle session timeouts.
 *
 * Kimlik çözümü çekirdeğe (Auth facade üzerinden Core\Auth) devredilir; o zaten
 * session + remember-me cookie'sini anlar. Bu middleware üstüne uygulama seviyesi
 * politikayı ekler: mutlak + hareketsizlik (idle) oturum zaman aşımları.
 */
class AuthMiddleware implements MiddlewareInterface
{
    private const SESSION_TIMEOUT = 1800; // absolute — 30 min // mutlak — 30 dk

    private const IDLE_TIMEOUT = 900;     // inactivity — 15 min // hareketsizlik — 15 dk

    public function handle(Request $request, \Closure $next): mixed
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Not authenticated (and no valid remember-me) → back to login.
        // Kimlik yok (ve geçerli remember-me de yok) → login'e dön.
        if (Auth::guest()) {
            flash('error', 'Lütfen giriş yapın.');
            redirect('login.show');

            return null;
        }

        // Absolute timeout — session older than SESSION_TIMEOUT is force-expired.
        // Mutlak zaman aşımı — SESSION_TIMEOUT'tan eski oturum zorla sonlandırılır.
        $loginTime = is_numeric($lt = $_SESSION['login_time'] ?? null) ? (int) $lt : time();
        if ((time() - $loginTime) > self::SESSION_TIMEOUT) {
            return $this->expire('Oturumunuz zaman aşımına uğradı. Lütfen tekrar giriş yapın.');
        }

        // Idle timeout — no activity for IDLE_TIMEOUT.
        // Hareketsizlik zaman aşımı — IDLE_TIMEOUT süresince aktivite yok.
        $lastActivity = is_numeric($la = $_SESSION['last_activity'] ?? null) ? (int) $la : time();
        if ((time() - $lastActivity) > self::IDLE_TIMEOUT) {
            return $this->expire('Hareketsizlik nedeniyle oturumunuz sonlandırıldı.');
        }

        // Refresh activity marker for the next request.
        // Sonraki istek için aktivite işaretini tazele.
        $_SESSION['last_activity'] = time();
        $_SESSION['login_time'] ??= time();

        return $next($request);
    }

    private function expire(string $message): mixed
    {
        Auth::logout();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        flash('error', $message);
        redirect('login.show');

        return null;
    }
}
