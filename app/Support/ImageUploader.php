<?php

declare(strict_types=1);

namespace App\Support;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use RuntimeException;

/**
 * Converts an uploaded image into a WebP file on the public storage disk.
 * Yüklenen bir görseli public storage diskinde WebP dosyasına çevirir.
 *
 * Storage is configured in config/uploads.php (root, quality, size/mime limits).
 * A returned path is disk-relative, e.g. "uploads/posts/2026-07/kapak-….webp",
 * reachable on the web at "{url_prefix}/uploads/posts/…".
 *
 * Depolama config/uploads.php'de ayarlanır (kök, kalite, boyut/mime limitleri).
 * Dönen yol disk-göreli; web'de "{url_prefix}/uploads/…" adresinden erişilir.
 */
final class ImageUploader
{
    /**
     * Store a single uploaded file ($_FILES entry) as WebP under uploads/{subDir}/{YYYY-MM}/.
     * Returns the disk-relative path, or null when no file was uploaded.
     * Tek bir yüklenen dosyayı ($_FILES girdisi) uploads/{subDir}/{YYYY-MM}/ altına WebP
     * olarak kaydeder. Disk-göreli yolu döndürür; dosya yoksa null.
     *
     * @param  array<array-key, mixed>  $file  A single $_FILES entry (name, type, tmp_name, error, size)
     *
     * @throws RuntimeException on oversize / unsupported type / write failure
     */
    public static function storeWebp(array $file, string $subDir): ?string
    {
        $error = is_int($file['error'] ?? null) ? $file['error'] : UPLOAD_ERR_NO_FILE;
        if ($error === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if ($error !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Dosya yüklenemedi (hata kodu '.$error.').');
        }

        $tmp = is_string($file['tmp_name'] ?? null) ? $file['tmp_name'] : '';
        if ($tmp === '' || ! is_uploaded_file($tmp)) {
            throw new RuntimeException('Geçersiz yükleme.');
        }

        // Size guard.
        // Boyut kontrolü.
        $maxCfg = config('uploads.max_size', 20 * 1024 * 1024);
        $max = is_numeric($maxCfg) ? (int) $maxCfg : 20 * 1024 * 1024;
        $size = is_int($file['size'] ?? null) ? $file['size'] : 0;
        if ($size > $max) {
            throw new RuntimeException('Dosya çok büyük (en fazla '.self::humanSize($max).').');
        }

        // MIME guard — sniff the real type from the temp file, never trust the client.
        // MIME kontrolü — gerçek tipi geçici dosyadan tespit et, istemciye güvenme.
        $mime = (string) (new \finfo(FILEINFO_MIME_TYPE))->file($tmp);
        /** @var array<int, string> $allowed */
        $allowed = is_array($a = config('uploads.allowed_mimes')) ? $a : ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (! in_array($mime, $allowed, true)) {
            throw new RuntimeException('Desteklenmeyen görsel tipi: '.$mime);
        }

        // Build destination path: uploads/{subDir}/{YYYY-MM}/{slug}-{ts}-{rand}.webp
        // Hedef yol: uploads/{subDir}/{YYYY-MM}/{slug}-{ts}-{rand}.webp
        $relDir = 'uploads/'.trim($subDir, '/').'/'.date('Y-m');
        $absDir = self::root().'/'.$relDir;
        if (! is_dir($absDir) && ! mkdir($absDir, 0775, true) && ! is_dir($absDir)) {
            throw new RuntimeException('Klasör oluşturulamadı: '.$relDir);
        }

        $originalName = is_string($file['name'] ?? null) ? $file['name'] : 'gorsel';
        $base = str_slug(pathinfo($originalName, PATHINFO_FILENAME));
        if ($base === '') {
            $base = 'gorsel';
        }
        $name = $base.'-'.time().'-'.bin2hex(random_bytes(3)).'.webp';
        $relPath = $relDir.'/'.$name;

        $qualityCfg = config('uploads.quality', 80);
        $quality = is_numeric($qualityCfg) ? (int) $qualityCfg : 80;

        // Intervention v4: decode from path, save by extension (.webp → WebP encoder).
        // Intervention v4: yoldan çöz, uzantıya göre kaydet (.webp → WebP encoder).
        $manager = new ImageManager(new Driver);
        $manager->decodePath($tmp)->save(self::root().'/'.$relPath, quality: $quality);

        return $relPath;
    }

    /**
     * Delete a previously stored file by its disk-relative path (best effort).
     * Daha önce kaydedilmiş bir dosyayı disk-göreli yoluyla siler (best effort).
     */
    public static function delete(string $relPath): void
    {
        $relPath = ltrim($relPath, '/');
        if ($relPath === '') {
            return;
        }
        $abs = self::root().'/'.$relPath;
        if (is_file($abs)) {
            @unlink($abs);
        }
    }

    /** Physical root of the public storage disk. / Public storage diskinin fiziksel kökü. */
    private static function root(): string
    {
        $root = config('uploads.root');

        return is_string($root) ? rtrim($root, '/\\') : dirname(__DIR__, 2).'/storage/app/public';
    }

    private static function humanSize(int $bytes): string
    {
        return $bytes >= 1048576
            ? round($bytes / 1048576, 1).' MB'
            : round($bytes / 1024).' KB';
    }
}
