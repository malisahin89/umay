<?php

declare(strict_types=1);

/**
 * Upload / image processing configuration.
 * Yükleme / görsel işleme yapılandırması.
 *
 * Files are stored on the "public" storage disk (storage/app/public) which is
 * exposed to the web via a symlink at public/storage (create it once with
 * `php umay storage:link`). A stored path like "uploads/posts/2026-07/x.webp" is
 * therefore reachable at "/storage/uploads/posts/2026-07/x.webp".
 *
 * Dosyalar "public" storage diskinde (storage/app/public) tutulur; bu disk
 * public/storage symlink'i ile web'e açılır (bir kez `php umay storage:link` ile
 * oluştur). Yani "uploads/posts/2026-07/x.webp" gibi bir yol web'de
 * "/storage/uploads/posts/2026-07/x.webp" adresinden erişilir.
 */
return [
    // Physical root of the public storage disk.
    // Public storage diskinin fiziksel kökü.
    'root' => (defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__)).'/storage/app/public',

    // URL prefix the disk root maps to (the public/storage symlink).
    // Disk kökünün karşılık geldiği URL öneki (public/storage symlink'i).
    'url_prefix' => '/storage',

    // WebP quality (0–100). Override per-project via .env.
    // WebP kalitesi (0–100). Proje bazında .env ile değiştir.
    'quality' => (int) ($_ENV['UPLOAD_QUALITY'] ?? 80),

    // Max accepted upload size in bytes (default 20 MB).
    // Kabul edilen azami yükleme boyutu, bayt (varsayılan 20 MB).
    'max_size' => (int) ($_ENV['UPLOAD_MAX_SIZE'] ?? 20 * 1024 * 1024),

    // Accepted source MIME types (converted to WebP on save).
    // Kabul edilen kaynak MIME tipleri (kayıtta WebP'ye çevrilir).
    'allowed_mimes' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
];
