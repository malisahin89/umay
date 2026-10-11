# Dizin Raporu: stubs

## Amaç
Konsolun `make:*` komutları tarafından yeni uygulama sınıfları iskeleti oluşturmak için kullanılan kod oluşturma şablonlarını (`*.stub`) tutar.

## Alt Dizinler
- Yok.

## Kaynak Dosyaları
- `controller.stub` — RESTful kontrolcü (bakınız `controller.stub.md`)
- `event.stub` — olay (event) sınıfı (bakınız `event.stub.md`)
- `factory.stub` — model fabrikası (bakınız `factory.stub.md`)
- `listener.stub` — olay dinleyicisi (bakınız `listener.stub.md`)
- `mail.stub` — mailable (bakınız `mail.stub.md`)
- `middleware.stub` — HTTP ara yazılımı (middleware) (bakınız `middleware.stub.md`)
- `migration.stub` — tablo oluşturma migrasyonu (bakınız `migration.stub.md`)
- `migration-soft-deletes.stub` — soft-delete sütun migrasyonu (bakınız `migration-soft-deletes.stub.md`)
- `model.stub` — model (bakınız `model.stub.md`)
- `request.stub` — form isteği (bakınız `request.stub.md`)
- `test.stub` — test durumu (bakınız `test.stub.md`)

## Genel Giriş Noktaları
- Yok. Şablonlar konsol çekirdeği tarafından dahili olarak okunur.

## Dahili Bağımlılıklar
- Tüm şablonlar `Core\Console\Kernel::renderStub()` tarafından okunur ve enterpole edilir.

## Harici Bağımlılıklar
- Yok.

## Çapraz Referanslar
- **Kullanan:** `Core\Console\Kernel` (`core/Console/Kernel.php:699-703`; komuta özel referanslar satır 155, 180, 203, 232, 260, 288, 312, 336, 361, 395).

## Kaynak Referansları
- `stubs/` dizini
- `core/Console/Kernel.php:44-49`, `core/Console/Kernel.php:699-703`
