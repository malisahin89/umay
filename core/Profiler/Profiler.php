<?php

declare(strict_types=1);

namespace Core\Profiler;

use Core\Profiler\Contracts\DataCollectorInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Profiler — Main orchestrator class.
 * Profiler — Ana orkestratör sınıfı.
 *
 * Monitors request lifecycle, coordinates collectors, writes data to storage.
 * Request lifecycle'ını izler, collector'ları koordine eder, veriyi storage'a yazar.
 * Maintains backward compatibility with existing DebugBar static API.
 * Mevcut DebugBar static API ile geriye dönük uyumlu çalışır.
 *
 * Usage / Kullanım:
 *   Profiler::init();                         // At start of request // Request başında
 *   Profiler::addCollector(new QueryCollector());
 *   ...
 *   Profiler::finish();                       // At end of request (save + cleanup) // Request sonunda (save + cleanup)
 *   echo Profiler::renderToolbar();           // HTML inject
 */
class Profiler
{
    private static bool $enabled = false;

    private static bool $finished = false;   // idempotent finish() check // idempotent finish() kontrolü

    private static float $startTime = 0.0;

    private static string $token = '';

    private static array $collectors = [];

    private static ?ProfilerStorage $storage = null;

    // ── Collector cache (for static add methods) ────────────────────────────
    // ── Collector cache (static add metodları için) ─────────────────────────

    private static array $queryData = [];

    private static array $logData = [];

    private static array $viewData = [];

    private static array $eventData = [];

    private static array $cacheData = [];

    private static array $mailData = [];

    private static array $routeInfo = [];

    private static array $exceptions = [];

    private static array $measures = [];

    private static array $openMeasures = [];

    private static array $middlewareTiming = [];

    private static array $requestData = [];

    // ── Lifecycle ───────────────────────────────────────────────────────────

    /**
     * Start the profiler.
     * Called at start of request — generates token, saves start time.
     *
     * Profiler'ı başlat.
     * Request başında çağrılır — token üretir, start time kaydeder.
     */
    public static function init(): void
    {
        $config = self::getConfig();
        if (! $config['enabled']) {
            return;
        }

        self::$enabled = true;
        self::$finished = false;
        self::$startTime = $_SERVER['REQUEST_TIME_FLOAT'] ?? microtime(true);
        self::$token = self::generateToken();

        self::$storage = new ProfilerStorage(
            $config['storage_path'],
            $config['ttl'],
            $config['max_entries']
        );

        // Capture request data immediately
        // Request verisini hemen yakala
        self::captureRequest();

        self::startMeasure('Application', self::$startTime);
    }

    /**
     * Is the profiler active?
     * Profiler aktif mi?
     */
    public static function isEnabled(): bool
    {
        return self::$enabled;
    }

    /**
     * Profiler token.
     * Profiler token'ı.
     */
    public static function getToken(): string
    {
        return self::$token;
    }

    /**
     * Called at the end of the request — collects all collector data, writes to storage.
     * Idempotent: Even if called multiple times, runs only the first time.
     *
     * Request sonunda çağrılır — tüm collector verisini toplar, storage'a yazar.
     * İdempotent: Birden fazla çağrılsa bile sadece ilk seferde çalışır.
     */
    public static function finish(): void
    {
        if (! self::$enabled || self::$finished) {
            return;
        }

        self::$finished = true;

        // Close open measures
        // Açık kalan measure'ları kapat
        foreach (array_keys(self::$openMeasures) as $name) {
            self::stopMeasure($name);
        }

        $data = self::collectAll();
        self::$storage->save(self::$token, $data);

        // Send X-Profiler-Token header (for API responses)
        // X-Profiler-Token header'ı gönder (API response'lar için)
        if (! headers_sent()) {
            header('X-Profiler-Token: '.self::$token);
        }

        // Cleanup (every Nth request — for performance)
        // Cleanup (her Nth request'te — performans için)
        if (mt_rand(1, 10) === 1) {
            self::$storage->cleanup();
        }
    }

    /**
     * Collects data from all registered collectors.
     * Register edilmiş tüm collector'ların verisini toplar.
     */
    public static function collectAll(): array
    {
        $data = [];

        // Built-in static data
        $totalMs = round((microtime(true) - self::$startTime) * 1000, 2);
        $memory = round(memory_get_peak_usage(true) / 1024 / 1024, 2);

        $data['_summary'] = [
            'token' => self::$token,
            'total_ms' => $totalMs,
            'memory_mb' => $memory,
            'php' => PHP_VERSION,
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'CLI',
            'uri' => parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/',
            'status' => http_response_code() ?: 200,
            'timestamp' => date('Y-m-d H:i:s'),
        ];

        // Built-in data fields
        // Built-in veri alanları
        $data['timeline'] = self::$measures;
        $data['route'] = self::$routeInfo ?: self::guessRoute();
        $data['queries'] = self::$queryData;
        $data['views'] = self::$viewData;
        $data['events'] = self::$eventData;
        $data['cache'] = self::$cacheData;
        $data['mails'] = self::$mailData;
        $data['logs'] = self::$logData;
        $data['exceptions'] = self::$exceptions;
        $data['auth'] = self::getAuthUser();
        $data['session'] = self::getSafeSession();
        $data['php_info'] = self::collectPhpInfo();
        $data['request'] = self::$requestData;
        $data['middleware_timing'] = self::$middlewareTiming;

        // Model statistics
        // Model istatistikleri
        $modelStats = [];
        foreach (self::$queryData as $q) {
            $m = $q['model'] ?? 'Raw';
            $modelStats[$m] = ($modelStats[$m] ?? 0) + 1;
        }
        arsort($modelStats);
        $data['model_stats'] = $modelStats;

        // N+1 Query Detection
        $data['n_plus_one'] = self::detectNPlusOne();

        // Custom collectors
        // Custom collector'lar
        foreach (self::$collectors as $collector) {
            try {
                $data[$collector->getName()] = $collector->collect();
            } catch (\Throwable $e) {
                $data[$collector->getName()] = ['__error' => $e->getMessage()];
            }
        }

        return $data;
    }

    // ── Collector Registration ──────────────────────────────────────────────

    /**
     * Register custom collector.
     * Özel collector kaydet.
     */
    public static function addCollector(DataCollectorInterface $collector): void
    {
        self::$collectors[$collector->getName()] = $collector;
    }

    // ── Timeline / Measure ──────────────────────────────────────────────────

    public static function startMeasure(string $name, ?float $start = null): void
    {
        if (! self::$enabled) {
            return;
        }
        self::$openMeasures[$name] = $start ?? microtime(true);
    }

    public static function stopMeasure(string $name): void
    {
        if (! self::$enabled || ! isset(self::$openMeasures[$name])) {
            return;
        }
        $start = self::$openMeasures[$name];
        $end = microtime(true);
        self::$measures[] = [
            'label' => $name,
            'start' => $start - self::$startTime,
            'end' => $end - self::$startTime,
            'ms' => round(($end - $start) * 1000, 2),
        ];
        unset(self::$openMeasures[$name]);
    }

    // ── Static Data Add (geriye dönük uyumlu API) ───────────────────────────

    public static function addQuery(array $q): void
    {
        if (! self::$enabled) {
            return;
        }
        self::$queryData[] = $q;
    }

    public static function addLog(string $level, string $message, array $context = []): void
    {
        if (! self::$enabled) {
            return;
        }
        self::$logData[] = [
            'level' => strtoupper($level),
            'message' => $message,
            'context' => $context,
            'time' => date('H:i:s'),
        ];
    }

    public static function addView(string $template, array $data = []): void
    {
        if (! self::$enabled) {
            return;
        }
        $safe = [];
        foreach ($data as $k => $v) {
            if (in_array($k, ['errors', 'success', 'error', 'csrf_token'])) {
                continue;
            }
            $safe[$k] = self::summarize($v);
        }
        self::$viewData[] = [
            'template' => $template,
            'data' => $safe,
            'time' => date('H:i:s'),
        ];
    }

    public static function addEvent(string $eventClass, mixed $payload = null): void
    {
        if (! self::$enabled) {
            return;
        }
        self::$eventData[] = [
            'class' => $eventClass,
            'payload' => self::summarize($payload),
            'time' => date('H:i:s'),
        ];
    }

    public static function addCacheOp(string $type, string $key, bool $hit = false): void
    {
        if (! self::$enabled) {
            return;
        }
        self::$cacheData[] = [
            'type' => $type,
            'key' => $key,
            'hit' => $hit,
            'time' => date('H:i:s'),
        ];
    }

    public static function addMail(array $mail): void
    {
        if (! self::$enabled) {
            return;
        }
        self::$mailData[] = $mail;
    }

    public static function setRoute(array $info): void
    {
        if (! self::$enabled) {
            return;
        }
        self::$routeInfo = $info;
    }

    public static function addException(\Throwable $e): void
    {
        if (! self::$enabled) {
            return;
        }
        self::$exceptions[] = [
            'class' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ];
    }

    /**
     * Save middleware execution time.
     * Middleware çalışma süresini kaydet.
     */
    public static function addMiddlewareTiming(string $name, float $ms): void
    {
        if (! self::$enabled) {
            return;
        }
        self::$middlewareTiming[] = [
            'name' => $name,
            'ms' => round($ms, 2),
        ];
    }

    // ── Request Data Capture ────────────────────────────────────────────────

    /**
     * Captures incoming request info — headers, query, POST, cookies, etc.
     * Gelen request bilgilerini yakalar — headers, query, POST, cookies vb.
     */
    private static function captureRequest(): void
    {
        // Request Headers — mask secrets (Authorization Bearer tokens, raw Cookie header)
        // Request Headers — sırları maskele (Authorization Bearer token'ları, ham Cookie header'ı)
        $headers = [];
        foreach ($_SERVER as $k => $v) {
            if (str_starts_with($k, 'HTTP_')) {
                $name = str_replace('_', '-', ucwords(strtolower(substr($k, 5)), '_'));
                if (strcasecmp($name, 'Authorization') === 0 || strcasecmp($name, 'Cookie') === 0) {
                    $v = '********';
                }
                $headers[$name] = $v;
            }
        }
        if (isset($_SERVER['CONTENT_TYPE'])) {
            $headers['Content-Type'] = $_SERVER['CONTENT_TYPE'];
        }
        if (isset($_SERVER['CONTENT_LENGTH'])) {
            $headers['Content-Length'] = $_SERVER['CONTENT_LENGTH'];
        }

        // Response Headers (not yet sent — will be updated at finish() time)
        // Response Headers (henüz gönderilmemiş — finish() zamanında güncellenecek)
        $responseHeaders = [];
        foreach (headers_list() as $header) {
            [$key, $val] = array_pad(explode(':', $header, 2), 2, '');
            $responseHeaders[trim($key)] = trim($val);
        }

        // Query String parameters (mask credential-like keys, e.g. ?token=...)
        // Query String parametreleri (kimlik-bilgisi benzeri anahtarları maskele)
        $queryParams = self::maskSensitive($_GET);

        // POST data (mask sensitive data by key pattern, not a fixed allow-list)
        // POST verisi (sabit liste yerine anahtar pattern'iyle hassas veriyi maskele)
        $postData = self::maskSensitive($_POST);

        // JSON body (for Content-Type: application/json)
        // JSON body (Content-Type: application/json için)
        // Bounded read — same 8 MB ceiling as Request::__construct, so an oversized
        // body can't exhaust memory just because the profiler is enabled; one extra
        // byte detects (and drops) an over-limit body instead of truncating it.
        // Sınırlı okuma — Request::__construct ile aynı 8 MB tavan; profiler açık diye
        // aşırı büyük gövde belleği tüketemesin. Bir fazla bayt, limiti aşan gövdeyi
        // kesip ayrıştırmak yerine tespit edip atar.
        $jsonBody = null;
        $maxBytes = 8 * 1024 * 1024;
        $rawBody = file_get_contents('php://input', false, null, 0, $maxBytes + 1);
        if ($rawBody !== false && $rawBody !== '' && strlen($rawBody) <= $maxBytes
            && str_contains(($_SERVER['CONTENT_TYPE'] ?? ''), 'json')) {
            $decoded = json_decode($rawBody, true);
            if (is_array($decoded)) {
                $jsonBody = self::maskSensitive($decoded);
            }
        }

        // Cookies (mask sensitive ones)
        // Cookies (hassas olanları maskele)
        $cookies = $_COOKIE;
        foreach ($cookies as $name => &$value) {
            if (str_contains($name, 'session') || str_contains($name, 'token') || str_contains($name, 'remember')) {
                $value = mb_substr($value, 0, 8).'…';
            }
        }
        unset($value);

        // The raw URI keeps its query string — mask credential-like params there too,
        // otherwise ?api_key=… lands unmasked in profiler storage while query_params
        // above is dutifully masked.
        // Ham URI query string'ini taşır — kimlik-bilgisi benzeri parametreleri orada
        // da maskele; aksi halde query_params maskelenirken ?api_key=… profiler
        // deposuna maskesiz yazılır.
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $maskedUri = self::maskUriQuery(is_string($requestUri) ? $requestUri : '/');

        self::$requestData = [
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'GET',
            'uri' => $maskedUri,
            'url' => ($_SERVER['REQUEST_SCHEME'] ?? 'http').'://'.($_SERVER['HTTP_HOST'] ?? 'localhost').$maskedUri,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'content_type' => $_SERVER['CONTENT_TYPE'] ?? '-',
            'request_headers' => $headers,
            'response_headers' => $responseHeaders,
            'query_params' => $queryParams,
            'post_data' => $postData,
            'json_body' => $jsonBody,
            'cookies' => $cookies,
            'server' => [
                'SERVER_SOFTWARE' => $_SERVER['SERVER_SOFTWARE'] ?? '-',
                'SERVER_PROTOCOL' => $_SERVER['SERVER_PROTOCOL'] ?? '-',
                'DOCUMENT_ROOT' => $_SERVER['DOCUMENT_ROOT'] ?? '-',
                'REMOTE_PORT' => $_SERVER['REMOTE_PORT'] ?? '-',
                'SERVER_PORT' => $_SERVER['SERVER_PORT'] ?? '-',
            ],
        ];
    }

    /**
     * Update response headers (before finish())
     * Response headers'ı güncelle (finish() öncesinde)
     */
    public static function captureResponseHeaders(): void
    {
        if (! self::$enabled) {
            return;
        }
        $responseHeaders = [];
        foreach (headers_list() as $header) {
            [$key, $val] = array_pad(explode(':', $header, 2), 2, '');
            $responseHeaders[trim($key)] = trim($val);
        }
        self::$requestData['response_headers'] = $responseHeaders;
        self::$requestData['response_status'] = http_response_code() ?: 200;
    }

    // ── N+1 Query Detection ─────────────────────────────────────────────────

    /**
     * N+1 query detection — warning if the same SQL pattern is repeated 3+ times.
     * Comparison is made by removing bindings.
     *
     * N+1 sorgu tespiti — aynı SQL kalıbı 3+ kez tekrarlanmışsa uyarı.
     * Binding'ler kaldırılarak pattern karşılaştırması yapılır.
     */
    private static function detectNPlusOne(): array
    {
        if (count(self::$queryData) < 3) {
            return [];
        }

        $patterns = [];
        foreach (self::$queryData as $i => $q) {
            $sql = $q['sql'] ?? '';
            // Replace binding values with placeholders
            // Binding değerlerini placeholder ile değiştir
            $pattern = preg_replace([
                "/= ?\?/",
                "/'[^']*'/",
                "/\b\d+\b/",
            ], ['= ?', '?', '?'], $sql);
            $pattern = preg_replace('/\s+/', ' ', trim($pattern));

            if (! isset($patterns[$pattern])) {
                $patterns[$pattern] = ['count' => 0, 'indices' => [], 'sql' => $sql, 'model' => $q['model'] ?? 'Raw'];
            }
            $patterns[$pattern]['count']++;
            $patterns[$pattern]['indices'][] = $i;
        }

        $warnings = [];
        foreach ($patterns as $pattern => $info) {
            if ($info['count'] >= 3) {
                $warnings[] = [
                    'pattern' => $pattern,
                    'count' => $info['count'],
                    'model' => $info['model'],
                    'sample' => $info['sql'],
                    'indices' => $info['indices'],
                ];
            }
        }

        return $warnings;
    }

    // ── Render ──────────────────────────────────────────────────────────────

    /**
     * Returns Toolbar HTML.
     * This method is injected before </body> by View::render().
     *
     * Toolbar HTML'ini döndürür.
     * Bu metot View::render() tarafından </body> öncesine enjekte edilir.
     */
    public static function renderToolbar(): string
    {
        if (! self::$enabled) {
            return '';
        }

        // Capture response headers
        // Response headers'ı yakala
        self::captureResponseHeaders();

        // Close open measures
        // Açık kalan measure'ları kapat
        foreach (array_keys(self::$openMeasures) as $name) {
            self::stopMeasure($name);
        }

        $token = self::$token;
        $totalMs = round((microtime(true) - self::$startTime) * 1000, 1);
        $memory = round(memory_get_peak_usage(true) / 1024 / 1024, 2);
        $qCount = count(self::$queryData);
        $qTime = round(array_sum(array_column(self::$queryData, 'time')), 2);
        $lCount = count(self::$logData);
        $vCount = count(self::$viewData);
        $evCount = count(self::$eventData);
        $caches = self::$cacheData;
        $cHits = count(array_filter($caches, fn ($c) => $c['hit']));
        $mCount = count(self::$mailData);
        $eCount = count(self::$exceptions);
        $route = self::$routeInfo ?: self::guessRoute();
        $authUser = self::getAuthUser();
        $session = self::getSafeSession();
        $n1Count = count(self::detectNPlusOne());
        $mwCount = count(self::$middlewareTiming);

        $statusCode = http_response_code() ?: 200;

        // Color codes
        // Renk kodları
        $timeColor = $totalMs > 500 ? '#ef4444' : ($totalMs > 200 ? '#f59e0b' : '#10b981');
        $memColor = $memory > 32 ? '#ef4444' : ($memory > 16 ? '#f59e0b' : '#10b981');
        $qColor = $qCount > 20 ? '#ef4444' : ($qCount > 10 ? '#f59e0b' : '#10b981');
        $stColor = $statusCode >= 500 ? '#ef4444' : ($statusCode >= 400 ? '#f59e0b' : '#10b981');
        $hasErr = ! empty(array_filter(self::$logData, fn ($l) => $l['level'] === 'ERROR'));
        $hasWarn = ! empty(array_filter(self::$logData, fn ($l) => $l['level'] === 'WARNING'));
        $lColor = $hasErr ? '#ef4444' : ($hasWarn ? '#f59e0b' : '#64748b');
        $role = $authUser['role'] ?? 'guest';

        // Profiler endpoint URL
        $profilerUrl = '/_profiler/'.$token;

        ob_start();
        include __DIR__.'/Views/toolbar.php';

        return ob_get_clean();
    }

    /**
     * Returns Storage instance (accessed from controller).
     * Storage instance'ını döndürür (controller'dan erişim).
     */
    public static function getStorage(): ?ProfilerStorage
    {
        if (! self::$storage && self::$enabled) {
            $config = self::getConfig();
            self::$storage = new ProfilerStorage(
                $config['storage_path'],
                $config['ttl'],
                $config['max_entries']
            );
        }

        return self::$storage;
    }

    // ── Caller Detection ────────────────────────────────────────────────────

    /**
     * Scans all frames in debug_backtrace to find the model and the first app/ file.
     * debug_backtrace içinde tüm frame'leri tarayarak modeli ve ilk app/ dosyasını bulur.
     */
    public static function findCaller(): array
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 25);
        $caller = '';
        $model = '';
        foreach ($trace as $frame) {
            $class = $frame['class'] ?? '';
            // Detect any Eloquent model (namespace-agnostic — no App\ assumption)
            // Herhangi bir Eloquent modelini tespit et (namespace'ten bağımsız — App\ varsayımı yok)
            if (! $model && $class && is_subclass_of($class, Model::class)) {
                $model = basename(str_replace('\\', '/', $class));
            }

            $file = str_replace('\\', '/', $frame['file'] ?? '');
            // Detect caller (first app/ file outside vendor)
            // Caller tespit et (vendor dışı ilk app/ dosyası)
            if (! $caller && ! str_contains($file, '/vendor/') && str_contains($file, '/app/')) {
                $rel = ltrim(str_replace(str_replace('\\', '/', BASE_PATH), '', $file), '/');
                $caller = $rel.':'.($frame['line'] ?? 0);
            }

            if ($caller && $model) {
                break;
            }
        }

        return ['caller' => $caller, 'model' => $model];
    }

    // ── Private Helpers ─────────────────────────────────────────────────────

    private static function generateToken(): string
    {
        return substr(bin2hex(random_bytes(16)), 0, 32);
    }

    private static function getConfig(): array
    {
        static $config = null;
        if ($config === null) {
            $path = (defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__, 2)).'/config/profiler.php';
            $config = file_exists($path) ? (require $path) : [];
            $config = array_merge([
                'enabled' => false,
                'storage_path' => sys_get_temp_dir().'/umay-profiler',
                'ttl' => 7200,
                'max_entries' => 200,
                'ip_whitelist' => ['127.0.0.1', '::1'],
            ], $config);
        }

        return $config;
    }

    private static function guessRoute(): array
    {
        return [
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'GET',
            'uri' => parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/',
            'name' => '-',
            'controller' => '-',
            'action' => '-',
            'middleware' => [],
        ];
    }

    private static function getSafeSession(): array
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return [];
        }
        $data = $_SESSION;
        foreach (['csrf_token', 'csp_nonce', '_old', 'remember_token'] as $k) {
            unset($data[$k]);
        }

        return $data;
    }

    private static function getAuthUser(): ?array
    {
        if (empty($_SESSION['user_id'])) {
            return null;
        }
        try {
            // Resolve the user model from config — the profiler must not depend
            // on a concrete App\Models\User. // Kullanıcı modelini config'den çöz —
            // profiler somut App\Models\User'a bağımlı olmamalı.
            $name = (string) config('auth.default', 'eloquent');
            $model = config("auth.providers.{$name}.model");
            if (! is_string($model) || ! class_exists($model)) {
                return null;
            }

            $user = $model::find((int) $_SESSION['user_id']);
            if (! $user) {
                return null;
            }

            return [
                'id' => $user->id,
                'name' => $user->name ?? ('User #'.$user->id),
                'email' => $user->email ?? '',
                'role' => $user->role ?? null, // optional — null if the model has no role // opsiyonel — modelde rol yoksa null
            ];
        } catch (\Throwable) {
            return [
                'id' => $_SESSION['user_id'],
                'name' => 'User #'.$_SESSION['user_id'],
                'email' => '',
                'role' => null,
            ];
        }
    }

    private static function collectPhpInfo(): array
    {
        $opcache = function_exists('opcache_get_status') ? @opcache_get_status(false) : false;

        return [
            'version' => PHP_VERSION,
            'environment' => $_ENV['APP_ENV'] ?? 'local',
            'peak_memory_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
            'timezone' => date_default_timezone_get(),
            'opcache_enabled' => $opcache && ($opcache['opcache_enabled'] ?? false),
            'https' => isset($_SERVER['HTTPS']),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? '-',
            'session_driver' => session_module_name(),
            'extensions' => array_values(array_filter(get_loaded_extensions(), fn ($e) => in_array($e, ['pdo', 'pdo_mysql', 'mbstring', 'fileinfo', 'gd', 'json', 'openssl', 'curl', 'zip', 'redis'])
            )),
        ];
    }

    /**
     * Mask credential-like values by key pattern (top level).
     * A fixed allow-list missed fields like pin/cvv/card/api_key/otp; matching the
     * key name is resilient to whatever a form happens to call its secret fields.
     *
     * Kimlik-bilgisi benzeri değerleri anahtar pattern'iyle maskele (üst seviye).
     * Sabit liste pin/cvv/card/api_key/otp gibi alanları kaçırıyordu; anahtar adını
     * eşlemek, formun gizli alanlarını nasıl adlandırdığından bağımsız çalışır.
     *
     * @param  array<array-key, mixed>  $data
     * @return array<array-key, mixed>
     */
    private static function maskSensitive(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_string($key) && preg_match('/pass|secret|token|otp|cvv|card|pin|api[_-]?key|authorization|credential/i', $key)) {
                $data[$key] = '********';
            }
        }

        return $data;
    }

    /**
     * Mask credential-like query parameters inside a URI (?api_key=… → ********).
     * Used for the raw URI strings persisted to profiler storage (requestData,
     * ProfilerStorage __meta) — query_params is masked separately.
     *
     * URI içindeki kimlik-bilgisi benzeri query parametrelerini maskele
     * (?api_key=… → ********). Profiler deposuna yazılan ham URI string'leri için
     * kullanılır (requestData, ProfilerStorage __meta) — query_params ayrıca maskelenir.
     */
    public static function maskUriQuery(string $uri): string
    {
        $qPos = strpos($uri, '?');
        if ($qPos === false) {
            return $uri;
        }

        parse_str(substr($uri, $qPos + 1), $params);
        $query = http_build_query(self::maskSensitive($params));

        return substr($uri, 0, $qPos).($query !== '' ? '?'.$query : '');
    }

    /** Convert value to summary format — for view data and event payload // Değeri özet biçimine çevirir — view data ve event payload için */
    public static function summarize(mixed $val): array
    {
        if (is_null($val)) {
            return ['type' => 'null',    'preview' => 'null'];
        }
        if (is_bool($val)) {
            return ['type' => 'bool',    'preview' => $val ? 'true' : 'false'];
        }
        if (is_int($val)) {
            return ['type' => 'int',     'preview' => (string) $val];
        }
        if (is_float($val)) {
            return ['type' => 'float',   'preview' => (string) $val];
        }
        if (is_string($val)) {
            return ['type' => 'string('.strlen($val).')', 'preview' => mb_substr($val, 0, 80).(mb_strlen($val) > 80 ? '…' : '')];
        }
        if (is_array($val)) {
            return ['type' => 'array('.count($val).')', 'preview' => json_encode(array_slice($val, 0, 3, true), JSON_UNESCAPED_UNICODE)];
        }
        if (is_object($val)) {
            return ['type' => get_class($val), 'preview' => method_exists($val, 'toArray') ? json_encode(array_slice($val->toArray(), 0, 3), JSON_UNESCAPED_UNICODE) : get_class($val)];
        }

        return ['type' => gettype($val), 'preview' => ''];
    }

    /** Insert binding values into SQL // Binding değerlerini SQL içine yerleştirir */
    public static function interpolateSql(string $sql, array $bindings): string
    {
        foreach ($bindings as $b) {
            $quoted = is_null($b) ? 'NULL' : (is_numeric($b) ? $b : "'".addslashes((string) $b)."'");
            $pos = strpos($sql, '?');
            if ($pos !== false) {
                $sql = substr_replace($sql, (string) $quoted, $pos, 1);
            }
        }

        return $sql;
    }

    /** Colorize SQL keywords // SQL anahtar kelimelerini renklendirir */
    public static function highlightSql(string $sql): string
    {
        $sql = htmlspecialchars($sql);
        $keywords = ['SELECT', 'FROM', 'WHERE', 'JOIN', 'LEFT JOIN', 'RIGHT JOIN', 'INNER JOIN', 'ON', 'AND', 'OR', 'NOT', 'IN', 'IS NULL', 'IS NOT NULL', 'AS', 'ORDER BY', 'GROUP BY', 'HAVING', 'LIMIT', 'OFFSET', 'INSERT INTO', 'VALUES', 'UPDATE', 'SET', 'DELETE', 'CREATE', 'ALTER', 'DROP', 'DISTINCT', 'COUNT', 'SUM', 'AVG', 'MAX', 'MIN', 'UNION', 'EXISTS', 'BETWEEN', 'LIKE', 'NULL'];
        foreach ($keywords as $kw) {
            $sql = preg_replace('/\b('.preg_quote($kw, '/').')\b/i', '<b>$1</b>', $sql);
        }
        $sql = preg_replace("/'[^']*'/", '<i>$0</i>', $sql);

        return $sql;
    }

    /**
     * Reset profiler (for test and CLI).
     * Profiler'ı sıfırlar (test ve CLI için).
     */
    public static function reset(): void
    {
        self::$enabled = false;
        self::$finished = false;
        self::$startTime = 0.0;
        self::$token = '';
        self::$collectors = [];
        self::$storage = null;
        self::$queryData = [];
        self::$logData = [];
        self::$viewData = [];
        self::$eventData = [];
        self::$cacheData = [];
        self::$mailData = [];
        self::$routeInfo = [];
        self::$exceptions = [];
        self::$measures = [];
        self::$openMeasures = [];
        self::$middlewareTiming = [];
        self::$requestData = [];
    }
}
