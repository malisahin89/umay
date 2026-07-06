<?php

declare(strict_types=1);

namespace Core\Facades;

use Core\Support\Facade;

/**
 * Cache Facade — static proxy for Core\Cache.
 * Cache Facade — Core\Cache için statik proxy.
 *
 * Usage / Kullanım:
 *   Cache::get('key');
 *   Cache::set('key', 'value', 3600);
 *   Cache::remember('key', 3600, fn() => expensiveQuery());
 *   Cache::forget('key');
 *   Cache::flush();
 *   Cache::has('key');
 *   Cache::pull('key');
 *
 * @method static mixed get(string $key, mixed $default = null)
 * @method static void set(string $key, mixed $value, int $ttl = 3600)
 * @method static mixed remember(string $key, int $ttl, callable $callback)
 * @method static void forget(string $key)
 * @method static void flush()
 * @method static mixed pull(string $key, mixed $default = null)
 * @method static bool has(string $key)
 *
 * @see \Core\Cache
 */
class Cache extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Core\Cache::class;
    }
}
