# Dosya Raporu: core/Cache.php

## Amaç
HMAC bütünlüğü ve TTL içeren dosya tabanlı önbellek sistemi.

## Genel Bakış
Dosya sistemini kullanarak basit bir anahtar-değer deposu sağlar. HMAC imzalarını kullanarak veri bütünlüğünü sağlar ve sona erme sürelerini (TTL) destekler. Yarış durumu (race condition) olmadan güncellemeler için `atomic()` metodunu içerir.

## Dosya Konumu
`core/Cache.php`

## Ad Alanı
`Core`

## Özellikler
- `string $cachePath`: Önbellek dosyalarının saklandığı dizin.
- `string $prefix`: Karma işleminden önce anahtarlara eklenen ön ek.
- `int $defaultTtl`: Saniye cinsinden varsayılan sona erme süresi.
- `?string $appKey`: HMAC imzalama için kullanılan gizli anahtar.

## Metotlar
- `get(string $key, mixed $default = null): mixed`: HMAC ve TTL'yi doğrulayarak önbellekten bir değer alır.
- `set(string $key, mixed $value, ?int $ttl = null): void`: Atomik yeniden adlandırma kullanarak önbelleğe bir değer saklar.
- `atomic(string $key, callable $mutator, ?int $ttl = null): mixed`: Süreçler arası dosya kilitlerini kullanarak atomik bir oku-değiştir-yaz işlemi gerçekleştirir.
- `remember(string $key, int $ttl, callable $callback): mixed`: Bir değeri alır veya bir geri çağırma fonksiyonunu çalıştırıp sonucu önbelleğe alır.
- `forget(string $key): void`: Belirli bir önbellek girişini siler.
- `flush(): void`: Tüm önbellek dosyalarını ve kilit/geçici dosyaları temizler.
- `pull(string $key, mixed $default = null): mixed`: Bir önbellek girişini alır ve ardından siler.
- `has(string $key): bool`: Bir önbellek girişinin var olduğunu ve süresinin dolmadığını kontrol eder.

## Dahili İş Akışı
- `filename()`: Dosya adı için ön ekli anahtarın SHA256 karmasını hesaplar.
- `encode()`: Değeri ve sona erme tarihini JSON ile kodlar, ardından bir HMAC imzası ekler.
- `decode()`: HMAC imzasını doğrular ve mevcut zamanın sona erme tarihinden önce olup olmadığını kontrol eder.

## Bağımlılıklar
- `Core\DebugBar` (İsteğe bağlı profilleme)

## Kaynak Referansları
- `core/Cache.php:1-319`
