<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Support\ImageUploader;
use Core\Request;
use RuntimeException;

/**
 * Inline image uploads for the rich-text editor (Summernote onImageUpload).
 * Rich-text editörü için satır-içi görsel yükleme (Summernote onImageUpload).
 *
 * Stores the dropped/pasted image as WebP (via ImageUploader) and returns its public
 * URL as JSON so the editor can embed it — this keeps HTML small instead of inlining
 * base64. Lives behind the admin (auth + admin) middleware group.
 *
 * Bırakılan/yapıştırılan görseli WebP olarak kaydeder (ImageUploader ile) ve public
 * URL'ini JSON döndürür; böylece editör base64 gömmek yerine küçük HTML üretir.
 * Admin (auth + admin) middleware grubunun arkasındadır.
 */
class UploadController
{
    public function image(Request $request): void
    {
        if (! $request->hasFile('image')) {
            response()->json(['error' => 'Dosya bulunamadı.'], 422)->send();

            return;
        }

        $file = $request->file('image');
        if (! is_array($file)) {
            response()->json(['error' => 'Geçersiz dosya.'], 422)->send();

            return;
        }

        try {
            $path = ImageUploader::storeWebp($file, 'content');
        } catch (RuntimeException $e) {
            response()->json(['error' => $e->getMessage()], 422)->send();

            return;
        }

        if ($path === null) {
            response()->json(['error' => 'Dosya yüklenemedi.'], 422)->send();

            return;
        }

        response()->json(['url' => '/storage/'.$path])->send();
    }
}
