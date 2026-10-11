<?php

declare(strict_types=1);

namespace App\Providers;

use Core\Route;
use Core\ServiceProvider;

/**
 * RouteServiceProvider — Service provider that loads route files.
 * RouteServiceProvider — Route dosyalarını yükleyen servis sağlayıcı.
 *
 * Hybrid architecture support:
 * Hibrit mimari desteği:
 *   - if routes/web.php exists → loaded with 'web' middleware group // routes/web.php varsa → 'web' middleware grubuyla yüklenir
 *   - if routes/api.php exists → loaded with 'api' middleware group + config prefix // routes/api.php varsa → 'api' middleware grubuyla + config prefix ile yüklenir
 *   - if both exist   → Hybrid mode (both active) // İkisi birden varsa   → Hibrit mod (her ikisi de aktif)
 *   - if neither exist         → Empty app (error page) // Hiçbiri yoksa         → Boş uygulama (hata sayfası)
 *
 * API prefix is determined by 'api_prefix' in config/middleware.php.
 * API prefix config/middleware.php içindeki 'api_prefix' ile belirlenir.
 * Default: '/api' // Varsayılan: '/api'
 *
 * Usage (public/index.php): // Kullanım (public/index.php):
 *   $app->register(\App\Providers\RouteServiceProvider::class);
 */
class RouteServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Route bindings are done during the boot() phase
        // Route binding'leri boot() aşamasında yapılır
    }

    public function boot(): void
    {
        $this->loadWebRoutes();
        $this->loadApiRoutes();
    }

    /**
     * Load web routes (session based, CSRF protected).
     * Web route'larını yükle (session tabanlı, CSRF korumalı).
     */
    protected function loadWebRoutes(): void
    {
        $webRoutes = BASE_PATH.'/routes/web.php';

        if (! file_exists($webRoutes)) {
            return;
        }

        Route::setGroup('web');
        require $webRoutes;
    }

    /**
     * Load API routes (stateless, token based).
     * API route'larını yükle (stateless, token tabanlı).
     * Loaded under the api_prefix in config.
     * Config'deki api_prefix altında yüklenir.
     */
    protected function loadApiRoutes(): void
    {
        $apiRoutes = BASE_PATH.'/routes/api.php';

        if (! file_exists($apiRoutes)) {
            return;
        }

        $apiPrefix = (string) config('middleware.api_prefix', '/api');

        Route::setGroup('api');
        Route::prefix($apiPrefix)->group(function () use ($apiRoutes) {
            require $apiRoutes;
        });
        Route::setGroup('web'); // Return to default // Varsayılana geri dön
    }
}
