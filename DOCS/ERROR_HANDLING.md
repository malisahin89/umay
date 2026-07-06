# Hata ve İstisna Yönetimi

## Amaç
Merkezi istisna yönetimini ve hata yanıtlarını belgeler.

## Genel Bakış
`Core\ExceptionHandler::handle(\Throwable $e)`, ön kontrolcünün try/catch bloğundan çağrılan merkezi yöneticidir. Hibrit web/API mimarisini destekler: web istekleri HTML hata sayfaları, API istekleri JSON alır.

## İstisna Dağıtımı
- `TerminateException` $\to$ sessizce `exit` (normal akış sonlandırması, örn. yönlendirme/yanıt gönderiminden sonra).
- `CsrfException` $\to$ HTTP **419**, bir uyarı günlüğüne kaydeder; istek türüne göre JSON veya düz metin mesajı döndürür.
- `HttpException` (`abort()`'dan gelen) $\to$ kendi durum kodu ile `handleHttp()` tarafından işlenir.
- Eloquent `ModelNotFoundException` $\to$ `HttpException(404)`'e eşlenir.
- Diğer her şey $\to$ `handleGeneric()` (HTTP 500); etkinse `DebugBar`'a bildirilir.

## Web ve API Algılama (`shouldReturnJson`)
Şu durumlarda JSON döndürür: yol `config('middleware.api_prefix')` (varsayılan `/api`) ile başlıyorsa, `Accept` başlığı `application/json` içeriyorsa veya `X-Requested-With: XMLHttpRequest` ise.

## HTTP Hataları (`handleHttp`)
- Yanıt kodunu ayarlar, bir uyarı günlüğüne kaydeder.
- Web: `errors/403`, `errors/404` veya `errors/500` görünümlerini işler (görünüm başarısız olursa düz metne döner).
- API: tutarlı bir JSON gövdesi `{error, status, message}` döndürür.

## Genel Hatalar (`handleGeneric`)
- HTTP 500; istisnayı (dosya/satır/iz) günlüğe kaydeder.
- API: `{message: "Internal Server Error"}` ve `APP_DEBUG` aktifse bir `debug` bloğu ekler.
- Web: `APP_DEBUG` aktifse ham iz dökümü (trace dump), aksi takdirde `errors/500` görünümü.

## İstisna Sınıfları
- `Core\Exceptions\HttpException` (durum kodu; varsayılan 403/500), `Core\Exceptions\ContainerException`, `Core\Exceptions\EntryNotFoundException` (PSR-11), `Core\CsrfException`, `Core\TerminateException`, `Core\RedirectException` (`TerminateException`'ı genişletir).

## Çapraz Referanslar
- `DOCS/core/ExceptionHandler.md`, `DOCS/core/Exceptions/index.md`, `DOCS/LOGGING.md`, `DOCS/VIEW_ENGINE.md`
- Testler: `DOCS/tests/Unit/ExceptionClassesTest.md`, `DOCS/tests/Unit/ExceptionFixTest.md`

## Kaynak Referansları
- `core/ExceptionHandler.php:22-196`
- `core/ExceptionHandler.php:151-173` (JSON algılama)
