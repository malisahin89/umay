# Dosya Raporu: tests/Unit/FileUploadTest.php

## Amaç
Dosya yükleme işlemleri ve güvenliği için birim (unit) testler.

## Genel Bakış
`Core\FileUpload`'ı doğrular: dosya adı temizleme (Türkçe/özel karakterlerin kaldırılması, uniqid geri dönüşü, yol aşımı (path-traversal) savunması), yolun `public/` dizini içinde olması, izin verilen MIME türleri (JPEG izinli, PHP reddedilmiş), 2 MB boyut sınırı ve varsayılan/boş/olmayan yollar için güvenli `rename`/`delete` davranışı.

## Dosya Konumu
`tests/Unit/FileUploadTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class FileUploadTest extends Tests\TestCase`

## Test Edilen Konu
- `Core\FileUpload`

## Test Metotları
- `test_sanitize_removes_turkish_characters` — `:28`
- `test_sanitize_removes_special_characters` — `:34`
- `test_sanitize_allows_alphanumeric_dash_underscore` — `:40`
- `test_sanitize_returns_uniqid_for_empty_result` — `:46`
- `test_sanitize_handles_path_traversal_attempt` — `:54`
- `test_path_inside_public_allows_valid_path` — `:71`
- `test_path_outside_public_throws_exception` — `:86`
- `test_allowed_types_includes_jpeg` — `:97`
- `test_allowed_types_does_not_include_php` — `:109`
- `test_max_size_is_2mb` — `:122`
- `test_rename_returns_false_for_default_png` — `:133`
- `test_rename_returns_false_for_empty_path` — `:139`
- `test_delete_returns_false_for_default_png` — `:147`
- `test_delete_returns_false_for_empty_path` — `:153`
- `test_delete_returns_false_for_nonexistent_file` — `:159`

## Çapraz Referanslar
- **Test Eder:** `Core\FileUpload` (bkz. `DOCS/core/FileUpload.md`)

## Kaynak Referansları
- `tests/Unit/FileUploadTest.php:1-164`
