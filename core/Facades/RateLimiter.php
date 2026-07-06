<?php

declare(strict_types=1);

namespace Core\Facades;

use Core\Support\Facade;

/**
 * RateLimiter Facade — static proxy for Core\RateLimiter.
 * RateLimiter Facade — Core\RateLimiter için statik proxy.
 *
 * Usage / Kullanım:
 *   RateLimiter::hit('login_' . md5($ip), 300);
 *   RateLimiter::tooManyAttempts('login_' . md5($ip), 5, 300);
 *   RateLimiter::clear('login_' . md5($ip));
 *   RateLimiter::for('api', 60, 60);
 *
 * @method static void for(string $name, int $maxAttempts, int $decaySeconds = 60)
 * @method static ?array limiter(string $name)
 * @method static bool tooManyAttempts(string $key, int $maxAttempts, int $decaySeconds = 60)
 * @method static int hit(string $key, int $decaySeconds = 60)
 * @method static void clear(string $key)
 * @method static int attempts(string $key)
 * @method static int remaining(string $key, int $maxAttempts)
 * @method static int availableIn(string $key, int $decaySeconds)
 * @method static string key(string $prefix, ?string $suffix = null)
 *
 * @see \Core\RateLimiter
 */
class RateLimiter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Core\RateLimiter::class;
    }
}
