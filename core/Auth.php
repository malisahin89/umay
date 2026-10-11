<?php

declare(strict_types=1);

namespace Core;

use Core\Auth\EloquentUserProvider;
use Core\Contracts\Authenticatable;
use Core\Contracts\UserProvider;

/**
 * Auth — request-based cached authentication guard.
 * Auth — request bazlı önbellekli kimlik doğrulama guard'ı.
 *
 * Instance-based architecture — resolved from Container via Facade.
 * Instance tabanlı mimari — Facade aracılığıyla Container'dan çözümlenir.
 *
 * Decoupled: this guard knows NOTHING about App\Models\User.
 * It talks to a Core\Contracts\UserProvider (the "login brain") and a
 * Core\Contracts\Authenticatable (the user contract). Which concrete classes
 * are used is decided entirely by config/auth.php.
 *
 * Decoupled: bu guard App\Models\User'ı HİÇ tanımaz.
 * Bir Core\Contracts\UserProvider ("login beyni") ve bir
 * Core\Contracts\Authenticatable (kullanıcı sözleşmesi) ile konuşur. Hangi somut
 * sınıfların kullanılacağına tamamen config/auth.php karar verir.
 *
 * Usage via Facade / Facade ile kullanım:
 *   Auth::check()              → bool
 *   Auth::user()               → ?Authenticatable
 *   Auth::id()                 → int|string|null
 *   Auth::guest()              → bool
 *   Auth::login($user)         → void
 *   Auth::login($user, true)   → void (remember me)
 *   Auth::logout()             → void
 *   Auth::attempt(['email' => ..., 'password' => ...]) → bool
 */
class Auth
{
    private ?Authenticatable $cachedUser = null;

    /**
     * Was a provider lookup already attempted this request? Distinguishes
     * "not looked up yet" from "looked up, user gone" — so a stale session id
     * (deleted user) doesn't re-query the provider on every check()/user() call.
     *
     * Bu istekte provider araması yapıldı mı? "Henüz bakılmadı" ile "bakıldı,
     * kullanıcı yok" durumlarını ayırır — böylece bayat session id (silinmiş
     * kullanıcı) her check()/user() çağrısında provider'ı yeniden sorgulamaz.
     */
    private bool $userResolved = false;

    private ?UserProvider $provider = null;

    // ── Provider resolution ────────────────────────────────────────────────────
    // ── Provider çözümleme ─────────────────────────────────────────────────────

    /**
     * Resolve the active UserProvider from config/auth.php (lazy, cached).
     * Aktif UserProvider'ı config/auth.php'den çöz (tembel, önbellekli).
     *
     * To plug in your own login logic, point the provider 'driver' in
     * config/auth.php at a class implementing Core\Contracts\UserProvider.
     * Kendi login mantığınızı takmak için config/auth.php'deki provider 'driver'ını
     * Core\Contracts\UserProvider implemente eden bir sınıfa yönlendirin.
     */
    public function provider(): UserProvider
    {
        if ($this->provider !== null) {
            return $this->provider;
        }

        $name = (string) config('auth.default', 'eloquent');
        $config = config("auth.providers.{$name}", []);
        $driver = is_array($config) ? ($config['driver'] ?? EloquentUserProvider::class) : EloquentUserProvider::class;

        if (! is_string($driver) || ! class_exists($driver)) {
            throw new \RuntimeException('auth provider driver not found // bulunamadı (config/auth.php).');
        }

        $instance = new $driver(is_array($config) ? $config : []);

        if (! $instance instanceof UserProvider) {
            throw new \RuntimeException($driver.' must implement Core\Contracts\UserProvider.');
        }

        return $this->provider = $instance;
    }

    /**
     * Override the provider (useful for tests or custom runtime swapping).
     * Provider'ı override et (test veya runtime'da özel değiştirme için kullanışlı).
     */
    public function setProvider(UserProvider $provider): void
    {
        $this->provider = $provider;
    }

    // ── Read ─────────────────────────────────────────────────────────────────
    // ── Okuma ────────────────────────────────────────────────────────────────

    /**
     * Returns the logged-in user; null if none.
     * Storage is queried only once in the same request cycle.
     *
     * Giriş yapmış kullanıcıyı döndürür; yoksa null.
     * Aynı request döngüsünde depo sadece bir kez sorgulanır.
     */
    public function user(): ?Authenticatable
    {
        if ($this->cachedUser !== null || $this->userResolved) {
            return $this->cachedUser;
        }

        $userId = $_SESSION['user_id'] ?? null;

        // Only accept a real identifier (int or non-empty string/UUID). Falsy
        // values like false/null/'' and — importantly — a valid primary key of
        // 0 / '0' — must NOT be collapsed into a "guest" session.
        // Yalnızca gerçek bir kimlik kabul et (int veya boş olmayan string/UUID).
        // false/null/'' gibi falsy değerler ve — önemlisi — geçerli bir primary key
        // olan 0 / '0' "misafir" session'a indirilmemeli.
        if (! is_int($userId) && ! is_string($userId)) {
            return null;
        }
        if ($userId === '') {
            return null;
        }

        $this->userResolved = true;

        return $this->cachedUser = $this->provider()->retrieveById($userId);
    }

    /**
     * Logged-in user's identifier; null if none.
     * Preserves the identifier's native type (int or string) so string/UUID keys
     * are not silently truncated to 0 by an int cast.
     *
     * Giriş yapmış kullanıcının kimliği; yoksa null.
     * Kimliğin doğal tipini (int veya string) korur; böylece string/UUID anahtarlar
     * int cast'iyle sessizce 0'a düşürülmez.
     */
    public function id(): int|string|null
    {
        // Backed by user(), never the raw session value — a stale session id
        // (user deleted from storage) must yield null, consistent with check()/user().
        // user() üzerinden döner, ham session değerinden asla — bayat session id
        // (depodan silinmiş kullanıcı) null vermeli; check()/user() ile tutarlı.
        return $this->user()?->getAuthIdentifier();
    }

    /**
     * Is the user logged in? True only when the user actually RESOLVES from the
     * provider (or was set via setUser) — a session that still carries the id of a
     * deleted/disabled user must NOT count as authenticated, otherwise guards let
     * ghost sessions through and auth()->... explodes right after.
     *
     * Kullanıcı giriş yapmış mı? Yalnızca kullanıcı provider'dan gerçekten
     * ÇÖZÜLÜYORSA (veya setUser ile atandıysa) true — silinmiş/pasif kullanıcının
     * id'sini taşıyan session kimliği doğrulanmış sayılmamalı; aksi halde guard'lar
     * hayalet session'ları içeri alır ve hemen ardından auth()->... patlar.
     */
    public function check(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Is the user a guest (not logged in)?
     * Kullanıcı misafir mi (giriş yapmamış)?
     */
    public function guest(): bool
    {
        return ! $this->check();
    }

    /**
     * Mark a user as authenticated for the CURRENT request only (no session).
     * Used by stateless guards such as the api-auth (Bearer token) middleware.
     *
     * Bir kullanıcıyı YALNIZCA mevcut istek için kimliği doğrulanmış işaretle (session yok).
     * api-auth (Bearer token) middleware gibi stateless guard'lar tarafından kullanılır.
     */
    public function setUser(Authenticatable $user): void
    {
        $this->cachedUser = $user;
    }

    // ── Write ────────────────────────────────────────────────────────────────
    // ── Yazma ────────────────────────────────────────────────────────────────

    /**
     * Log the user into the session.
     * Kullanıcıyı oturuma al.
     *
     * @param  bool  $remember  Create "remember me" cookie (30 days) // "Beni hatırla" cookie'si oluştur (30 gün).
     */
    public function login(Authenticatable $user, bool $remember = false): void
    {
        session_regenerate_id(true);

        // Rotate the CSRF token on privilege change so a token captured pre-login
        // cannot be replayed against the now-authenticated session. The next
        // Csrf::generate() (e.g. in a rendered form) mints a fresh one.
        // Yetki değişiminde CSRF token'ını döndür; böylece login öncesi yakalanan bir
        // token, artık kimliği doğrulanmış session'a karşı yeniden kullanılamaz. Sonraki
        // Csrf::generate() (örn. render edilen bir formda) yenisini üretir.
        unset($_SESSION['csrf_token']);

        $_SESSION['user_id'] = $user->getAuthIdentifier();
        $_SESSION['login_time'] = time();

        $this->cachedUser = $user;

        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $rememberExpires = time() + 30 * 24 * 60 * 60;

            // Store "hash|expiry" so a stolen token also expires SERVER-SIDE —
            // otherwise a captured cookie stayed valid for the full 30 days no
            // matter what happened afterwards.
            // "hash|expiry" sakla ki çalınan token SUNUCU tarafında da süresi dolsun —
            // aksi halde yakalanan cookie sonrasında ne olursa olsun 30 gün geçerli kalıyordu.
            $this->provider()->updateRememberToken($user, hash('sha256', $token).'|'.$rememberExpires);

            setcookie('remember_me', $user->getAuthIdentifier().':'.$token, [
                'expires' => $rememberExpires,
                'path' => '/',
                // isSecure(): TLS sonlandıran proxy arkasında da Secure bayrağı korunur
                // isSecure(): keeps the Secure flag behind a TLS-terminating proxy too
                'secure' => isSecure(),
                'httponly' => true,
                'samesite' => 'Strict',
            ]);
        }
    }

    /**
     * Log the user out.
     * Clears the remember me cookie and session.
     *
     * Kullanıcıyı çıkış yaptır.
     * Remember me cookie'sini ve session'ı temizler.
     */
    public function logout(): void
    {
        // Clear remember me token from storage. Fall back to the cookie's user id
        // when the session user does not resolve (stale/deleted session) so the
        // stored token is still revoked.
        // Remember me token'ı depodan temizle. Session kullanıcısı çözülmezse
        // (bayat/silinmiş session) cookie'deki kullanıcı id'sine düş ki saklanan
        // token yine de iptal edilsin.
        $user = $this->user();
        if ($user === null && isset($_COOKIE['remember_me'])) {
            $rememberCookie = $_COOKIE['remember_me'];
            if (is_string($rememberCookie) && $rememberCookie !== '') {
                $cookieParts = explode(':', $rememberCookie, 2);

                // Cookie tabanlı revoke, token DOĞRULANDIKTAN sonra yapılır —
                // asla yalnızca cookie'deki kullanıcı id'sine güvenilmez. Aksi halde
                // bir misafir, sahte `remember_me=<kurbanId>:herhangi` cookie'siyle
                // kurbanın stored remember token'ını sildirtebilirdi (çapraz-hesap
                // zorla çıkış / DoS).
                // Cookie-based revocation only happens after the token VERIFIES —
                // the embedded user id alone is never trusted. Otherwise a guest
                // could wipe a victim's stored remember token with a forged
                // `remember_me=<victimId>:anything` cookie (cross-account forced
                // logout / DoS).
                if (count($cookieParts) === 2
                    && $cookieParts[0] !== ''
                    && $cookieParts[1] !== '') {
                    $candidate = $this->provider()->retrieveByToken(
                        $cookieParts[0],
                        hash('sha256', $cookieParts[1])
                    );
                    if ($candidate !== null) {
                        $user = $candidate;
                    }
                }
            }
        }
        if ($user && $user->getRememberToken()) {
            $this->provider()->updateRememberToken($user, null);
        }

        // Clear cookie
        // Cookie temizle
        if (isset($_COOKIE['remember_me'])) {
            setcookie('remember_me', '', [
                'expires' => time() - 3600,
                'path' => '/',
                'secure' => isSecure(),
                'httponly' => true,
                'samesite' => 'Strict',
            ]);
        }

        // Clear session
        // Session temizle
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();

        // Start a FRESH empty session so the rest of the request keeps working —
        // without this, a flash('success', 'Çıkış yapıldı') right after logout()
        // would be written into a destroyed session and silently lost.
        // TAZE boş bir session başlat ki isteğin kalanı çalışmaya devam etsin —
        // bu olmadan logout() hemen sonrası flash('success', 'Çıkış yapıldı')
        // yok edilmiş session'a yazılır ve sessizce kaybolurdu.
        if (session_status() === PHP_SESSION_NONE && ! headers_sent()) {
            session_start();
            session_regenerate_id(true);
        }

        $this->cachedUser = null;
        $this->userResolved = false;
    }

    /**
     * Attempt login with credentials (email + password by default).
     * Delegates user lookup & password check to the configured UserProvider.
     *
     * Kimlik bilgileriyle giriş dene (varsayılan: email + şifre).
     * Kullanıcı bulma ve şifre kontrolünü yapılandırılmış UserProvider'a devreder.
     *
     * Auth::attempt(['email' => $email, 'password' => $pass, 'remember' => true])
     *
     * @param  array<string, mixed>  $credentials
     */
    public function attempt(array $credentials): bool
    {
        $provider = $this->provider();

        // Throttle failed logins per (identifier + client IP) to slow brute force.
        // Başarısız girişleri (kimlik + client IP) başına sınırla — kaba kuvveti yavaşlatır.
        $throttleKey = $this->throttleKey($credentials);
        if ($this->tooManyAttempts($throttleKey)) {
            return false;
        }

        $user = $provider->retrieveByCredentials($credentials);

        if ($user === null) {
            // Constant-time: run a dummy bcrypt verify so a missing user takes
            // roughly as long as a wrong password — prevents user enumeration via
            // response timing (previously the miss returned before any bcrypt work).
            // Sabit-zaman: sahte bir bcrypt doğrulaması çalıştır ki olmayan kullanıcı
            // yanlış parola kadar sürsün — yanıt zamanlamasıyla kullanıcı numaralandırmayı önler.
            $attemptedPassword = $credentials['password'] ?? '';
            password_verify(is_string($attemptedPassword) ? $attemptedPassword : '', self::DUMMY_HASH);
            $this->hitThrottle($throttleKey);

            return false;
        }

        if ($provider->validateCredentials($user, $credentials)) {
            $this->clearThrottle($throttleKey);
            $remember = (bool) ($credentials['remember'] ?? false);
            $this->login($user, $remember);

            return true;
        }

        $this->hitThrottle($throttleKey);

        return false;
    }

    // ── Login throttling helpers ───────────────────────────────────────────────

    /** Max failed attempts within the decay window. */
    private const MAX_ATTEMPTS = 5;

    /** Decay window (seconds). */
    private const DECAY_SECONDS = 300;

    /**
     * A fixed bcrypt hash used only to equalize failure timing (never matches a
     * real login because the plaintext is unknown).
     */
    private const DUMMY_HASH = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

    /**
     * @param  array<string, mixed>  $credentials
     */
    private function throttleKey(array $credentials): ?string
    {
        $email = $credentials['email'] ?? null;
        if (! is_string($email) || $email === '') {
            return null;
        }

        $ip = $_SERVER['REMOTE_ADDR'] ?? 'cli';
        if (! is_string($ip) || $ip === '') {
            $ip = 'cli';
        }
        if (function_exists('getRealIP')) {
            $resolved = getRealIP();
            if ($resolved !== '') {
                $ip = $resolved;
            }
        }

        return 'login_'.hash('sha256', strtolower($email).'|'.$ip);
    }

    private function limiter(): ?RateLimiter
    {
        try {
            $limiter = Container::getInstance()->make(RateLimiter::class);

            return $limiter instanceof RateLimiter ? $limiter : null;
        } catch (\Throwable) {
            return null;
        }
    }

    private function tooManyAttempts(?string $key): bool
    {
        if ($key === null) {
            return false;
        }

        $limiter = $this->limiter();

        return $limiter !== null && $limiter->tooManyAttempts($key, self::MAX_ATTEMPTS, self::DECAY_SECONDS);
    }

    private function hitThrottle(?string $key): void
    {
        if ($key === null) {
            return;
        }

        $this->limiter()?->hit($key, self::DECAY_SECONDS);
    }

    private function clearThrottle(?string $key): void
    {
        if ($key === null) {
            return;
        }

        $this->limiter()?->clear($key);
    }

    /**
     * Clear cache (for testing or after logout).
     * Önbelleği temizle (test veya logout sonrası).
     */
    public function clearCache(): void
    {
        $this->cachedUser = null;
        $this->userResolved = false;
    }
}
