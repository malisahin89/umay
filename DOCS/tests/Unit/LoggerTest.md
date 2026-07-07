# Dosya Raporu: tests/Unit/LoggerTest.php

## Amaç
Günlükleyici (logger) için birim (unit) testler.

## Genel Bakış
`Core\Logger`'ı doğrular: günlük dosyaları oluşturulur, girişler doğru düzeyi (error/warning/info) taşır, her giriş IP ve kullanıcı aracısını içerir, dosyalar günlük rotasyon kullanır ve birden fazla günlük aynı dosyaya eklenir.

## Dosya Konumu
`tests/Unit/LoggerTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class LoggerTest extends Tests\TestCase`

## Test Edilen Konu
- `Core\Logger`

## Test Metotları
- `test_info_creates_log_file` — `:44`
- `test_error_log_contains_error_level` — `:54`
- `test_warning_log_contains_warning_level` — `:64`
- `test_info_log_contains_info_level` — `:73`
- `test_log_includes_ip_and_user_agent` — `:84`
- `test_log_file_uses_daily_rotation` — `:95`
- `test_multiple_logs_appended_to_same_file` — `:108`

## Çapraz Referanslar
- **Test Eder:** `Core\Logger` (bkz. `DOCS/core/Logger.md`)

## Kaynak Referansları
- `tests/Unit/LoggerTest.php:1-124`
