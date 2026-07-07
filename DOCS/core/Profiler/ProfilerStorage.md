# Dosya Raporu: core/Profiler/ProfilerStorage.php

## Amaç
Profiler verileri için kalıcılık katmanı.

## Genel Bakış
`storage/profiler/` dizinindeki profiler JSON dosyalarının kaydedilmesini, yüklenmesini ve otomatik olarak temizlenmesini yönetir.

## Dosya Konumu
`core/Profiler/ProfilerStorage.php`

## Ad Alanı
`Core\Profiler`

## Sınıflar
- `class ProfilerStorage`

## Metotlar
- `save(string $token, array $data): void`: Profil verilerini bir JSON dosyasına yazar.
- `load(string $token): ?array`: Bir profil JSON dosyasını okur ve çözer.
- `cleanup(): void`: TTL'yi aşan veya `max_entries` sınırına ulaşılan profilleri siler.
- `listRecent(): array`: En son profillerin bir listesini döndürür.

## Bağımlılıklar
- `Core\Profiler\Profiler` (Kullanır)

## Kaynak Referansları
- `core/Profiler/ProfilerStorage.php:1-110`
