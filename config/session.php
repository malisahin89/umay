<?php

declare(strict_types=1);

return [
    /*
     * Session lifetime — in minutes (default 30 minutes).
     * Session ömrü — dakika cinsinden (varsayılan 30 dakika).
     */
    'lifetime' => (int) ($_ENV['SESSION_LIFETIME'] ?? 30),

    /*
     * Cookie settings.
     * Cookie ayarları.
     */
    'cookie' => $_ENV['SESSION_COOKIE'] ?? 'umay_session',

    /*
     * Secure cookie — HTTPS üzerinde otomatik açılır (isSecure(): güvenilir proxy'nin
     * X-Forwarded-Proto'su dahil); SESSION_SECURE ile zorlanabilir.
     * Secure cookie — auto-enabled over HTTPS (isSecure(): includes X-Forwarded-Proto
     * from a trusted proxy); can be forced via SESSION_SECURE.
     */
    'secure' => filter_var(
        $_ENV['SESSION_SECURE'] ?? isSecure(),
        FILTER_VALIDATE_BOOLEAN
    ),
    'http_only' => true,
    'same_site' => $_ENV['SESSION_SAME_SITE'] ?? 'Strict',
];
