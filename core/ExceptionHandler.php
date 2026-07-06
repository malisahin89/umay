<?php

declare(strict_types=1);

namespace Core;

use Core\Exceptions\HttpException;
use Core\Facades\Log as Logger;
use Core\Facades\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Merkezi exception handler.
 * public/index.php'deki try/catch bloklarını yönetir.
 *
 * Hibrit mimari desteği:
 *   - Web istekleri → HTML hata sayfası (errors/404, errors/500 vb.)
 *   - API istekleri → JSON hata yanıtı (otomatik tespit)
 */
class ExceptionHandler
{
    public function handle(\Throwable $e): void
    {
        // Normal flow termination — sessizce çık (redirect, response send vb.)
        if ($e instanceof TerminateException) {
            exit;
        }

        // CSRF hatası
        if ($e instanceof CsrfException) {
            http_response_code(419);
            Logger::warning('CSRF token mismatch', [
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                'uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
            ]);

            if ($this->shouldReturnJson()) {
                $this->jsonResponse(419, 'CSRF token mismatch', 'csrf_error');
            } else {
                echo 'CSRF Hatası: '.htmlspecialchars($e->getMessage());
            }
            exit;
        }

        // HTTP abort() hatası (403, 404, vs.)
        if ($e instanceof HttpException) {
            $this->handleHttp($e);
            exit;
        }

        // Eloquent ModelNotFoundException -> 404
        if ($e instanceof ModelNotFoundException) {
            $this->handleHttp(new HttpException(404, 'Kayıt bulunamadı.'));
            exit;
        }

        // Beklenmedik hata
        if (class_exists(DebugBar::class) && DebugBar::isEnabled()) {
            DebugBar::addException($e);
        }
        $this->handleGeneric($e);
    }

    // ── Private handlers ─────────────────────────────────────────────────────

    private function handleHttp(HttpException $e): void
    {
        $code = $e->getStatusCode();
        http_response_code($code);

        Logger::warning("HTTP {$code}", [
            'message' => $e->getMessage(),
            'uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
        ]);

        // API istekleri → JSON yanıt
        if ($this->shouldReturnJson()) {
            $this->jsonResponse($code, $e->getMessage() ?: "HTTP $code");

            return;
        }

        // Web istekleri → HTML hata sayfası
        $view = match (true) {
            $code === 403 => 'errors/403',
            $code === 404 => 'errors/404',
            default => 'errors/500',
        };

        try {
            View::render($view, ['message' => $e->getMessage() ?: "HTTP $code"]);
        } catch (\Throwable) {
            echo "HTTP {$code}: ".htmlspecialchars($e->getMessage());
        }
    }

    private function handleGeneric(\Throwable $e): void
    {
        http_response_code(500);

        Logger::error('Uncaught exception: '.$e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        // API istekleri → JSON yanıt
        if ($this->shouldReturnJson()) {
            $data = ['message' => 'Internal Server Error'];

            if (filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
                $data['debug'] = [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ];
            }

            $this->jsonResponse(500, $data['message'], 'server_error', $data['debug'] ?? null);
            exit;
        }

        // Web istekleri → HTML hata sayfası
        if (filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            echo '<pre style="background:#1e1e1e;color:#d4d4d4;padding:1rem">'
                .htmlspecialchars($e->getMessage()."\n\n".$e->getTraceAsString())
                .'</pre>';
            exit;
        }

        try {
            View::render('errors/500', ['message' => 'Beklenmeyen bir hata oluştu.']);
        } catch (\Throwable) {
            echo 'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.';
        }

        exit;
    }

    // ── JSON helpers ─────────────────────────────────────────────────────────

    /**
     * İsteğin JSON yanıt bekleyip beklemediğini tespit et.
     *
     * Şu durumlarda true döner:
     *   - URI, API prefix'i ile başlıyorsa (örn: /api/...)
     *   - Accept header'ında 'application/json' varsa
     *   - X-Requested-With: XMLHttpRequest ise (AJAX)
     */
    private function shouldReturnJson(): bool
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $apiPrefix = config('middleware.api_prefix', '/api');

        // URI API prefix'i ile başlıyorsa
        if (str_starts_with($path, $apiPrefix.'/') || $path === $apiPrefix) {
            return true;
        }

        // Accept header kontrolü
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        if (str_contains($accept, 'application/json')) {
            return true;
        }

        // AJAX isteği kontrolü
        if (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest') {
            return true;
        }

        return false;
    }

    /**
     * Tutarlı JSON hata yanıtı gönder.
     */
    private function jsonResponse(int $status, string $message, string $error = 'error', ?array $debug = null): void
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($status);

        $body = [
            'error' => $error,
            'status' => $status,
            'message' => $message,
        ];

        if ($debug !== null) {
            $body['debug'] = $debug;
        }

        echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
