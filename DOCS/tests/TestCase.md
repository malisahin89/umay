# Dosya Raporu: tests/TestCase.php

## Amaç
Framework farkındalığına sahip kurulum ve yardımcılar ile PHPUnit'in `TestCase` sınıfını genişleten proje taban test durumu.

## Genel Bakış
Tüm testler için soyut taban sınıfı. Her testten önce oturumu, süper globalleri, konteynere bağlı `Auth` önbelleğini ve olay dağıtıcısını (event dispatcher) sıfırlar. İstek oluşturma ve doğrulama yardımcıları sağlar.

## Dosya Konumu
`tests/TestCase.php`

## İsim Uzayı
`Tests`

## İçe Aktarmalar
- `App\Models\User`
- `Core\Auth`, `Core\Container`, `Core\Events\Dispatcher`, `Core\Request`
- `PHPUnit\Framework\TestCase as BaseTestCase`

## Sınıflar
- `abstract class TestCase extends BaseTestCase`

## Metotlar
- `setUp(): void` — dizi tabanlı bir oturum başlatır, `$_SESSION/$_POST/$_GET/$_FILES/$_COOKIE` değerlerini sıfırlar, `Auth` önbelleğini temizler, `Dispatcher`'ı boşaltır.
- `tearDown(): void` — `Dispatcher`'ı boşaltır, `Auth` önbelleğini temizler.
- `makeRequest(string $method, string $uri, array $data, array $headers): Request` — `Request::capture()` aracılığıyla simüle edilmiş süper globallerden bir `Request` oluşturur.
- `actingAs(User $user): static` — `$_SESSION['user_id']` değerini ayarlar ve `Auth` önbelleğini temizler.
- `withSession(array $data): static` — verileri `$_SESSION` ile birleştirir.
- `assertSessionHas(string $key, mixed $value = null): void`
- `assertSessionMissing(string $key): void`
- `assertFlash(string $type, ?string $message = null): void`

## Çapraz Referanslar
- **Genişletenler:** `tests/Unit/` ve `tests/Feature/` altındaki her test sınıfı.
- **Kullananlar:** `Core\Auth`, `Core\Container`, `Core\Events\Dispatcher`, `Core\Request`, `App\Models\User`.

## Kaynak Referansları
- `tests/TestCase.php:1-166`
