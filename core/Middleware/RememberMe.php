<?php

declare(strict_types=1);

namespace Core\Middleware;

use Core\Auth;
use Core\Container;
use Core\Contracts\MiddlewareInterface;
use Core\Request;

/**
 * Remember Me Middleware — Automatic login via "Remember Me" cookie.
 * Remember Me Middleware — "Beni Hatırla" cookie'si ile otomatik login.
 *
 * Must run BEFORE SecurityHeaders and VerifyCsrfToken as a global middleware.
 * Global middleware olarak SecurityHeaders ve VerifyCsrfToken'dan ÖNCE çalışmalıdır.
 *
 * If there is no user_id in the session but a remember_me cookie exists, the token is verified
 * and the user is automatically logged in.
 * Session'da user_id yoksa ama remember_me cookie'si varsa, token doğrulanıp
 * kullanıcı otomatik olarak oturuma alınır.
 *
 * Cookie format: "user_id:plain_token" // Cookie formatı: "user_id:plain_token"
 * Stored in DB: hash('sha256', plain_token) // DB'de saklanan: hash('sha256', plain_token)
 */
class RememberMe implements MiddlewareInterface
{
    public function handle(Request $request, \Closure $next): mixed
    {
        $auth = Container::getInstance()->make(Auth::class);

        // Skip if already logged in or cookie is missing
        // Zaten giriş yapmışsa veya cookie yoksa geç
        if ($auth->check() || ! isset($_COOKIE['remember_me'])) {
            return $next($request);
        }

        $cookie = $_COOKIE['remember_me'];
        $parts = explode(':', $cookie, 2);

        if (count($parts) !== 2) {
            // Invalid format — clear cookie
            // Geçersiz format — cookie'yi temizle
            self::clearCookie();

            return $next($request);
        }

        [$userId, $plainToken] = $parts;

        // Accept both integer and string/UUID identifiers — the provider contract
        // (retrieveByToken) takes int|string, so forcing (int) here would silently
        // break remember-me for UUID/string primary keys.
        // Hem tamsayı hem string/UUID kimliği kabul et — provider sözleşmesi
        // (retrieveByToken) int|string alır; burada (int)'e zorlamak UUID/string
        // primary key'lerde remember-me'yi sessizce bozardı.
        if ($userId === '' || $plainToken === '') {
            self::clearCookie();

            return $next($request);
        }

        // Lookup + timing-safe token verification are delegated to the configured
        // provider — this middleware no longer knows the user model class.
        // Arama + zamanlama-güvenli token doğrulama yapılandırılmış provider'a
        // devredilir — bu middleware artık kullanıcı modeli sınıfını tanımaz.
        $provider = $auth->provider();
        $user = $provider->retrieveByToken($userId, hash('sha256', $plainToken));

        if (! $user) {
            self::clearCookie();

            return $next($request);
        }

        // Token is correct — log the user in
        // Token doğru — kullanıcıyı oturuma al
        $auth->login($user);

        // Token BİLEREK rotate edilmez. Her istekte rotation, session'ı dolmuş bir
        // kullanıcı iki sekmeyi aynı anda açtığında yarışıyordu: ilk istek token'ı
        // döndürür, ikinci istek eski cookie ile gelir, doğrulama düşer ve cookie
        // silinirdi — kullanıcı sebepsiz yere "beni hatırla"yı kaybederdi. Laravel ile
        // aynı semantik: token her açık login(remember: true)'da yenilenir ve
        // logout()'ta depodan silinir (çalınan cookie o anda geçersizleşir).
        // The token is deliberately NOT rotated here. Per-request rotation raced when a
        // user with an expired session opened two tabs at once: the first request
        // rotated the token, the second arrived with the old cookie, failed
        // verification and cleared the cookie — silently dropping "remember me".
        // Same semantics as Laravel: the token is refreshed on every explicit
        // login(remember: true) and removed from storage on logout() (which also
        // invalidates a stolen cookie at that moment).

        return $next($request);
    }

    private static function clearCookie(): void
    {
        setcookie('remember_me', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => isSecure(),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }
}
