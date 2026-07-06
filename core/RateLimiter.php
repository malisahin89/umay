<?php

declare(strict_types=1);

namespace Core;

/**
 * RateLimiter — cache-backed rate limiter.
 * RateLimiter — cache tabanlı hız sınırlayıcı.
 *
 * Instance-based architecture — resolved from Container via Facade.
 * Instance tabanlı mimari — Facade aracılığıyla Container'dan çözümlenir.
 *
 * Usage via Facade / Facade ile kullanım:
 *   RateLimiter::hit('login_' . md5($ip), 300);
 *   RateLimiter::tooManyAttempts('login_' . md5($ip), 5, 300);
 *   RateLimiter::clear('login_' . md5($ip));
 *   RateLimiter::for('api', 60, 60);
 *
 * Route::middleware('throttle:5,300')   → ThrottleMiddleware çağırır
 * Route::middleware('throttle:login')   → Önceden tanımlanmış limiter adı
 */
class RateLimiter
{
    /** Önceden tanımlanmış named limiter'lar */
    private array $limiters = [];

    private Cache $cache;

    public function __construct()
    {
        // Resolve Cache from Container to maintain isolation
        // İzolasyonu korumak için Cache'i Container'dan çöz
        $this->cache = Container::getInstance()->make(Cache::class);
    }

    /**
     * Named limiter tanımla.
     *
     * RateLimiter::for('login', 5, 300);   // 300 sn'de 5 deneme
     */
    public function for(string $name, int $maxAttempts, int $decaySeconds = 60): void
    {
        $this->limiters[$name] = ['max' => $maxAttempts, 'decay' => $decaySeconds];
    }

    /**
     * Named limiter config'ini al.
     */
    public function limiter(string $name): ?array
    {
        return $this->limiters[$name] ?? null;
    }

    /**
     * Limit aşıldı mı kontrol et.
     *
     * @param  string  $key  Cache anahtarı (örn: 'login_' . md5($ip))
     * @param  int  $maxAttempts  İzin verilen maksimum deneme
     * @param  int  $decaySeconds  Kaç saniyede sıfırlanır
     */
    public function tooManyAttempts(string $key, int $maxAttempts, int $decaySeconds = 60): bool
    {
        $data = $this->cache->get($key);
        if (! is_array($data) || ! isset($data['reset_at'], $data['attempts'])) {
            return false;
        }

        // Süre dolmuşsa serbest
        // If decay time has elapsed, user is free
        if ((time() - $data['reset_at']) >= $decaySeconds) {
            return false;
        }

        return $data['attempts'] >= $maxAttempts;
    }

    /**
     * Sayacı bir artır.
     *
     * @return int Yeni deneme sayısı
     */
    public function hit(string $key, int $decaySeconds = 60): int
    {
        // Atomik oku-değiştir-yaz: eşzamanlı isteklerde sayaç yarış-koşulu olmadan artar.
        // Atomic read-modify-write: the counter increments without a race under concurrency.
        $data = $this->cache->atomic($key, function ($current) use ($decaySeconds) {
            $attempts = (is_array($current) && isset($current['attempts']) && is_int($current['attempts']))
                ? $current['attempts'] : 0;
            $resetAt = (is_array($current) && isset($current['reset_at']) && is_int($current['reset_at']))
                ? $current['reset_at'] : 0;

            // Süre dolmuşsa veya geçerli veri yoksa sıfırla
            // Reset when the window elapsed or there is no valid data yet
            if ($resetAt === 0 || (time() - $resetAt) >= $decaySeconds) {
                $attempts = 0;
                $resetAt = time();
            }

            return ['attempts' => $attempts + 1, 'reset_at' => $resetAt];
        }, $decaySeconds + 10); // biraz fazla TTL

        return (is_array($data) && isset($data['attempts']) && is_int($data['attempts'])) ? $data['attempts'] : 0;
    }

    /**
     * Sayacı sıfırla (başarılı işlem sonrası).
     */
    public function clear(string $key): void
    {
        $this->cache->forget($key);
    }

    /**
     * Mevcut deneme sayısını al.
     */
    public function attempts(string $key): int
    {
        $data = $this->cache->get($key);

        return (is_array($data) && isset($data['attempts']) && is_int($data['attempts'])) ? $data['attempts'] : 0;
    }

    /**
     * Kalan deneme hakkı.
     */
    public function remaining(string $key, int $maxAttempts): int
    {
        return max(0, $maxAttempts - $this->attempts($key));
    }

    /**
     * Kaç saniye sonra serbest olur (0 = şu an serbest).
     */
    public function availableIn(string $key, int $decaySeconds): int
    {
        $data = $this->cache->get($key);
        if (! is_array($data) || ! isset($data['reset_at'])) {
            return 0;
        }
        $elapsed = time() - $data['reset_at'];

        return max(0, $decaySeconds - $elapsed);
    }

    /**
     * Cache anahtarı üret — IP + route kombinasyonu.
     */
    public function key(string $prefix, ?string $suffix = null): string
    {
        $suffix = $suffix ?? (getRealIP());

        return 'rl_'.$prefix.'_'.md5($suffix);
    }
}
