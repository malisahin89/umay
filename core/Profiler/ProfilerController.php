<?php

declare(strict_types=1);

namespace Core\Profiler;

use Core\Request;

/**
 * ProfilerController — Profiler data endpoint.
 * ProfilerController — Profiler veri endpoint'i.
 *
 * /_profiler/{token}  → Single profile data (JSON) // Tek profil verisi (JSON)
 * /_profiler/latest   → Latest profile data (JSON) // Son profil verisi (JSON)
 * /_profiler          → Token list (JSON) // Token listesi (JSON)
 *
 * Security: IP whitelist + environment check.
 * Güvenlik: IP whitelist + environment kontrolü.
 */
class ProfilerController
{
    /**
     * Returns profile data for a specific token.
     * Belirli bir token'a ait profil verisini döndürür.
     */
    public function show(Request $request, string $token): void
    {
        $this->guardAccess();

        $storage = Profiler::getStorage();
        if (! $storage) {
            $this->jsonResponse(['error' => 'Profiler disabled. // Profiler devre dışı.'], 503);

            return;
        }

        if ($token === 'latest') {
            $token = $storage->getLatestToken();
            if (! $token) {
                $this->jsonResponse(['error' => 'Profile not found. // Profil bulunamadı.'], 404);

                return;
            }
        }

        $data = $storage->load($token);
        if (! $data) {
            $this->jsonResponse(['error' => 'Profile not found. // Profil bulunamadı.'], 404);

            return;
        }

        $this->jsonResponse($data);
    }

    /**
     * Returns the token list.
     * Token listesini döndürür.
     */
    public function index(Request $request): void
    {
        $this->guardAccess();

        $storage = Profiler::getStorage();
        if (! $storage) {
            $this->jsonResponse(['error' => 'Profiler disabled. // Profiler devre dışı.'], 503);

            return;
        }

        $limit = min((int) ($request->get('limit', 50)), 200);
        $tokens = $storage->listTokens($limit);
        $this->jsonResponse(['tokens' => $tokens]);
    }

    // ── Guards ──────────────────────────────────────────────────────────────

    private function guardAccess(): void
    {
        // Environment check — fail-safe: unset APP_ENV is treated as production
        // Environment kontrolü — fail-safe: APP_ENV tanımsızsa production sayılır
        $env = $_ENV['APP_ENV'] ?? 'production';
        if ($env === 'production' && ! filter_var($_ENV['PROFILER_ENABLED'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            $this->jsonResponse(['error' => 'Profiler disabled in production. // Profiler üretim ortamında devre dışı.'], 403);
            exit;
        }

        // IP whitelist check
        // IP whitelist kontrolü
        // getRealIP() — trusted-proxy aware: reverse proxy (nginx→php-fpm) arkasında
        // REMOTE_ADDR=127.0.0.1 olur ve whitelist'i bypass ederdi; gerçek client IP'sini çözer.
        $config = $this->getConfig();
        $whitelist = $config['ip_whitelist'] ?? [];
        $clientIp = getRealIP();

        // Fail-closed: outside local, an EMPTY whitelist must NOT mean "allow everyone".
        // It previously did (the check was skipped when the list was empty), which would
        // expose every profile to any IP on a staging/non-local box.
        // Fail-closed: local dışında BOŞ whitelist "herkese izin ver" anlamına GELMEMELİ.
        // Önceden öyleydi (liste boşken kontrol atlanıyordu); bu, local olmayan/staging
        // bir makinede tüm profilleri her IP'ye açardı.
        if ($whitelist === []) {
            if ($env !== 'local') {
                $this->jsonResponse(['error' => 'Access denied. // Erişim reddedildi.'], 403);
                exit;
            }
        } elseif (! in_array($clientIp, $whitelist, true)) {
            $this->jsonResponse(['error' => 'Access denied. // Erişim reddedildi.'], 403);
            exit;
        }
    }

    private function jsonResponse(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('X-Profiler: Umay');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    private function getConfig(): array
    {
        $path = (defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__, 2)).'/config/profiler.php';

        return file_exists($path) ? (require $path) : [];
    }
}
