# Dosya Raporu: core/Logger.php

## Amaç
Dosya tabanlı uygulama günlüklemesi.

## Genel Bakış
Uygulama olaylarını ve hatalarını `storage/logs/` altındaki günlük dosyalarına kaydeder. Günlük enjeksiyonuna karşı korumalar içerir ve gerçek zamanlı görüntüleme için `DebugBar` ile entegre çalışır.

## Dosya Konumu
`core/Logger.php`

## Ad Alanı
`Core`

## Sınıflar
- `class Logger`

## Özellikler
- `string $logPath`: Günlük dizininin yolu (`storage/logs`).

## Metotlar
- `error(string $message, array $context = []): void`: Bir hata mesajı kaydeder.
- `warning(string $message, array $context = []): void`: Bir uyarı mesajı kaydeder.
- `info(string $message, array $context = []): void`: Bilgi amaçlı bir mesaj kaydeder.
- `log(string $level, string $message, array $context = []): void`: Günlük girdisini (zaman damgası, IP ve Kullanıcı Aracısı dahil) formatlayan ve `FILE_APPEND | LOCK_EX` kullanarak günlük dosyasına ekleyen dahili metot.

## Dahili İş Akışı
1. Günlük enjeksiyonunu önlemek için mesajı ve başlıkları temizler (CR/LF kaldırma).
2. Girdiyi formatlar: `[timestamp] LEVEL: message | IP: ... | Context: ... | User-Agent: ...`.
3. `storage/logs/YYYY-MM-DD.log` dosyasına yazar.
4. `DebugBar` etkinse, günlüğü profiler'a ekler.

## Bağımlılıklar
- `Core\DebugBar` (İsteğe bağlı profilleme)

## Kaynak Referansları
- `core/Logger.php:1-73`
