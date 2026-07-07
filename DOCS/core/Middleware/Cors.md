# Dosya Raporu: core/Middleware/Cors.php

## Amaç
Kökenler Arası Kaynak Paylaşımı (CORS) middleware'i.

## Genel Bakış
`config/middleware.php` içindeki yapılandırmaya dayanarak kökenler arası istekleri izin vermek veya kısıtlamak için uygun HTTP başlıklarını ayarlar.

## Dosya Konumu
`core/Middleware/Cors.php`

## Ad Alanı
`Core\Middleware`

## Sınıflar
- `class Cors implements MiddlewareInterface`

## Metotlar
- `handle(Request $request, \Closure $next): mixed`:
    1. İstek bir `OPTIONS` (ön kontrol/preflight) isteği ise, yapılandırılmış CORS başlıkları ile hemen 204 yanıtı döndürür.
    2. Aksi takdirde, bir sonraki işleyicinin yanıtına CORS başlıklarını ekler.

## Bağımlılıklar
- `Core\Contracts\MiddlewareInterface` (Uygular)
- `Core\Request` (Kullanır)

## Kaynak Referansları
- `core/Middleware/Cors.php:1-80`
