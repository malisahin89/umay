# Dosya Raporu: core/Profiler/Profiler.php

## Amaç
Uygulama performansı ve tanı profiler'ı.

## Genel Bakış
Bir istek sırasında veritabanı sorguları, işlenen görünümler, olaylar, günlükler ve yürütme zamanlaması dahil olmak üzere geniş bir metrik yelpazesini toplar. Bu veriler `storage/profiler/` altında JSON dosyaları olarak saklanır ve Debug Araç Çubuğu aracılığıyla görüntülenebilir.

## Dosya Konumu
`core/Profiler/Profiler.php`

## Ad Alanı
`Core\Profiler`

## Sınıflar
- `class Profiler`

## Özellikler
- `array $data`: Mevcut istek için toplanan metrikler.
- `ProfilerStorage $storage`: Profilleri kalıcı hale getirmek için depolama işleyicisi.

## Metotlar
- `init(): void`: Profiler'ı başlatır.
- `isEnabled(): bool`: Profillemenin aktif olup olmadığını kontrol eder.
- `startMeasure(string $name): void`: Belirli bir kod bloğu için zamanlayıcıyı başlatır.
- `stopMeasure(string $name): void`: Zamanlayıcıyı durdurur ve geçen süreyi kaydeder.
- `addQuery(array $q): void`: Bir veritabanı sorgusu kaydeder.
- `addLog(string $level, string $message, array $context = []): void`: Bir günlük girdisi kaydeder.
- `addView(string $template, array $data = []): void`: İşlenen bir görünümü kaydeder.
- `addEvent(string $eventClass, mixed $payload = null): void`: Dağıtılan bir olayı kaydeder.
- `addCacheOp(string $type, string $key, bool $hit = false): void`: Bir önbellek işlemi kaydeder.
- `addMail(array $mail): void`: Gönderilen bir e-postayı kaydeder.
- `setRoute(array $info): void`: Eşleşen rotayı kaydeder.
- `addException(\Throwable $e): void`: Yakalanan bir istisnayı kaydeder.
- `addMiddlewareTiming(string $name, float $ms): void`: Middleware yürütme süresini kaydeder.
- `finish(): void`: Toplanan verileri diske kaydeder.
- `renderToolbar(): string`: Hata ayıklama araç çubuğunun HTML'ini oluşturur.

## Dahili İş Akışı
- **Veri Toplama**: Çeşitli çekirdek bileşenler, istek yaşam döngüsü boyunca `Profiler` metotlarını çağırır.
- **Kalıcılık**: `finish()` benzersiz bir token oluşturur ve verileri `storage/profiler/{token}.json` dosyasına kaydeder.
- **Analiz**: `detectNPlusOne()` veritabanı sorgularını analiz ederek yaygın N+1 desenlerini bulur.

## Bağımlılıklar
- `Core\Profiler\ProfilerStorage` (Kullanır)
- `Core\Profiler\Contracts\DataCollectorInterface` (Kullanır)

## Kaynak Referansları
- `core/Profiler/Profiler.php:1-1581`
