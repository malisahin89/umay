# Dosya Raporu: core/ExceptionHandler.php

## Amaç
Uygulama için merkezi istisna işleyicisi.

## Genel Bakış
Yakalanamayan tüm istisnaları yönetir. Web istekleri için HTML hata sayfası mı yoksa API istekleri için JSON hata yanıtı mı döndürüleceğine karar verir ve hataları uygun şekilde günlüğe kaydeder.

## Dosya Konumu
`core/ExceptionHandler.php`

## Ad Alanı
`Core`

## İçe Aktarmalar
- `Core\Exceptions\HttpException`
- `Core\Facades\Log as Logger`
- `Core\Facades\View`
- `Illuminate\Database\Eloquent\ModelNotFoundException`

## Sınıflar
- `class ExceptionHandler`

## Metotlar
- `handle(\Throwable $e): void`: Ana giriş noktası. İstisnaları türlerine göre belirli işleyicilere yönlendirir.
- `handleHttp(HttpException $e): void`: HTTP'ye özgü istisnaları işler, ilgili hata görünümünü (403, 404, 500) oluşturur veya JSON döndürür.
- `handleGeneric(\Throwable $e): void`: Beklenmedik istisnaları işler, tam iz dökümünü (trace) günlüğe kaydeder ve 500 hatası döndürür.
- `shouldReturnJson(): bool`: URI öneki, `Accept` başlığı veya AJAX başlıklarına dayanarak isteğin bir JSON yanıtı bekleyip beklemediğini tespit eder.
- `jsonResponse(int $status, string $message, string $error = 'error', ?array $debug = null): void`: Standartlaştırılmış bir JSON hata yanıtı gönderir.

## Dahili İş Akışı
1. `TerminateException` fırlatılırsa, sessizce çıkış yapar.
2. `CsrfException` fırlatılırsa, 419 hatası döndürür.
3. `HttpException` veya `ModelNotFoundException` (404) fırlatılırsa, `handleHttp()` çağrılır.
4. Aksi takdirde, `handleGeneric()` çağrılır.

## Bağımlılıklar
- `Core\Exceptions\HttpException` (Kullanır)
- `Core\Facades\Log` (Kullanır)
- `Core\Facades\View` (Kullanır)
- `Core\DebugBar` (İsteğe bağlı)

## Kaynak Referansları
- `core/ExceptionHandler.php:1-196`
