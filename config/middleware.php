<?php

declare(strict_types=1);

/**
 * Middleware Configuration
 *
 * Umay hybrid architecture — define Web and API middleware groups here.
 *
 * Route::setGroup('web')  → applies web middleware group
 * Route::setGroup('api')  → applies api middleware group
 *
 * Adding a new middleware:
 *   1. Create class in core/Middleware/ or app/Middleware/
 *   2. Add its name to the relevant group
 *
 * Middleware resolution order:
 *   App\Middleware\{Studly}Middleware  →  Core\Middleware\{Studly}
 *
 *
 * Middleware Konfigürasyonu
 *
 * Umay hibrit mimari — Web ve API middleware gruplarını burada tanımlayın.
 *
 * Route::setGroup('web')  → web middleware grubu uygulanır
 * Route::setGroup('api')  → api middleware grubu uygulanır
 *
 * Yeni middleware eklemek:
 *   1. core/Middleware/ veya app/Middleware/ altında sınıf oluşturun
 *   2. İlgili gruba adını ekleyin
 *
 * Middleware çözümleme sırası:
 *   App\Middleware\{Studly}Middleware  →  Core\Middleware\{Studly}
 */

return [

    /*
    |--------------------------------------------------------------------------
    | API Prefix
    |--------------------------------------------------------------------------
    |
    | Prefix for API routes. All routes in routes/api.php
    | will be loaded under this prefix.
    |
    | API route'larının prefix'i. routes/api.php içindeki tüm route'lar
    | bu prefix altında yüklenir.
    |
    | Örnek: '/api'  → GET /api/posts
    |         '/v1'  → GET /v1/posts
    |
    */
    'api_prefix' => $_ENV['API_PREFIX'] ?? '/api',

    /*
    |--------------------------------------------------------------------------
    | Global Middleware
    |--------------------------------------------------------------------------
    |
    | Runs on every HTTP request — both web and API.
    | DO NOT put Session or CSRF here.
    |
    | Her HTTP isteğinde çalışır — hem web hem API.
    | Session, CSRF gibi şeyleri buraya KOYMAYIN.
    |
    */
    'global' => [
        // Example: 'Logger',
        // Örnek: 'Logger',
    ],

    /*
    |--------------------------------------------------------------------------
    | Web Middleware Group
    |--------------------------------------------------------------------------
    |
    | Runs on session-based requests (routes/web.php).
    | CSRF protection, session, cookie, security headers go here.
    |
    | Session tabanlı isteklerde çalışır (routes/web.php).
    | CSRF koruması, session, cookie, güvenlik header'ları burada.
    |
    */
    'web' => [
        'RememberMe',
        'SecurityHeaders',
        'VerifyCsrfToken',
    ],

    /*
    |--------------------------------------------------------------------------
    | API Middleware Group
    |--------------------------------------------------------------------------
    |
    | Runs on stateless API requests (routes/api.php).
    | Session and CSRF DO NOT belong here. Token auth is added at route level.
    |
    | Stateless API isteklerinde çalışır (routes/api.php).
    | Session ve CSRF burada OLMAZ. Token auth route seviyesinde eklenir.
    |
    */
    'api' => [
        'Cors',
        'throttle:60,60',
    ],

    /*
    |--------------------------------------------------------------------------
    | CORS Settings
    |--------------------------------------------------------------------------
    |
    | Determines which origins can access the API.
    | '*' allows all origins (for development).
    | Specify exact domain in production.
    |
    | API'nin hangi origin'lerden erişilebilir olduğunu belirler.
    | '*' tüm origin'lere izin verir (geliştirme için).
    | Production'da spesifik domain belirtin.
    |
    */
    'cors_origin' => $_ENV['CORS_ORIGIN'] ?? '*',

    /*
    |--------------------------------------------------------------------------
    | CORS — methods / headers / credentials / preflight cache
    |--------------------------------------------------------------------------
    |
    | cors_credentials: send Access-Control-Allow-Credentials. When true, the
    | wildcard '*' origin is never sent (the spec forbids '*' with credentials);
    | the concrete request Origin is reflected instead — so set cors_origin to an
    | explicit allow-list in that case.
    |
    | cors_credentials: Access-Control-Allow-Credentials gönderir. true ise '*'
    | origin asla gönderilmez (spec '*' + credentials'ı yasaklar); somut istek
    | Origin'i yansıtılır — bu durumda cors_origin'i açık bir listeye ayarlayın.
    |
    */
    'cors_methods' => $_ENV['CORS_METHODS'] ?? 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
    'cors_headers' => $_ENV['CORS_HEADERS'] ?? 'Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN, Accept',
    'cors_credentials' => filter_var($_ENV['CORS_CREDENTIALS'] ?? false, FILTER_VALIDATE_BOOLEAN),
    'cors_max_age' => (int) ($_ENV['CORS_MAX_AGE'] ?? 86400),

    /*
    |--------------------------------------------------------------------------
    | Middleware Namespaces (class resolution)
    |--------------------------------------------------------------------------
    |
    | Ordered templates the router tries when resolving a middleware name to a
    | class. The first existing class wins. '{name}' is replaced with the
    | StudlyCase middleware name (e.g. 'api-auth' → 'ApiAuth').
    |
    | This lets the CORE router resolve middleware without hard-coding the
    | 'App\Middleware\' / 'Core\Middleware\' namespaces.
    |
    | Router'ın bir middleware adını sınıfa çözerken denediği sıralı şablonlar.
    | İlk var olan sınıf kazanır. '{name}', StudlyCase middleware adıyla değişir
    | (örn. 'api-auth' → 'ApiAuth').
    |
    | Böylece ÇEKİRDEK router, 'App\Middleware\' / 'Core\Middleware\'
    | namespace'lerini sabit kodlamadan middleware çözer.
    |
    */
    'namespaces' => [
        'App\\Middleware\\{name}Middleware',
        'Core\\Middleware\\{name}',
    ],

    /*
    |--------------------------------------------------------------------------
    | Middleware Aliases (optional — future use)
    |--------------------------------------------------------------------------
    |
    | For using short names in route definitions.
    | Currently Route::runMiddleware() does StudlyCase conversion,
    | this alias table can be used if custom mapping is needed later.
    |
    | Route tanımlarında kısa isim kullanmak için.
    | Şu an Route::runMiddleware() StudlyCase dönüşümü yapıyor,
    | bu alias tablosu ileride özel mapping gerekirse kullanılabilir.
    |
    */
    'aliases' => [
        'throttle' => 'Throttle',
        'cors' => 'Cors',
    ],
];
