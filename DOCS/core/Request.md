# Dosya Raporu: core/Request.php

## Amaç
HTTP İstek (Request) soyutlaması.

## Genel Bakış
Süper küresel değişkenlere (`$_GET`, `$_POST`, `$_FILES`, `$_SERVER`, `$_COOKIE`) erişmek için birleşik bir API yakalar ve sağlar. API istekleri için otomatik JSON gövde ayrıştırmayı ve hassas girdiler için güvenlik filtrelerini içerir.

## Dosya Konumu
`core/Request.php`

## Ad Alanı
`Core`

## Sınıflar
- `class Request`

## Özellikler
- `array $get`, `$post`, `$files`, `$server`, `$cookies`: Süper küresel değişkenlerin anlık görüntüleri.
- `array $routeParams`: Rota deseninden yakalanan parametreler (örn. `{id}`).

## Metotlar
- `capture(): static`: Mevcut süper küresel değişkenlerden bir `Request` örneği oluşturmak için fabrika metodu.
- `input(string $key, mixed $default = null): mixed`: POST (öncelikli) veya GET üzerinden bir değer alır.
- `all(): array`: GET ve POST verilerini birleştirir.
- `only(array $keys): array` / `except(array $keys): array`: Girdi verilerini filtreler.
- `has(string $key): bool`: Girdide bir anahtarın mevcut olup olmadığını kontrol eder.
- `filled(string $key): bool`: Bir anahtarın mevcut olup olmadığını ve boş bir dize olmadığını kontrol eder.
- `file(string $key): ?array`: Yüklenen bir dosyayı alır.
- `method(): string`: HTTP metodunu döndürür (GET, POST, vb.).
- `isAjax(): bool`: İsteğin bir AJAX isteği olup olmadığını kontrol eder.
- `header(string $key, ?string $default = null): ?string`: Belirli SAPI davranışları (örn. `Authorization` başlığı) için yedeklerle birlikte bir HTTP başlığı alır.
- `ip(): string`: `getRealIP()` aracılığıyla istemcinin gerçek IP adresini döndürür.
- `path(): string`: İstenen URI yolunu döndürür.
- `fullUrl(): string`: İsteğin tam URL'sini döndürür.
- `route(string $key, mixed $default = null): mixed`: Bir rota parametresini döndürür.
- `bearerToken(): ?string`: `Authorization` başlığından Bearer token'ı çıkarır.
- `expectsJson(): bool`: İsteğin bir JSON yanıtı bekleyip beklemediğini belirler.
- `validate(array $rules): ?array`: İstek verilerini `Core\Validator` kullanarak doğrular.

## Dahili İş Akışı
- **JSON Ayrıştırma**: Kurucu metotta (constructor), eğer `Content-Type` `application/json` ise, `php://input`'u okur (8MB'a kadar) ve çözülen JSON'ı `$post` dizisine ekler.
- **Hassas Filtreleme**: `exceptSensitive()` metodu, şifre, token, secret gibi anahtarları, düz metin kimlik bilgilerinin saklanmasını önlemek için session'a aktarılmadan önce girdiden kaldırır.

## Kaynak Referansları
- `core/Request.php:1-317`
