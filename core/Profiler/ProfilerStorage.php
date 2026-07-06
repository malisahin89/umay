<?php

declare(strict_types=1);

namespace Core\Profiler;

/**
 * ProfilerStorage — JSON file-based profiler data store.
 * ProfilerStorage — JSON dosya tabanlı profiler veri deposu.
 *
 * Separate JSON file for each profiler token. Automatic cleanup with TTL and max file count.
 * Her profiler token için ayrı JSON dosyası. TTL ve max dosya sayısı ile
 * otomatik temizlik.
 *
 * Can be disabled in production.
 * Production'da devre dışı bırakılabilir.
 */
class ProfilerStorage
{
    private string $storagePath;

    private int $ttl;

    private int $maxEntries;

    public function __construct(?string $storagePath = null, int $ttl = 7200, int $maxEntries = 200)
    {
        $this->storagePath = $storagePath ?? (defined('BASE_PATH') ? BASE_PATH.'/storage/profiler' : sys_get_temp_dir().'/umay-profiler');
        $this->ttl = $ttl;
        $this->maxEntries = $maxEntries;

        if (! is_dir($this->storagePath)) {
            @mkdir($this->storagePath, 0700, true);
        }
    }

    /**
     * Saves token-based profile data to file.
     * Token bazlı profil verisini dosyaya kaydeder.
     */
    public function save(string $token, array $data): void
    {
        $data['__meta'] = [
            'token' => $token,
            'timestamp' => time(),
            'date' => date('Y-m-d H:i:s'),
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'CLI',
            // Query'deki token/api_key gibi değerler maskeli yazılır (bkz. Profiler::maskUriQuery)
            // Credential-like query values are stored masked (see Profiler::maskUriQuery)
            'uri' => Profiler::maskUriQuery(is_string($_SERVER['REQUEST_URI'] ?? null) ? $_SERVER['REQUEST_URI'] : '-'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'status' => http_response_code() ?: 200,
        ];

        $file = $this->getFilePath($token);
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        file_put_contents($file, $json, LOCK_EX);
    }

    /**
     * Loads profile data for a token.
     * Token'a ait profil verisini yükler.
     */
    public function load(string $token): ?array
    {
        $file = $this->getFilePath($token);
        if (! is_file($file)) {
            return null;
        }

        $json = @file_get_contents($file);
        if ($json === false) {
            return null;
        }

        $data = json_decode($json, true);

        return is_array($data) ? $data : null;
    }

    /**
     * Returns the token list of recent profiles (newest to oldest).
     * Son profillerin token listesini döndürür (yeniden eskiye).
     */
    public function listTokens(int $limit = 50): array
    {
        $files = glob($this->storagePath.'/*.json');
        if (! $files) {
            return [];
        }

        // Sort by file time (newest to oldest)
        // Dosya zamanına göre sırala (yeniden eskiye)
        usort($files, fn ($a, $b) => filemtime($b) - filemtime($a));

        $tokens = [];
        foreach (array_slice($files, 0, $limit) as $file) {
            $token = basename($file, '.json');
            $meta = null;

            $json = @file_get_contents($file);
            if ($json !== false) {
                $data = json_decode($json, true);
                $meta = $data['__meta'] ?? null;
            }

            $tokens[] = [
                'token' => $token,
                'meta' => $meta,
            ];
        }

        return $tokens;
    }

    /**
     * Returns the last saved token.
     * Son kaydedilen token'ı döndürür.
     */
    public function getLatestToken(): ?string
    {
        $list = $this->listTokens(1);

        return $list[0]['token'] ?? null;
    }

    /**
     * Cleans up old profiles based on TTL & max file count.
     * TTL & max dosya sayısına göre eski profilleri temizler.
     */
    public function cleanup(): void
    {
        $files = glob($this->storagePath.'/*.json');
        if (! $files) {
            return;
        }

        $now = time();

        // TTL check — delete expired files
        // TTL kontrolü — süresi dolan dosyaları sil
        foreach ($files as $file) {
            if (($now - filemtime($file)) > $this->ttl) {
                @unlink($file);
            }
        }

        // Max file count check
        // Max dosya sayısı kontrolü
        $files = glob($this->storagePath.'/*.json');
        if ($files && count($files) > $this->maxEntries) {
            usort($files, fn ($a, $b) => filemtime($a) - filemtime($b));
            $toDelete = array_slice($files, 0, count($files) - $this->maxEntries);
            foreach ($toDelete as $file) {
                @unlink($file);
            }
        }
    }

    private function getFilePath(string $token): string
    {
        // Convert token to safe file name
        // Token'ı güvenli dosya adına dönüştür
        $safe = preg_replace('/[^a-zA-Z0-9_\-]/', '', $token);

        return $this->storagePath.'/'.$safe.'.json';
    }
}
