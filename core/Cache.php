<?php

declare(strict_types=1);

namespace Core;

use Core\Facades\Log;

/**
 * Cache — file-based cache with HMAC integrity and TTL support.
 * Cache — HMAC bütünlük koruması ve TTL desteğine sahip dosya tabanlı önbellek.
 *
 * Instance-based architecture — resolved from Container via Facade.
 * Instance tabanlı mimari — Facade aracılığıyla Container'dan çözümlenir.
 *
 * Usage via Facade / Facade ile kullanım:
 *   Cache::get('key');
 *   Cache::set('key', 'value', 3600);
 *   Cache::remember('key', 3600, fn() => expensiveQuery());
 *   Cache::forget('key');
 *   Cache::flush();
 *   Cache::has('key');
 *   Cache::pull('key');
 */
class Cache
{
    private string $cachePath;

    /** Key prefix from config('cache.prefix') — namespaces cache entries */
    private string $prefix;

    /** Default TTL (seconds) from config('cache.default_ttl') */
    private int $defaultTtl;

    /** Cached app key — computed once per instance */
    private ?string $appKey = null;

    public function __construct()
    {
        // Config-driven: path, prefix and default TTL come from config/cache.php
        // Config tabanlı: yol, önek ve varsayılan TTL config/cache.php'den gelir
        $this->cachePath = (string) config('cache.path', BASE_PATH.'/storage/cache');
        $this->prefix = (string) config('cache.prefix', '');
        $this->defaultTtl = (int) config('cache.default_ttl', 3600);

        if (! is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0700, true);
        }
    }

    /**
     * Build the cache file path for a key (prefix applied before hashing).
     * Bir anahtar için cache dosya yolunu üretir (hash'lemeden önce önek eklenir).
     */
    private function filename(string $key): string
    {
        return $this->cachePath.'/'.hash('sha256', $this->prefix.$key).'.cache';
    }

    private function getAppKey(): string
    {
        if ($this->appKey === null) {
            $key = $_ENV['APP_KEY'] ?? '';

            if (! is_string($key) || $key === '') {
                // Fallback anahtar BASE_PATH'ten türetilir — yolu bilen biri HMAC'i
                // üretebilir, yani bütünlük koruması yalnızca APP_KEY ile gerçek olur.
                // Sessizce zayıf anahtara düşme; süreç başına bir kez uyar.
                // The fallback key derives from BASE_PATH — anyone knowing the path can
                // forge the HMAC, so integrity is only real with APP_KEY set. Don't fall
                // back silently; warn once per process.
                static $warned = false;
                if (! $warned) {
                    $warned = true;
                    try {
                        Log::warning('Cache: APP_KEY tanımlı değil — HMAC için öngörülebilir fallback anahtar kullanılıyor. .env dosyasına APP_KEY ekleyin. // APP_KEY is not set — using a predictable fallback HMAC key. Set APP_KEY in .env.');
                    } catch (\Throwable) {
                        // Logger henüz çözülemiyorsa (erken boot) uyarıyı atla — cache çalışmaya devam etsin.
                        // Skip the warning if the logger can't resolve yet (early boot) — keep the cache working.
                    }
                }

                $key = hash('sha256', BASE_PATH.'umay-cache-key');
            }

            $this->appKey = $key;
        }

        return $this->appKey;
    }

    /**
     * Encode + HMAC-sign a value together with its expiry. Returns null when the
     * value cannot be JSON-encoded.
     * Bir değeri son kullanma tarihiyle birlikte kodla + HMAC ile imzala. Değer
     * JSON'a kodlanamazsa null döner.
     */
    private function encode(mixed $value, int $ttl): ?string
    {
        $serialized = json_encode(['value' => $value, 'expires' => time() + $ttl]);
        if ($serialized === false) {
            return null;
        }

        return hash_hmac('sha256', $serialized, $this->getAppKey()).':'.$serialized;
    }

    /**
     * Verify HMAC + TTL and return the stored value, or $default on a missing,
     * tampered, malformed or expired payload. array_key_exists (not isset) is used
     * so a legitimately stored null value round-trips correctly.
     * HMAC + TTL doğrula ve saklanan değeri döndür; eksik, kurcalanmış, bozuk veya
     * süresi dolmuş içerikte $default döner. Meşru biçimde saklanmış null değerin
     * doğru dönebilmesi için isset değil array_key_exists kullanılır.
     */
    private function decode(string $content, mixed $default = null): mixed
    {
        $colonPos = strpos($content, ':');
        if ($colonPos === false) {
            return $default;
        }

        $signature = substr($content, 0, $colonPos);
        $serialized = substr($content, $colonPos + 1);

        if (! hash_equals(hash_hmac('sha256', $serialized, $this->getAppKey()), $signature)) {
            return $default;
        }

        $data = json_decode($serialized, true);

        if (! is_array($data) || ! array_key_exists('expires', $data) || ! array_key_exists('value', $data)) {
            return $default;
        }

        if ($data['expires'] < time()) {
            return $default;
        }

        return $data['value'];
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $filename = $this->filename($key);

        if (! file_exists($filename)) {
            if (defined('UMAY_PROFILING') && UMAY_PROFILING) {
                DebugBar::addCacheOp('get', $key, false);
            }

            return $default;
        }

        $content = @file_get_contents($filename);
        if ($content === false) {
            return $default;
        }

        // decode() verifies HMAC + TTL. A miss sentinel lets us delete corrupt or
        // expired files while still distinguishing them from a stored null value.
        // decode() HMAC + TTL doğrular. Miss sentinel'i sayesinde bozuk/süresi dolmuş
        // dosyalar silinirken bunlar saklanmış null değerden ayırt edilebilir.
        $miss = "\0__umay_cache_miss__\0";
        $value = $this->decode($content, $miss);

        if ($value === $miss) {
            @unlink($filename);

            if (defined('UMAY_PROFILING') && UMAY_PROFILING) {
                DebugBar::addCacheOp('get', $key, false);
            }

            return $default;
        }

        if (defined('UMAY_PROFILING') && UMAY_PROFILING) {
            DebugBar::addCacheOp('get', $key, true);
        }

        return $value;
    }

    public function set(string $key, mixed $value, ?int $ttl = null): void
    {
        $payload = $this->encode($value, $ttl ?? $this->defaultTtl);
        if ($payload === null) {
            return;
        }

        // Publish via temp file + atomic rename (same as atomic()) so a concurrent
        // unlocked get() never observes a half-written file. An in-place write lets a
        // reader see a truncated payload, fail the HMAC check and drop a valid entry.
        // Geçici dosya + atomik rename ile yayınla (atomic() ile aynı); böylece eşzamanlı
        // ve kilitsiz bir get() yarım yazılmış dosya görmez. Yerinde yazım, okuyucunun
        // kesik içerik görüp HMAC kontrolünü düşürerek geçerli kaydı atmasına yol açardı.
        $filename = $this->filename($key);
        $tmp = $filename.'.'.bin2hex(random_bytes(4)).'.tmp';
        if (@file_put_contents($tmp, $payload) !== false) {
            @rename($tmp, $filename);
        }

        if (defined('UMAY_PROFILING') && UMAY_PROFILING) {
            DebugBar::addCacheOp('set', $key);
        }
    }

    /**
     * Atomically read-modify-write an entry under a cross-process exclusive lock.
     *
     * The mutator receives the current value (null on miss/expired) and returns the
     * value to persist. Read + write happen while a sibling ".lock" file is held with
     * LOCK_EX, and the new payload is published via a temp file + atomic rename — so
     * concurrent callers cannot interleave (this is what makes the rate-limiter
     * increment race-free / TOCTOU-safe) and unlocked readers never see a partial file.
     *
     * Bir kaydı, süreçler arası özel kilit altında atomik biçimde oku-değiştir-yaz.
     * Mutator mevcut değeri alır (miss/expired'da null) ve saklanacak değeri döndürür.
     * Okuma + yazma kardeş ".lock" dosyası LOCK_EX ile tutulurken yapılır; yeni içerik
     * geçici dosya + atomik rename ile yayınlanır — böylece eşzamanlı çağrılar iç içe
     * geçemez (rate-limiter artışını yarış-koşulundan/TOCTOU'dan korur) ve kilitsiz
     * okuyucular asla yarım dosya görmez.
     *
     * @param  callable(mixed): mixed  $mutator
     *
     * @throws \RuntimeException When the cross-process lock cannot be acquired.
     */
    public function atomic(string $key, callable $mutator, ?int $ttl = null): mixed
    {
        $ttl ??= $this->defaultTtl;
        $filename = $this->filename($key);

        // Lock on a fixed pool of bucket files instead of a per-key ".lock" sidecar,
        // so lock files can't accumulate unboundedly (previously one per distinct key
        // ever seen — e.g. one per IP+route for the rate limiter). Distinct keys that
        // share a bucket only serialise; a given key always maps to the same bucket,
        // so mutual exclusion for that key still holds.
        // Anahtar başına ".lock" yan dosyası yerine sabit bir kilit havuzunda kilitle;
        // böylece kilit dosyaları sınırsız birikemez (önceden görülen her ayrı anahtar
        // için bir tane — örn. rate limiter'da IP+route başına). Aynı kovayı paylaşan
        // farklı anahtarlar yalnızca sıraya girer; belirli bir anahtar hep aynı kovaya
        // düşer, o anahtar için karşılıklı dışlama korunur.
        $lockFile = $this->cachePath.'/umay-lock-'.(abs(crc32($this->prefix.$key)) % 256).'.lock';

        $lock = @fopen($lockFile, 'c');
        if ($lock === false) {
            // Fail closed: without the lock we cannot guarantee atomicity, so a silent
            // non-atomic read-modify-write would let concurrent callers race past a rate
            // limit (TOCTOU). The only callers are rate limiters, so throwing denies the
            // request rather than letting it bypass the cap.
            // Fail-closed: kilit olmadan atomiklik garanti edilemez; sessiz, atomik olmayan
            // oku-değiştir-yaz eşzamanlı çağrıların rate limit'i yarışarak aşmasına (TOCTOU)
            // izin verirdi. Tek çağıranlar rate limiter olduğundan, exception fırlatmak
            // isteği bypass ettirmek yerine reddeder.
            throw new \RuntimeException("Cache::atomic could not acquire lock for [{$key}].");
        }

        // flock() can fail too (lock table exhaustion, unsupported FS…) — same
        // fail-closed reasoning as the fopen check above: continuing without the lock
        // would silently drop atomicity and let rate-limit callers race past the cap.
        // flock() da başarısız olabilir (kilit tablosu dolması, desteklemeyen FS…) —
        // yukarıdaki fopen kontrolüyle aynı fail-closed gerekçe: kilitsiz devam etmek
        // atomikliği sessizce düşürür ve rate-limit çağıranların limiti yarışarak
        // aşmasına izin verirdi.
        if (! flock($lock, LOCK_EX)) {
            fclose($lock);
            throw new \RuntimeException("Cache::atomic could not acquire lock for [{$key}].");
        }

        try {
            $miss = "\0__umay_cache_miss__\0";
            $content = @file_get_contents($filename);
            $current = ($content === false) ? $miss : $this->decode($content, $miss);

            $new = $mutator($current === $miss ? null : $current);

            $payload = $this->encode($new, $ttl);
            if ($payload !== null) {
                // temp + atomic rename so readers never observe a half-written file
                // geçici + atomik rename; okuyucular yarı-yazılmış dosya görmesin
                $tmp = $filename.'.'.bin2hex(random_bytes(4)).'.tmp';
                if (@file_put_contents($tmp, $payload) !== false) {
                    @rename($tmp, $filename);
                }
            }

            if (defined('UMAY_PROFILING') && UMAY_PROFILING) {
                DebugBar::addCacheOp('set', $key);
            }

            return $new;
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }

    public function remember(string $key, int $ttl, callable $callback): mixed
    {
        // Sentinel ile "miss" ile "saklanmış null" ayırt edilir; aksi halde null
        // değerler her seferinde callback'i yeniden tetiklerdi.
        // A sentinel distinguishes "miss" from "stored null"; otherwise a cached
        // null would re-invoke the callback on every call.
        $miss = "\0__umay_cache_miss__\0";
        $value = $this->get($key, $miss);

        if ($value !== $miss) {
            return $value;
        }

        $value = $callback();
        $this->set($key, $value, $ttl);

        return $value;
    }

    public function forget(string $key): void
    {
        $filename = $this->filename($key);

        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    public function flush(): void
    {
        $files = glob($this->cachePath.'/*.cache') ?: [];
        foreach ($files as $file) {
            unlink($file);
        }

        // Remove lock/temp sidecars created by atomic().
        // atomic() tarafından oluşturulan kilit/geçici yan dosyaları sil.
        foreach (array_merge(glob($this->cachePath.'/*.lock') ?: [], glob($this->cachePath.'/*.tmp') ?: []) as $file) {
            @unlink($file);
        }
    }

    public function pull(string $key, mixed $default = null): mixed
    {
        $value = $this->get($key, $default);
        $this->forget($key);

        return $value;
    }

    public function has(string $key): bool
    {
        $miss = "\0__umay_cache_miss__\0";

        return $this->get($key, $miss) !== $miss;
    }
}
