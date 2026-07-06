<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\FileUpload;
use Tests\TestCase;

/**
 * FileUpload testleri.
 *
 * sanitizeFilename, path traversal koruması ve
 * MIME type doğrulama mekanizması test edilir.
 */
class FileUploadTest extends TestCase
{
    // ── sanitizeFilename (Reflection ile private metoda erişim) ───────────

    private function callSanitizeFilename(string $input): string
    {
        $reflection = new \ReflectionMethod(FileUpload::class, 'sanitizeFilename');
        $reflection->setAccessible(true);

        return $reflection->invoke(null, $input);
    }

    public function test_sanitize_removes_turkish_characters(): void
    {
        $result = $this->callSanitizeFilename('türkçe-dosya-adı');
        $this->assertSame('turkce-dosya-adi', $result);
    }

    public function test_sanitize_removes_special_characters(): void
    {
        $result = $this->callSanitizeFilename('file@name!#$%.png');
        $this->assertSame('filenamepng', $result);
    }

    public function test_sanitize_allows_alphanumeric_dash_underscore(): void
    {
        $result = $this->callSanitizeFilename('my-file_2024');
        $this->assertSame('my-file_2024', $result);
    }

    public function test_sanitize_returns_uniqid_for_empty_result(): void
    {
        $result = $this->callSanitizeFilename('!!!');
        $this->assertNotEmpty($result);
        // uniqid üretilmiş olmalı
        $this->assertMatchesRegularExpression('/^[a-f0-9]+$/', $result);
    }

    public function test_sanitize_handles_path_traversal_attempt(): void
    {
        $result = $this->callSanitizeFilename('../../etc/passwd');
        // ../.. ve / karakterleri silinmeli
        $this->assertStringNotContainsString('..', $result);
        $this->assertStringNotContainsString('/', $result);
    }

    // ── assertPathInsidePublic (Reflection ile private metoda erişim) ─────

    private function callAssertPathInsidePublic(string $path): void
    {
        $reflection = new \ReflectionMethod(FileUpload::class, 'assertPathInsidePublic');
        $reflection->setAccessible(true);
        $reflection->invoke(null, $path);
    }

    public function test_path_inside_public_allows_valid_path(): void
    {
        $validPath = BASE_PATH.'/public/uploads/test.jpg';

        // public/uploads dizini yoksa oluştur
        $uploadsDir = BASE_PATH.'/public/uploads';
        if (! is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0755, true);
        }

        // Exception fırlatmamalı
        $this->callAssertPathInsidePublic($uploadsDir.'/test.jpg');
        $this->assertTrue(true); // buraya ulaştıysa başarılı
    }

    public function test_path_outside_public_throws_exception(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Güvenlik ihlali');

        // public dışına çıkma denemesi
        $this->callAssertPathInsidePublic(BASE_PATH.'/config/database.php');
    }

    // ── MIME type — allowedTypes kontrol ──────────────────────────────────

    public function test_allowed_types_includes_jpeg(): void
    {
        $reflection = new \ReflectionProperty(FileUpload::class, 'allowedTypes');
        $reflection->setAccessible(true);
        $types = $reflection->getValue();

        $this->assertContains('image/jpeg', $types);
        $this->assertContains('image/png', $types);
        $this->assertContains('image/webp', $types);
        $this->assertContains('image/gif', $types);
    }

    public function test_allowed_types_does_not_include_php(): void
    {
        $reflection = new \ReflectionProperty(FileUpload::class, 'allowedTypes');
        $reflection->setAccessible(true);
        $types = $reflection->getValue();

        $this->assertNotContains('application/x-php', $types);
        $this->assertNotContains('text/html', $types);
        $this->assertNotContains('application/javascript', $types);
    }

    // ── maxSize kontrol ──────────────────────────────────────────────────

    public function test_max_size_is_2mb(): void
    {
        $reflection = new \ReflectionProperty(FileUpload::class, 'maxSize');
        $reflection->setAccessible(true);
        $maxSize = $reflection->getValue();

        $this->assertSame(2097152, $maxSize); // 2MB = 2 * 1024 * 1024
    }

    // ── rename — güvenlik kontrolleri ─────────────────────────────────────

    public function test_rename_returns_false_for_default_png(): void
    {
        $result = FileUpload::rename('default.png', 'newname');
        $this->assertFalse($result);
    }

    public function test_rename_returns_false_for_empty_path(): void
    {
        $result = FileUpload::rename('', 'newname');
        $this->assertFalse($result);
    }

    // ── delete — güvenlik kontrolleri ─────────────────────────────────────

    public function test_delete_returns_false_for_default_png(): void
    {
        $result = FileUpload::delete('default.png');
        $this->assertFalse($result);
    }

    public function test_delete_returns_false_for_empty_path(): void
    {
        $result = FileUpload::delete('');
        $this->assertFalse($result);
    }

    public function test_delete_returns_false_for_nonexistent_file(): void
    {
        $result = FileUpload::delete('uploads/nonexistent_file_xyz.jpg');
        $this->assertFalse($result);
    }

    // ── uniqueFilename — mevcut dosyanın üzerine yazmayı önler ────────────
    // ── uniqueFilename — prevents overwriting an existing file ────────────

    private function callUniqueFilename(string $dir, string $filename, string $extension): string
    {
        $reflection = new \ReflectionMethod(FileUpload::class, 'uniqueFilename');
        $reflection->setAccessible(true);

        return $reflection->invoke(null, $dir, $filename, $extension);
    }

    public function test_unique_filename_returns_name_as_is_when_free(): void
    {
        $dir = sys_get_temp_dir().'/umay_upload_test_'.uniqid();
        mkdir($dir, 0700, true);

        try {
            $this->assertSame('avatar', $this->callUniqueFilename($dir, 'avatar', 'webp'));
        } finally {
            rmdir($dir);
        }
    }

    public function test_unique_filename_appends_suffix_on_collision(): void
    {
        $dir = sys_get_temp_dir().'/umay_upload_test_'.uniqid();
        mkdir($dir, 0700, true);
        file_put_contents($dir.'/avatar.webp', 'x');

        try {
            $result = $this->callUniqueFilename($dir, 'avatar', 'webp');

            // Çakışmada sessizce üzerine yazmak yerine rastgele sonek eklenir.
            // On collision a random suffix is appended instead of a silent overwrite.
            $this->assertNotSame('avatar', $result);
            $this->assertMatchesRegularExpression('/^avatar-[a-f0-9]{8}$/', $result);
        } finally {
            unlink($dir.'/avatar.webp');
            rmdir($dir);
        }
    }
}
