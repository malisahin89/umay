# Görünüm Motoru (View Engine)

## Amaç
Şablonları işleyen ve görünüm yardımcılarını sunan sarmalayıcı olan `Core\View`'ı belgeler.

## Genel Bakış
`Core\View`, League Plates motorunu sarmalar. Örnek tabanlıdır (`View` facade'ı aracılığıyla konteynerden çözümlenir) ve her görünüm örneği için tek bir `Engine` örneğini önbelleğe alır, tüm şablon fonksiyonlarını bir kez kaydeder. Şablonlar `views/` dizininde yer alır ve bileşen tarzı eklemeler (`$this->insert('partials::alert', …)`) için `partials` klasörü kaydedilmiştir.

## İşleme (`render($template, $data)`)
1. Gerekirse oturumu başlatır.
2. `$_SESSION['_flash_errors']`'dan PRG doğrulama hatalarını okur (işlemeden sonrasına kadar saklanır).
3. Flash `success`/`error` verilerini bir kez tüketir ve hatırlar (`$consumedFlash`), böylece `flash()` yardımcısı `$success`/`$error` küresel değişkenleri ile tutarlı kalır.
4. Küresel şablon verilerini ekler: `title`, `errors`, `success`, `error`, `user_id`.
5. Eğer profiler etkinse, işlemi ölçer, görünüm verilerini ekler, profillemeyi bitirir ve `</body>`'den önce araç çubuğu (toolbar) HTML'ini enjekte eder.
6. Çıktıyı ekrana yazdırır (echo), ardından `_old` ve `_flash_errors` değerlerini temizler (tek işlem ömrü).

## Kayıtlı Şablon Fonksiyonları
- Güvenlik: `csrf()` (gizli girdi), `csrf_token()`, `e()` (htmlspecialchars), `method()` (metot taklidi), `nonce()` (CSP nonce).
- Yönlendirme/varlıklar: `route()`, `url()` (eski takma ad), `asset()` — hepsi XSS-korumalıdır.
- Formlar/girdiler: `old()` (korumalı).
- Kimlik Doğrulama: `auth()`, `guest()`.
- Yapılandırma/ortam: `config()`, `app_name()`.
- Tarih: `now()`.
- Çeşitli: `class_if()`, `has_error()`, `error()`, `flash()`, `dd()`.

## Çapraz Referanslar
- `DOCS/TEMPLATE_ENGINE.md`, `DOCS/core/View.md`, `DOCS/core/Facades/View.md`
- Güvenlik yardımcıları: `Core\Csrf`, `Core\Csp` (bkz. `DOCS/SECURITY.md`)
- Testler: `DOCS/tests/Unit/ViewTest.md`

## Kaynak Referansları
- `core/View.php:20-306`
- `core/View.php:61-235` (fonksiyon kaydı), `core/View.php:241-305` (işleme)
