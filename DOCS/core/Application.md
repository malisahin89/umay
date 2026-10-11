# Dosya Raporu: core/Application.php

## Amaç
Framework önyükleme (bootstrap) orkestratörü.

## Genel Bakış
ServiceProvider kaydı, başlatma ve istisna yönetimi dahil olmak üzere uygulama yaşam döngüsünü yöneten merkezi sınıftır. `Container` üzerinde konumlanır.

## Dosya Konumu
`core/Application.php`

## Ad Alanı
`Core`

## Sınıflar
- `class Application`

## Özellikler
- `static ?self $instance`: Uygulamanın singleton örneği.
- `ServiceProvider[] $providers`: Kaydedilmiş servis sağlayıcıların listesi.
- `bool $booted`: Uygulamanın başlatılıp başlatılmadığını belirten bayrak.
- `Container $container`: Bağımlılık enjeksiyonu konteyneri.

## Metotlar
- `getInstance(): self`: Singleton örneğini döndürür.
- `container(): Container`: Konteyner örneğini döndürür.
- `make(string $abstract): mixed`: Bir sınıfı konteynerdan çözmek için kısa yol.
- `instance(string $abstract, mixed $concrete): void`: Belirli bir örneği konteynere bağlar.
- `singleton(string $abstract, callable|string $concrete): void`: Bir singleton'ı konteynere bağlar.
- `register(string $providerClass): static`: Bir `ServiceProvider` kaydeder ve onun `register()` metodunu çağırır.
- `boot(): static`: Kaydedilen tüm servis sağlayıcıların `boot()` metodunu çağırır.
- `handleException(\Throwable $e): void`: İstisna yönetimini `ExceptionHandler`'a devreder.
- `captureRequest(): static`: Mevcut HTTP isteğini yakalar ve konteynere bağlar.

## Bağımlılıklar
- `Core\Container` (Kullanır)
- `Core\ServiceProvider` (Kullanır)
- `Core\ExceptionHandler` (Kullanır)
- `Core\Request` (Kullanır)

## Kaynak Referansları
- `core/Application.php:1-166`
