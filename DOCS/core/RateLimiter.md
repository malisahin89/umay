# Dosya Raporu: core/RateLimiter.php

## Amaç
Önbellek destekli hız sınırlama (rate limiting) sistemi.

## Genel Bakış
Belirli bir anahtar (genellikle IP + rota) için istek denemelerini takip eder ve bir sınırın aşılıp aşılmadığını belirler. Uygulama genelinde tanımlanabilen "isimlendirilmiş sınırlayıcıları" destekler.

## Dosya Konumu
`core/RateLimiter.php`

## Ad Alanı
`Core`

## Sınıflar
- `class RateLimiter`

## Özellikler
- `array $limiters`: Maksimum deneme sayısı ve azalma süresi ile isimlendirilmiş sınırlayıcıların kayıt defteri.
- `Cache $cache`: Depolama için kullanılan önbellek örneği.

## Metotlar
- `for(string $name, int $maxAttempts, int $decaySeconds = 60): void`: İsimlendirilmiş bir sınırlayıcı tanımlar.
- `limiter(string $name): ?array`: İsimlendirilmiş bir sınırlayıcının yapılandırmasını alır.
- `tooManyAttempts(string $key, int $maxAttempts, int $decaySeconds = 60): bool`: Bir anahtar için denemelerin sınırı aşıp aşmadığını kontrol eder.
- `hit(string $key, int $decaySeconds = 60): int`: Atomik bir işlem kullanarak bir anahtar için vuruş sayacını artırır.
- `clear(string $key): void`: Bir anahtar için sayacı sıfırlar.
- `attempts(string $key): int`: Mevcut deneme sayısını döndürür.
- `remaining(string $key, int $maxAttempts): int`: Sınıra ulaşmadan önce kalan deneme sayısını döndürür.
- `availableIn(string $key, int $decaySeconds): int`: Sınır sıfırlanana kadar kalan saniyeyi döndürür.
- `key(string $prefix, ?string $suffix = null): string`: Karma bir önbellek anahtarı oluşturur.

## Dahili İş Akışı
- `hit()`: Sayacı artırırken eşzamanlı isteklerin yarış durumuna (race condition) neden olmamasını sağlamak için `Cache::atomic()` kullanır.

## Bağımlılıklar
- `Core\Cache` (Kullanır)
- `Core\Container` (Kullanır)

## Kaynak Referansları
- `core/RateLimiter.php:1-155`
