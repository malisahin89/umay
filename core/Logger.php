<?php

declare(strict_types=1);

namespace Core;

/**
 * Logger — file-based application logger with daily rotation.
 * Logger — günlük rotasyonlu dosya tabanlı uygulama logger'ı.
 *
 * Instance-based architecture — resolved from Container via Facade.
 * Instance tabanlı mimari — Facade aracılığıyla Container'dan çözümlenir.
 *
 * Usage via Facade / Facade ile kullanım:
 *   Log::info('User logged in', ['user_id' => 5]);
 *   Log::error('Payment failed', ['order_id' => 123]);
 *   Log::warning('Low stock', ['product' => 'Widget']);
 */
class Logger
{
    private string $logPath;

    public function __construct()
    {
        $this->logPath = BASE_PATH.'/storage/logs';
        if (! is_dir($this->logPath)) {
            mkdir($this->logPath, 0700, true);
        }
    }

    public function error(string $message, array $context = []): void
    {
        $this->log('ERROR', $message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->log('WARNING', $message, $context);
    }

    public function info(string $message, array $context = []): void
    {
        $this->log('INFO', $message, $context);
    }

    private function log(string $level, string $message, array $context = []): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $ip = str_replace(["\r", "\n"], '', (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
        $userAgent = str_replace(["\r", "\n"], '', (string) ($_SERVER['HTTP_USER_AGENT'] ?? 'unknown'));

        // Log injection koruması — message içindeki satır sonlarını boşluğa çevir
        // Log injection protection — collapse newlines in the message
        $message = str_replace(["\r", "\n"], ' ', $message);

        $logEntry = sprintf(
            "[%s] %s: %s | IP: %s | Context: %s | User-Agent: %s\n",
            $timestamp,
            $level,
            $message,
            $ip,
            json_encode($context),
            $userAgent
        );

        $filename = $this->logPath.'/'.date('Y-m-d').'.log';
        file_put_contents($filename, $logEntry, FILE_APPEND | LOCK_EX);

        if (class_exists(DebugBar::class) && DebugBar::isEnabled()) {
            DebugBar::addLog($level, $message, $context);
        }
    }
}
