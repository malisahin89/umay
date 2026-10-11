# Dosya Raporu: config/cache.php

## Amaç
Önbellek (cache) yapılandırması.

## Genel Bakış
`Core\Cache` tarafından kullanılan dosya tabanlı önbellek sistemini yapılandırır; depolama yolunu, anahtar önekini ve varsayılan TTL'yi belirtir.

## Dosya Konumu
`config/cache.php`

## Yapılandırma
- `path`: `storage/cache` yolu (`BASE_PATH`'e göre göreceli).
- `prefix`: `CACHE_PREFIX`'ten gelen önbellek anahtar öneki (varsayılan: 'umay_').
- `default_ttl`: `CACHE_TTL`'den gelen varsayılan saniye cinsinden ömür (varsayılan: 3600).

## Kaynak Referansları
- `config/cache.php:1-30`
