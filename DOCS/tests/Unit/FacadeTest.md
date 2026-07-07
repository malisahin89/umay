# Dosya Raporu: tests/Unit/FacadeTest.php

## Amaç
Facade taban sınıfı için birim (unit) testler.

## Genel Bakış
`Core\Support\Facade`'i doğrular: statik çağrılar çözümlenmiş örneğe yönlendirilir, `swap` örneği değiştirir, çözümlenmiş örneklerin temizlenmesi yeniden çözünürlüğü zorlar, çözümlenemeyen bir erişici (accessor) hata fırlatır, `getFacadeRoot` doğru örneği döndürür ve tek bir çözümlenmiş örnek temizlenebilir.

## Dosya Konumu
`tests/Unit/FacadeTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class FacadeTest extends Tests\TestCase`

## Test Edilen Konu
- `Core\Support\Facade`

## Test Metotları
- `test_facade_forwards_static_calls_to_resolved_instance` — `:36`
- `test_swap_replaces_resolved_instance` — `:64`
- `test_clear_resolved_instances_forces_re_resolution` — `:99`
- `test_facade_throws_when_accessor_cannot_be_resolved` — `:132`
- `test_get_facade_root_returns_correct_instance` — `:148`
- `test_clear_single_resolved_instance` — `:170`

## Çapraz Referanslar
- **Test Eder:** `Core\Support\Facade` (bkz. `DOCS/core/Support/Facade.md`)

## Kaynak Referansları
- `tests/Unit/FacadeTest.php:1-193`
