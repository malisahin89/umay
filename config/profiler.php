<?php

declare(strict_types=1);

return [
    /*
     * Is profiler enabled?
     * Disabled by default in production.
     *
     * Profiler aktif mi?
     * Production'da varsayılan olarak kapalıdır.
     */
    'enabled' => filter_var($_ENV['PROFILER_ENABLED'] ?? $_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),

    /*
     * Directory where profiler data will be stored.
     * Profil verilerinin depolanacağı dizin.
     */
    'storage_path' => (defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__)).'/storage/profiler',

    /*
     * Lifetime of profiler files (in seconds).
     * Default: 2 hours (7200 seconds).
     *
     * Profil dosyalarının yaşam süresi (saniye).
     * Varsayılan: 2 saat (7200 saniye).
     */
    'ttl' => (int) ($_ENV['PROFILER_TTL'] ?? 7200),

    /*
     * Maximum number of profiles to keep.
     * Oldest profiles are deleted when this number is exceeded.
     *
     * Maksimum saklanacak profil sayısı.
     * Bu sayı aşıldığında en eski profiller silinir.
     */
    'max_entries' => (int) ($_ENV['PROFILER_MAX_ENTRIES'] ?? 200),

    /*
     * IP addresses allowed to access the profiler.
     * Empty array = allow all IPs (safe only in dev environment).
     *
     * Profiler'a erişim izni verilen IP adresleri.
     * Boş array = tüm IP'lere izin (sadece dev ortamında güvenlidir).
     */
    'ip_whitelist' => array_filter(
        explode(',', $_ENV['PROFILER_IP_WHITELIST'] ?? '127.0.0.1,::1')
    ),
];
