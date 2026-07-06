<?php

declare(strict_types=1);

// Security: Define base path
// Güvenlik: Temel dizini tanımla
define('BASE_PATH', realpath(__DIR__.'/../'));

require_once BASE_PATH.'/vendor/autoload.php';
require_once BASE_PATH.'/config/database.php';
require_once BASE_PATH.'/core/helpers.php';

use App\Providers\EventServiceProvider;
use App\Providers\RouteServiceProvider;
use Core\Application;
use Core\Profiler\Profiler;
use Core\Providers\FacadeServiceProvider;
use Core\Route;

// Profiler (DebugBar compatible — also works via facade)
// Profiler (DebugBar uyumlu — facade üzerinden de çalışır)
// init() self-gates on config('profiler.enabled') (PROFILER_ENABLED ?? APP_DEBUG) and
// returns immediately when disabled — so the profiler can be enabled INDEPENDENTLY of
// APP_DEBUG (e.g. PROFILER_ENABLED=true on a staging box with APP_DEBUG=false).
// init() config('profiler.enabled')'a (PROFILER_ENABLED ?? APP_DEBUG) göre kendini geçitler
// ve kapalıysa hemen döner — böylece profiler APP_DEBUG'dan BAĞIMSIZ etkinleştirilebilir
// (örn. staging'de APP_DEBUG=false iken PROFILER_ENABLED=true).
Profiler::init();

// Global profiling flag — avoids repeated class_exists() + isEnabled() checks
// Global profiling flag — tekrarlı class_exists() + isEnabled() kontrollerini önler
define('UMAY_PROFILING', Profiler::isEnabled());

// ── API / Web mode detection ──────────────────────────────────────────────────
// API requests are stateless — no session is started, CSRF checks are disabled.
// API / Web mod tespiti
// API istekleri stateless — session başlatılmaz, CSRF kontrolü yapılmaz.
// parse_url bozuk request-target'ta false/null dönebilir — str_starts_with'e
// string dışı değer strict_types altında TypeError (ham 500) üretirdi.
// parse_url can return false/null on a malformed request-target — a non-string
// into str_starts_with would TypeError (raw 500) under strict_types.
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$apiPrefix = config('middleware.api_prefix', '/api');
$isApiRequest = str_starts_with($requestPath, $apiPrefix.'/') || $requestPath === $apiPrefix;

// Secure session settings (only for web requests)
// Güvenli session ayarları (sadece web isteklerinde)
if (! $isApiRequest) {
    ini_set('session.cookie_httponly', '1');
    // config('session.secure') HTTPS'i otomatik algılar (config/session.php)
    ini_set('session.cookie_secure', config('session.secure') ? '1' : '0');
    ini_set('session.use_strict_mode', '1');
    ini_set('session.cookie_samesite', config('session.same_site', 'Strict'));
    session_name(config('session.cookie', 'umay_session'));
}

// Set application timezone
// Uygulama timezone ayarla
date_default_timezone_set(config('app.timezone', 'Europe/Istanbul'));

// Start session (only for web requests)
// Session başlat (sadece web isteklerinde)
if (! $isApiRequest) {
    session_start();

    // Session idle timeout (from config — minutes → seconds)
    // Session idle timeout (config'den — dakika → saniye)
    $sessionLifetime = config('session.lifetime', 30) * 60;
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $sessionLifetime) {
        // Empty $_SESSION BEFORE destroying. session_destroy() does NOT clear the
        // in-memory superglobal, so without this the old (authenticated) data would
        // survive session_regenerate_id() and the idle timeout would never actually
        // log the user out — it would only rotate the session id.
        // $_SESSION'ı yok etmeden ÖNCE boşalt. session_destroy() bellekteki superglobal'i
        // temizlemez; bu olmadan eski (oturum açmış) veri session_regenerate_id()'den
        // sağ çıkar ve idle timeout kullanıcıyı gerçekte hiç çıkışa zorlamaz — yalnızca
        // session id'sini döndürür.
        $_SESSION = [];
        session_destroy();
        session_start();
        session_regenerate_id(true);
    }
    $_SESSION['last_activity'] = time();
}

// ── Profiler shutdown: save data to storage ─────────────────────────────────
// Profiler shutdown: veriyi storage'a kaydet
register_shutdown_function(function () {
    if (Profiler::isEnabled()) {
        Profiler::finish();
    }
});

// ── Application bootstrap ─────────────────────────────────────────────────────

Profiler::startMeasure('boot');

$app = Application::getInstance();

// Register Request to container (dispatch() will override, this is for fallback)
// Request'i container'a kaydet (dispatch() override edecek, bu fallback içindir)
$app->captureRequest();

// Service Providers
// ServiceProvider'lar
$app->register(FacadeServiceProvider::class);
$app->register(EventServiceProvider::class);
$app->register(RouteServiceProvider::class);

$app->boot();

Profiler::stopMeasure('boot');

// ── Route dispatch ────────────────────────────────────────────────────────────

Profiler::startMeasure('routing');

try {
    // Routes are loaded by RouteServiceProvider (in boot phase)
    // Route'lar RouteServiceProvider tarafından yüklendi (boot aşamasında)
    Profiler::stopMeasure('routing');

    Route::dispatch();

} catch (Throwable $e) {
    Profiler::stopMeasure('routing');
    $app->handleException($e);
}
