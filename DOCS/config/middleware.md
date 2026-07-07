# Dosya Raporu: config/middleware.php

## Amaç
Middleware ve CORS yapılandırması.

## Genel Bakış
Middleware gruplarını ('web', 'api'), küresel middleware'leri ve CORS ayarlarını tanımlar. Ayrıca middleware isimlerinin sınıflara çözümleme sırasını belirtir.

## Dosya Konumu
`config/middleware.php`

## Yapılandırma
- `api_prefix`: `API_PREFIX`'ten gelen API rotaları öneki (varsayılan: '/api').
- `global`: Her istekte çalışan middleware dizisi (şu an boş).
- `web`: Oturum tabanlı istekler için middleware: `['RememberMe', 'SecurityHeaders', 'VerifyCsrfToken']`.
- `api`: Durumsuz istekler için middleware: `['Cors', 'throttle:60,60']`.
- `cors_origin`: `CORS_ORIGIN`'den gelen izin verilen kökenler (varsayılan: '*').
- `cors_methods`: İzin verilen HTTP metotları.
- `cors_headers`: İzin verilen HTTP başlıkları.
- `cors_credentials`: `CORS_CREDENTIALS`'tan gelen boolean değer.
- `cors_max_age`: `CORS_MAX_AGE`'den gelen saniye cinsinden değer (varsayılan: 86400).

## Middleware Çözümleme
Yönlendirici, middleware isimlerini şu şablonları kullanarak çözer:
1. `App\\Middleware\\{name}Middleware`
2. `Core\\Middleware\\{name}`

## Middleware Takma Adları
- `throttle` -> `Throttle`
- `cors` -> `Cors`

## Kaynak Referansları
- `config/middleware.php:1-186`
