# Dosya Raporu: config/profiler.php

## Amaç
Profiler yapılandırması.

## Genel Bakış
Uygulama profiler'ının davranışını kontrol eder; etkin olup olmadığını, verilerin nereye saklandığını ve kimlerin erişebileceğini belirler.

## Dosya Konumu
`config/profiler.php`

## Yapılandırma
- `enabled`: `PROFILER_ENABLED` veya `APP_DEBUG`'dan gelen boolean değer.
- `storage_path`: `storage/profiler` yolu (`BASE_PATH`'e göre göreceli).
- `ttl`: `PROFILER_TTL`'den gelen saniye cinsinden profil ömrü (varsayılan: 7200).
- `max_entries`: `PROFILER_MAX_ENTRIES`'den gelen saklanacak maksimum profil sayısı (varsayılan: 200).
- `ip_whitelist`: `PROFILER_IP_WHITELIST`'den gelen, profiler'a erişebilen IP'ler (varsayılan: '127.0.0.1,::1').

## Kaynak Referansları
- `config/profiler.php:1-49`
