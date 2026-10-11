<?php

declare(strict_types=1);

namespace App\Middleware;

use Core\Contracts\MiddlewareInterface;
use Core\Facades\RateLimiter;
use Core\Request;
use Core\TerminateException;

/**
 * Rate limiting middleware.
 *
 * Route level usage / Route seviyesinde kullanım:
 *   Route::post('/login', ...)->middleware('throttle:5,300');
 *   // → 5 attempts / 300 seconds (IP based) // 5 deneme / 300 saniye (IP bazlı)
 *
 *   Route::post('/api/data', ...)->middleware('throttle:60,60');
 *   // → 60 requests / minute // 60 istek / dakika
 *
 *   Route::post('/reset', ...)->middleware('throttle:login');
 *   // → named limiter defined via RateLimiter::for('login', ...) // RateLimiter::for('login', ...) ile tanımlanan named limiter
 */
class ThrottleMiddleware implements MiddlewareInterface
{
    private int $maxAttempts;

    private int $decaySeconds;

    private string $limiterName;

    /**
     * @param  string  $param  "maxAttempts,decaySeconds" or named limiter name // "maxAttempts,decaySeconds" veya named limiter adı
     */
    public function __construct(string $param = '60,60')
    {
        /** @var \Core\RateLimiter $rl */
        $rl = RateLimiter::getFacadeRoot();

        // Named limiter check
        // Named limiter kontrolü
        if (! str_contains($param, ',')) {
            // Tanımsız named limiter (RateLimiter::for() çağrılmamış ya da adda typo)
            // sessizce 60/60'a düşüyordu — sıkı bir limit (5/300) fark edilmeden
            // gevşerdi. Güvenlik limiti sessizce gevşemek yerine net hata versin.
            // An undefined named limiter (RateLimiter::for() never called, or a typo)
            // silently fell back to 60/60 — a strict limit (5/300) loosened unnoticed.
            // A security limit must fail loudly instead of silently relaxing.
            $config = $rl->limiter($param);
            if ($config === null) {
                throw new \InvalidArgumentException(
                    "Rate limiter [{$param}] is not defined — call RateLimiter::for('{$param}', ...) first. "
                    ."// Rate limiter [{$param}] tanımlı değil — önce RateLimiter::for('{$param}', ...) çağırın."
                );
            }

            $this->limiterName = $param;
            $this->maxAttempts = array_key_exists('max', $config) ? (int) $config['max'] : 60;
            $this->decaySeconds = array_key_exists('decay', $config) ? (int) $config['decay'] : 60;
        } else {
            [$max, $decay] = explode(',', $param, 2);
            $this->maxAttempts = (int) $max;
            $this->decaySeconds = (int) $decay;
            $this->limiterName = '';
        }
    }

    public function handle(Request $request, \Closure $next): mixed
    {
        /** @var \Core\RateLimiter $rl */
        $rl = RateLimiter::getFacadeRoot();

        // İsimsiz throttle'da anahtara method+path ekle ki her route kendi sayacına
        // sahip olsun (klasik global throttle'a göre daha katı izolasyon).
        // İsimli limiter (throttle:login) kasıtlı olarak paylaşımlı kalır.
        // Unnamed throttle scopes the counter per route (method+path) so different
        // routes don't share a bucket. Named limiters stay intentionally shared.
        $key = $rl->key(
            $this->limiterName ?: 'throttle:'.$request->method().':'.$request->path(),
            $request->ip()
        );

        // Increment first under an atomic lock, then decide — this removes the
        // check-then-increment race where concurrent requests could slip past the
        // limit. The request that pushes the counter over the cap is the one
        // rejected, so exactly $maxAttempts requests are allowed per window.
        // Önce atomik kilit altında artır, sonra karar ver — bu, eşzamanlı
        // isteklerin limiti aşabildiği "önce kontrol et sonra artır" yarışını
        // ortadan kaldırır. Sayacı limiti aşan istek reddedilir; böylece pencere
        // başına tam olarak $maxAttempts istek serbesttir.
        $attempts = $rl->hit($key, $this->decaySeconds);

        if ($attempts > $this->maxAttempts) {
            $waitSeconds = (string) $rl->availableIn($key, $this->decaySeconds);

            // expectsJson() covers AJAX + Accept: application/json + Bearer token, so
            // stateless API clients get a proper 429 JSON response instead of a 302
            // redirect (which would also start a session via flash()/back()).
            // expectsJson() AJAX + Accept: application/json + Bearer token'ı kapsar;
            // böylece stateless API client'ları 302 redirect yerine (ki flash()/back()
            // üzerinden session da başlatırdı) düzgün bir 429 JSON yanıtı alır.
            if ($request->expectsJson()) {
                http_response_code(429);
                header('Content-Type: application/json; charset=utf-8');
                header('Retry-After: '.$waitSeconds);
                echo json_encode([
                    'error' => 'too_many_requests',
                    'message' => 'Çok fazla istek gönderildi. '.$waitSeconds.' saniye bekleyin.',
                    'retry_after' => $waitSeconds,
                ], JSON_UNESCAPED_UNICODE);
                throw new TerminateException;
            }

            flash('error', 'Çok fazla deneme yaptınız. '.$waitSeconds.' saniye sonra tekrar deneyin.');
            back();
        }

        return $next($request);
    }
}
