<?php

declare(strict_types=1);

/*
 * Cache is file-based (storage/cache/) with HMAC integrity + TTL.
 * The values below are consumed by Core\Cache.
 *
 * Cache dosya tabanlıdır (storage/cache/), HMAC bütünlük + TTL ile.
 * Aşağıdaki değerler Core\Cache tarafından kullanılır.
 */
return [
    /*
     * Cache directory.
     * Cache dizini.
     */
    'path' => (defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__)).'/storage/cache',

    /*
     * Cache key prefix — applied before hashing the key; prevents collisions.
     * Cache key prefix — anahtar hash'lenmeden önce eklenir; çakışmaları önler.
     */
    'prefix' => $_ENV['CACHE_PREFIX'] ?? 'umay_',

    /*
     * Default TTL (seconds) — used when Cache::set() is called without a ttl.
     * Varsayılan TTL (saniye) — Cache::set() ttl olmadan çağrıldığında kullanılır.
     */
    'default_ttl' => (int) ($_ENV['CACHE_TTL'] ?? 3600),
];
