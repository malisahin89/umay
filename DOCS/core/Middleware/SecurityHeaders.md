# Dosya Raporu: core/Middleware/SecurityHeaders.php

## Amaç
Güvenlik odaklı HTTP başlık middleware'i.

## Genel Bakış
Uygulamayı yaygın web güvenlik açıklarından korumak için her yanıta temel güvenlik başlıklarını (örneğin, `X-Content-Type-Options`, `X-Frame-Options`, `Content-Security-Policy`) enjekte eder.

## Dosya Konumu
`core/Middleware/SecurityHeaders.php`

## Ad Alanı
`Core\Middleware`

## Sınıflar
- `class SecurityHeaders implements MiddlewareInterface`

## Metotlar
- `handle(Request $request, \Closure $next): mixed`:
    1. `Csp::reset()` kullanarak taze bir CSP nonce oluşturur.
    2. Çeşitli güvenlik başlıklarını ayarlar.
    3. Yapılandırılmışsa yanıtın HTTPS üzerinden gönderildiğinden emin olur.

## Bağımlılıklar
- `Core\Contracts\MiddlewareInterface` (Uygular)
- `Core\Csp` (Kullanır)

## Kaynak Referansları
- `core/Middleware/SecurityHeaders.php:1-110`
