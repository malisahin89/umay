<?php

declare(strict_types=1);

namespace Core;

use Core\Facades\Log;

class FileUpload
{
    private static $allowedTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    // MIME tipinden güvenli uzantı türetme (kullanıcı dosya adı uzantısına güvenilmez)
    /** @var array<string, string> */
    private static $mimeToExt = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ];

    private static $maxSize = 2097152; // 2MB

    private static $quality = 70; // WebP kalitesi (0-100)

    public static function upload(array $file, string $directory = 'uploads', bool $convertToWebP = true, ?string $customFilename = null): string
    {
        // Rate limiting: 10 uploads/minute per IP. Atomic read-modify-write so concurrent
        // uploads cannot race past the cap (TOCTOU-safe), mirroring RateLimiter::hit().
        // Rate limit: IP başına dakikada 10 yükleme. Atomik oku-değiştir-yaz; eşzamanlı
        // yüklemeler limiti yarışarak aşamaz (TOCTOU-güvenli), RateLimiter::hit() gibi.
        $ip = getRealIP();
        $ratKey = 'upload_rate_'.hash('sha256', $ip);
        $cache = Container::getInstance()->make(Cache::class);
        if ($cache instanceof Cache) {
            $hits = $cache->atomic($ratKey, fn ($current) => (is_int($current) ? $current : 0) + 1, 60);
            if (is_int($hits) && $hits > 10) {
                throw new \Exception('Çok fazla yükleme isteği. Bir dakika bekleyin.');
            }
        }

        // Çoklu dosya input'u (name="photos[]") — PHP tmp_name'i dizi yapar;
        // is_uploaded_file(array) strict_types altında TypeError (ham 500) verirdi.
        // Multi-file input (name="photos[]") — PHP makes tmp_name an array;
        // is_uploaded_file(array) would TypeError (raw 500) under strict_types.
        if (is_array($file['tmp_name'] ?? null)) {
            throw new \Exception('Çoklu dosya dizisi desteklenmiyor — her dosyayı tek tek upload() ile yükleyin.');
        }

        if (! isset($file['tmp_name']) || ! is_string($file['tmp_name']) || ! is_uploaded_file($file['tmp_name'])) {
            throw new \Exception('Geçersiz dosya yükleme.');
        }

        // Dosya boyutu kontrolü
        if ($file['size'] > self::$maxSize) {
            throw new \Exception('Dosya boyutu çok büyük. Maksimum 2MB.');
        }

        // MIME type kontrolü — finfo/finfo_file başarısızlığı da reddedilir (fail-closed)
        // MIME type check — a finfo/finfo_file failure is also rejected (fail-closed)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo === false) {
            throw new \Exception('Dosya türü doğrulanamadı.');
        }
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (! is_string($mimeType) || ! in_array($mimeType, self::$allowedTypes, true)) {
            throw new \Exception('Desteklenmeyen dosya türü.');
        }

        // Dizin parametresinde path traversal koruması (../, mutlak yol veya Windows sürücü yolu reddet)
        // Path traversal protection on directory param (reject ../, absolute path or Windows drive path)
        if (str_contains($directory, '..')
            || str_starts_with($directory, '/')
            || str_starts_with($directory, '\\')
            || preg_match('#^[a-zA-Z]:#', $directory) === 1) {
            throw new \Exception('Geçersiz yükleme dizini.');
        }

        // Upload dizini oluştur
        $uploadPath = BASE_PATH.'/public/'.$directory;
        if (! is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Güvenli dosya adı oluştur — uniqid() zaman tabanlı ve tahmin edilebilir;
        // yüklenen dosya URL'lerinin öngörülemez olması için kriptografik rastgelelik.
        // Secure filename — uniqid() is time-based/guessable; use cryptographic
        // randomness so uploaded file URLs are not predictable.
        $filename = $customFilename ? self::sanitizeFilename($customFilename) : bin2hex(random_bytes(16));
        $tempDestination = $uploadPath.'/'.$filename.'_temp';

        if (! move_uploaded_file($file['tmp_name'], $tempDestination)) {
            throw new \Exception('Dosya yükleme başarısız.');
        }

        // WebP'ye dönüştür
        if ($convertToWebP && function_exists('imagewebp')) {
            try {
                // Resmi yükle
                $image = self::createImageFromFile($tempDestination, $mimeType);

                if ($image === false) {
                    // Dönüştürme başarısız; orijinali MIME uzantısıyla koru
                    return self::keepOriginal($tempDestination, $uploadPath, $directory, $filename, $mimeType);
                }

                // WebP formatında kaydet — çakışmayı önle (özellikle custom filename'de
                // mevcut bir dosyanın üzerine sessizce yazılmasın)
                // Save as WebP — avoid collisions (especially with a custom filename,
                // never silently overwrite an existing file)
                $webpFilename = self::uniqueFilename($uploadPath, $filename, 'webp').'.webp';
                $webpPath = $uploadPath.'/'.$webpFilename;

                // imagewebp() bool döner, exception ATMAZ (disk dolu, izin vb.) —
                // başarısızlıkta var olmayan bir .webp yolu döndürmek yerine orijinali koru.
                // imagewebp() returns bool, it does NOT throw (disk full, permissions…) —
                // on failure keep the original instead of returning a nonexistent .webp path.
                $saved = imagewebp($image, $webpPath, self::$quality);
                imagedestroy($image);

                if (! $saved) {
                    return self::keepOriginal($tempDestination, $uploadPath, $directory, $filename, $mimeType);
                }

                // Geçici dosyayı sil
                unlink($tempDestination);

                return $directory.'/'.$webpFilename;

            } catch (\Exception $e) {
                // Hata durumunda geçici dosyayı temizle
                if (file_exists($tempDestination)) {
                    unlink($tempDestination);
                }
                throw new \Exception('WebP dönüştürme başarısız: '.$e->getMessage());
            }
        }

        // WebP desteği yok; orijinali MIME uzantısıyla koru
        return self::keepOriginal($tempDestination, $uploadPath, $directory, $filename, $mimeType);
    }

    /**
     * Geçici yükleme dosyasını MIME tipinden türetilen uzantıyla (kullanıcı dosya
     * adına güvenilmez) kalıcı hale getirir; mevcut dosyaların üzerine yazmaz.
     * Persists the temp upload with an extension derived from the MIME type (user
     * filename is untrusted); never overwrites existing files.
     */
    private static function keepOriginal(string $tempDestination, string $uploadPath, string $directory, string $filename, string $mimeType): string
    {
        $extension = self::$mimeToExt[$mimeType] ?? 'bin';
        $finalFilename = self::uniqueFilename($uploadPath, $filename, $extension).'.'.$extension;

        if (! rename($tempDestination, $uploadPath.'/'.$finalFilename)) {
            @unlink($tempDestination);
            throw new \Exception('Dosya kaydedilemedi.');
        }

        return $directory.'/'.$finalFilename;
    }

    /**
     * Hedefte aynı adlı dosya varsa rastgele sonek ekleyerek benzersiz bir taban ad
     * döndürür — custom filename'de sessiz üzerine yazmayı önler.
     * Returns a unique base name, appending a random suffix while the target exists —
     * prevents silent overwrites with custom filenames.
     */
    private static function uniqueFilename(string $uploadPath, string $filename, string $extension): string
    {
        $candidate = $filename;
        while (file_exists($uploadPath.'/'.$candidate.'.'.$extension)) {
            $candidate = $filename.'-'.bin2hex(random_bytes(4));
        }

        return $candidate;
    }

    /**
     * MIME type'a göre GD image resource oluşturur
     */
    private static function createImageFromFile(string $filePath, string $mimeType): \GdImage|false
    {
        switch ($mimeType) {
            case 'image/jpeg':
                return imagecreatefromjpeg($filePath);
            case 'image/png':
                $image = imagecreatefrompng($filePath);
                if ($image === false) {
                    return false;
                }
                // PNG şeffaflığını koru
                imagealphablending($image, false);
                imagesavealpha($image, true);

                return $image;
            case 'image/gif':
                return imagecreatefromgif($filePath);
            case 'image/webp':
                return imagecreatefromwebp($filePath);
            default:
                return false;
        }
    }

    /**
     * Dosya adını güvenli hale getirir
     *
     * @param  string  $filename  Dosya adı
     * @return string Güvenli dosya adı
     */
    private static function sanitizeFilename(string $filename): string
    {
        // Türkçe karakterleri dönüştür
        $turkish = ['ş', 'Ş', 'ı', 'İ', 'ğ', 'Ğ', 'ü', 'Ü', 'ö', 'Ö', 'ç', 'Ç'];
        $english = ['s', 's', 'i', 'i', 'g', 'g', 'u', 'u', 'o', 'o', 'c', 'c'];
        $filename = str_replace($turkish, $english, $filename);

        // Sadece alfanumerik karakterler, tire ve alt çizgi kalsın
        $filename = preg_replace('/[^a-zA-Z0-9\-_]/', '', $filename);

        // Boşsa kriptografik rastgele ad kullan (tahmin edilemez)
        // If empty, fall back to a cryptographically random name (unpredictable)
        if (empty($filename)) {
            $filename = bin2hex(random_bytes(16));
        }

        return $filename;
    }

    /**
     * Verilen dosya yolunun public/ dizini içinde olduğunu doğrular.
     * Path traversal saldırılarını (../../.env vb.) engeller.
     *
     * @param  string  $resolvedPath  Tam dosya yolu
     *
     * @throws \Exception Yol public dizini dışındaysa
     */
    private static function assertPathInsidePublic(string $resolvedPath): void
    {
        $publicDir = realpath(BASE_PATH.'/public');

        if ($publicDir === false) {
            throw new \Exception('Public dizini bulunamadı.');
        }

        // Dosya mevcutsa realpath ile gerçek yolu al
        $real = realpath($resolvedPath);

        // Dosya henüz yoksa (rename hedefi vb.), üst dizini kontrol et
        if ($real === false) {
            $real = realpath(dirname($resolvedPath));
            if ($real === false) {
                throw new \Exception('Geçersiz dosya yolu: Dizin mevcut değil.');
            }
        }

        // Windows'ta büyük/küçük harf farkını normalize et
        $publicDir = str_replace('\\', '/', $publicDir);
        $real = str_replace('\\', '/', $real);

        // Require a real directory boundary: $publicDir itself, or a path under
        // "$publicDir/". Without the trailing separator a sibling like
        // ".../public_html" would falsely prefix-match ".../public".
        // Gerçek dizin sınırı şart: ya $publicDir'in kendisi ya da "$publicDir/" altı.
        // Ayraç olmadan ".../public_html" gibi bir kardeş, ".../public" ile yanlışlıkla
        // prefix-eşleşirdi.
        if ($real !== $publicDir && ! str_starts_with($real, $publicDir.'/')) {
            Log::warning('Path traversal attempt blocked', [
                'attempted_path' => $resolvedPath,
                'resolved_to' => $real,
                'ip' => getRealIP(),
            ]);
            throw new \Exception('Güvenlik ihlali: Dosya yolu izin verilen dizin dışında.');
        }
    }

    /**
     * Dosyayı yeniden adlandırır
     *
     * @param  string  $oldPath  Eski dosya yolu (örn: 'uploads/profiles/ahmet-pp.webp')
     * @param  string  $newFilename  Yeni dosya adı (örn: 'mehmet-pp')
     * @return string|false Yeni dosya yolu veya false
     */
    public static function rename(string $oldPath, string $newFilename): string|false
    {
        if (empty($oldPath) || $oldPath === 'default.png') {
            return false;
        }

        $fullOldPath = BASE_PATH.'/public/'.$oldPath;

        // Path traversal koruması
        self::assertPathInsidePublic($fullOldPath);

        if (! file_exists($fullOldPath) || ! is_file($fullOldPath)) {
            return false;
        }

        // Yeni dosya yolunu oluştur
        $directory = dirname($oldPath);
        $extension = pathinfo($oldPath, PATHINFO_EXTENSION);
        $sanitizedFilename = self::sanitizeFilename($newFilename);
        $newPath = $directory.'/'.$sanitizedFilename.'.'.$extension;
        $fullNewPath = BASE_PATH.'/public/'.$newPath;

        // Hedef yol da public içinde olmalı
        self::assertPathInsidePublic($fullNewPath);

        // Hedefte dosya varsa üzerine yazma — davranış platforma göre değişir
        // (Windows'ta rename başarısız olur, POSIX'te sessizce ezer)
        // Never overwrite an existing target — behaviour is platform-dependent
        // (rename fails on Windows, silently clobbers on POSIX)
        if (file_exists($fullNewPath)) {
            return false;
        }

        // Dosyayı yeniden adlandır
        if (rename($fullOldPath, $fullNewPath)) {
            return $newPath;
        }

        return false;
    }

    /**
     * Dosyayı siler
     *
     * @param  string  $filePath  Silinecek dosyanın relative path'i (örn: 'uploads/profiles/abc123.jpg')
     * @return bool Silme başarılı ise true
     */
    public static function delete(string $filePath): bool
    {
        if (empty($filePath) || $filePath === 'default.png') {
            return false;
        }

        $fullPath = BASE_PATH.'/public/'.$filePath;

        // Path traversal koruması
        self::assertPathInsidePublic($fullPath);

        if (file_exists($fullPath) && is_file($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }
}
