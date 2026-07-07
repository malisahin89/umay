# Dosya Raporu: core/DebugBar.php

## Amaç
Uygulama Profileri için Facade.

## Genel Bakış
Bir istek sırasında tanısal bilgileri (sorgular, günlükler, görünümler vb.) toplamak için statik bir API sağlar. Tüm çağrılar `Core\Profiler\Profiler` sınıfına yönlendirilir.

## Dosya Konumu
`core/DebugBar.php`

## Ad Alanı
`Core`

## İçe Aktarmalar
- `Core\Profiler\Profiler`

## Sınıflar
- `class DebugBar`

## Metotlar
- `init(): void`: Profiler'ı başlatır.
- `isEnabled(): bool`: Profiler'ın aktif olup olmadığını kontrol eder.
- `startMeasure(string $name, ?float $start = null): void`: Belirli bir işlem için zamanlayıcıyı başlatır.
- `stopMeasure(string $name): void`: Zamanlayıcıyı durdurur.
- `addQuery(array $q): void`: Bir veritabanı sorgusu kaydeder.
- `addLog(string $level, string $message, array $context = []): void`: Bir günlük girdisi kaydeder.
- `addView(string $template, array $data = []): void`: İşlenen bir görünümü kaydeder.
- `addEvent(string $eventClass, mixed $payload = null): void`: Dağıtılan bir olayı kaydeder.
- `addCacheOp(string $type, string $key, bool $hit = false): void`: Bir önbellek işlemi kaydeder.
- `addMail(array $mail): void`: Gönderilen bir e-postayı kaydeder.
- `setRoute(array $info): void`: Mevcut rotayı kaydeder.
- `addException(\Throwable $e): void`: Yakalanan bir istisnayı kaydeder.
- `addMiddlewareTiming(string $name, float $ms): void`: Middleware yürütme süresini kaydeder.
- `render(): string`: Hata ayıklama araç çubuğunun HTML'ini döndürür.
- `findCaller(): array`: Bir işlemi tetikleyen uygulama kodunu bulmak için backtrace analizini yapar.

## Bağımlılıklar
- `Core\Profiler\Profiler` (Yönlendirir)

## Kaynak Referansları
- `core/DebugBar.php:1-104`
