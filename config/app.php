<?php

declare(strict_types=1);

use Core\Facades\Auth;
use Core\Facades\Cache;
use Core\Facades\DB;
use Core\Facades\Event;
use Core\Facades\Log;
use Core\Facades\RateLimiter;
use Core\Facades\Validator;

return [
    /*
     * Application Name — accessed in views via config('app.name').
     * Uygulama adı — views içinde config('app.name') ile erişilir.
     */
    'name' => $_ENV['APP_NAME'] ?? 'Umay',

    /*
     * Framework Version.
     * Framework versiyonu.
     */
    'version' => '1.0.0',

    /*
     * Application URL — used for mail, redirects, and asset helpers.
     * Uygulama URL'i — Mail, redirect ve asset helper'ları için.
     */
    'url' => $_ENV['APP_URL'] ?? 'http://localhost',

    /*
     * Controller Namespace — prefix the router prepends to string actions.
     * 'DashboardController@index' → {controller_namespace}DashboardController
     *
     * The core router reads this instead of hard-coding 'App\Controllers\',
     * so you can relocate/rename your controller namespace freely.
     *
     * Controller Namespace — router'ın string action'lara eklediği önek.
     * 'DashboardController@index' → {controller_namespace}DashboardController
     *
     * Çekirdek router 'App\Controllers\' yerine bunu okur; böylece controller
     * namespace'inizi serbestçe taşıyabilir/yeniden adlandırabilirsiniz.
     */
    'controller_namespace' => $_ENV['CONTROLLER_NAMESPACE'] ?? 'App\\Controllers\\',

    /*
     * Environment: local | staging | production
     * Ortam: local | staging | production
     */
    'env' => $_ENV['APP_ENV'] ?? 'local',

    /*
     * Trusted proxies — only requests whose REMOTE_ADDR is in this list are
     * allowed to set the real client IP via X-Forwarded-For. Set this to your
     * actual load balancer / reverse proxy address(es). An attacker who is NOT
     * behind one of these proxies cannot spoof their IP for throttling/logging.
     *
     * Güvenilir proxy'ler — yalnızca REMOTE_ADDR'ı bu listede olan istekler
     * X-Forwarded-For ile gerçek client IP'sini belirleyebilir. Bunu gerçek
     * load balancer / reverse proxy adres(ler)inize ayarlayın. Bu proxy'lerin
     * arkasında OLMAYAN bir saldırgan throttle/log için IP'sini sahteleyemez.
     */
    'trusted_proxies' => array_values(array_filter(array_map(
        'trim',
        explode(',', $_ENV['TRUSTED_PROXIES'] ?? '127.0.0.1,::1')
    ))),

    /*
     * Debug Mode — if true, detailed errors are shown.
     * Set to false in production.
     *
     * Debug modu — true ise hata detayları gösterilir.
     * Production'da false yapın.
     */
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),

    /*
     * Timezone — used for PHP date() and Carbon.
     * Zaman dilimi — PHP date() ve Carbon için.
     */
    'timezone' => $_ENV['APP_TIMEZONE'] ?? 'Europe/Istanbul',

    /*
     * Encryption Key — used for signing tokens.
     * Defined via APP_KEY in .env.
     *
     * Şifreleme anahtarı — token imzalama için.
     * .env'de APP_KEY ile belirlenir.
     */
    'key' => $_ENV['APP_KEY'] ?? '',

    /*
     * Facade Aliases — short global names for Facade classes.
     * Facade Alias'ları — Facade sınıfları için kısa global isimler.
     *
     * These aliases are registered by FacadeServiceProvider during boot.
     * Bu alias'lar FacadeServiceProvider tarafından boot sırasında kaydedilir.
     *
     * Usage: Cache::get('key') instead of \Core\Facades\Cache::get('key')
     * Kullanım: \Core\Facades\Cache::get('key') yerine Cache::get('key')
     *
     * Note: Original \Core\Cache, \Core\Auth etc. still work as before.
     * Not: Orijinal \Core\Cache, \Core\Auth vb. eskisi gibi çalışmaya devam eder.
     */
    'aliases' => [
        'Cache' => Cache::class,
        'Auth' => Auth::class,
        'Log' => Log::class,
        // 'Route'    => \Core\Facades\Route::class,  // Route uses static architecture — alias disabled to avoid conflict
        'DB' => DB::class,
        'Event' => Event::class,
        'Validator' => Validator::class,
        // 'View'     => \Core\Facades\View::class,   // View alias disabled — used directly as Core\View in core
        'RateLimiter' => RateLimiter::class,
    ],
];
