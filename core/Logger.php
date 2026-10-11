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

    /** @param array<array-key, mixed> $context */
    public function error(string $message, array $context = []): void
    {
        $this->log('ERROR', $message, $context);
    }

    /** @param array<array-key, mixed> $context */
    public function warning(string $message, array $context = []): void
    {
        $this->log('WARNING', $message, $context);
    }

    /** @param array<array-key, mixed> $context */
    public function info(string $message, array $context = []): void
    {
        $this->log('INFO', $message, $context);
    }

    /** @param array<array-key, mixed> $context */
    private function log(string $level, string $message, array $context = []): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $ip = str_replace(["\r", "\n"], '', (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
        $userAgent = str_replace(["\r", "\n"], '', (string) ($_SERVER['HTTP_USER_AGENT'] ?? 'unknown'));

        // Log injection koruması — message içindeki satır sonlarını boşluğa çevir
        // Log injection protection — collapse newlines in the message
        $message = str_replace(["\r", "\n"], ' ', $message);

        // Hassas bağlam alanlarını maskele — parola/token/cookie vb. log dosyasına
        // düz metin düşmesin. Anahtar adına göre çalışır (iç içe diziler dahil).
        // Redact sensitive context values — keep passwords/tokens/cookies out of the
        // log file. Key-name based, recursive into nested arrays.
        $context = $this->redact($context);

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

    /**
     * Mask context values whose KEY looks credential-like.
     * Anahtarı kimlik bilgisi gibi görünen bağlam değerlerini maskeler.
     *
     * @param  array<array-key, mixed>  $context
     * @return array<array-key, mixed>
     */
    private function redact(array $context): array
    {
        $pattern = '/(password|passwd|pwd|token|secret|authorization|api[_-]?key|apikey|remember|cookie|session|credential)/i';

        $out = [];
        foreach ($context as $key => $value) {
            if (is_string($key) && preg_match($pattern, $key)) {
                $out[$key] = '***';
            } elseif (is_array($value)) {
                $out[$key] = $this->redact($value);
            } elseif (is_object($value)) {
                // Recurse into objects too — otherwise a credential nested in an
                // object (e.g. a DTO) was passed through unmasked.
                // Nesnelere de in — aksi halde bir nesnede (örn. DTO) iç içe bir kimlik
                // bilgisi maskelenmeden geçerdi.
                $out[$key] = $this->redact((array) $value);
            } else {
                $out[$key] = $value;
            }
        }

        return $out;
    }
}
