# Dosya Raporu: core/Middleware/VerifyCsrfToken.php

## Amaç
CSRF koruma middleware'i.

## Genel Bakış
Durum değiştiren isteklerin (POST, PUT, PATCH, DELETE), oturumda saklananla eşleşen geçerli bir CSRF token'ı içerdiğini doğrular.

## Dosya Konumu
`core/Middleware/VerifyCsrfToken.php`

## Ad Alanı
`Core\Middleware`

## Sınıflar
- `class VerifyCsrfToken implements MiddlewareInterface`

## Metotlar
- `handle(Request $request, \Closure $next): mixed`:
    1. GET, HEAD ve OPTIONS istekleri için doğrulamayı atlar.
    2. Token'ı `csrf_token` girdisinden veya `X-CSRF-TOKEN` başlığından çıkarır.
    3. Token'ı doğrulamak için `Csrf::check()` kullanır.
    4. Geçersizse, 419 durum koduyla sonuçlanan bir `CsrfException` fırlatır.

## Bağımlılıklar
- `Core\Contracts\MiddlewareInterface` (Uygular)
- `Core\Request` (Kullanır)
- `Core\Csrf` (Kullanır)

## Kaynak Referansları
- `core/Middleware/VerifyCsrfToken.php:1-90`
