# Dosya Raporu: app/Middleware/ThrottleMiddleware.php

## Amaç
İstek sıklığını kontrol etmek için hız sınırlama (rate limiting) ara yazılımı.

## Genel Bakış
Bir istemcinin belirli bir zaman penceresi içinde yapabileceği istek sayısını sınırlar. Hem satır içi yapılandırmayı (örneğin, `throttle:5,300`) hem de `RateLimiter::for` aracılığıyla tanımlanan adlandırılmış sınırlayıcıları destekler.

## Dosya Konumu
`app/Middleware/ThrottleMiddleware.php`

## İsim Uzayı
`App\Middleware`

## İçe Aktarmalar
- `Core\Contracts\MiddlewareInterface`
- `Core\Facades\RateLimiter`
- `Core\Request`
- `Core\TerminateException`

## Sınıflar
- `class ThrottleMiddleware implements MiddlewareInterface`

## Özellikler
- `int $maxAttempts`: İzin verilen maksimum deneme sayısı.
- `int $decaySeconds`: Sınır için zaman penceresi.
- `string $limiterName`: Adlandırılmış bir sınırlayıcı kullanılıyorsa sınırlayıcının adı.

## Metotlar
- `__construct(string $param = '60,60')`: Ara yazılımı, virgülle ayrılmış bir "maksimum,bozulma" dizgisi veya adlandırılmış bir sınırlayıcı ile başlatır.
- `handle(Request $request, \Closure $next): mixed`: İsteği işler, hız sınırını kontrol eder ve isteğin devam etmesine izin verir veya 429 Too Many Requests yanıtı döndürür.

## Dahili İş Akışı
1. `RateLimiter` facade kökünü çözer.
2. Sınırlayıcı adına veya istemcinin IP'si ile birleştirilmiş rota özel anahtarına (`throttle:method:path`) göre önbellek anahtarını belirler.
3. `RateLimiter::hit` kullanarak vuruş sayacını artırır.
4. Eğer `attempts > maxAttempts` ise:
    - İstek JSON bekliyorsa, `Retry-After` başlığı ve JSON hatası ile 429 yanıtı döndürür.
    - Aksi takdirde, bir hata mesajı ekler ve geri yönlendirir.
5. Sınırlar dahilindeyse, bir sonraki ara yazılımı/denetleyiciyi çağırır.

## Bağımlılıklar
- `Core\RateLimiter`

## Çapraz Referanslar
- `Core\Contracts\MiddlewareInterface` (Uygular)
- `Core\Facades\RateLimiter` (Kullanır)
- `Core\Request` (Kullanır)
- `Core\TerminateException` (Fırlatır)

## Kaynak Referansları
- `app/Middleware/ThrottleMiddleware.php:1-108`
