# Dosya Raporu: core/Response.php

## Amaç
Kullanımdan kaldırılmış (Deprecated) yanıt aracı.

## Genel Bakış
Yaygın yanıtları (JSON, yönlendirmeler, 404, 403, 500) göndermek için statik metotlar sağlar. Bu metotlar artık `ResponseBuilder` ve `abort()` yardımcısı lehine kullanımdan kaldırılmıştır.

## Dosya Konumu
`core/Response.php`

## Ad Alanı
`Core`

## Sınıflar
- `class Response`

## Metotlar
- `json(mixed $data, int $status = 200): void`: Kullanımdan kaldırıldı. `response()->json()` kullanın.
- `redirect(string $url, int $status = 302): void`: Kullanımdan kaldırıldı. `\Core\Redirect::to()` kullanın.
- `notFound(string $message = '404 - Sayfa Bulunamadı'): void`: Kullanımdan kaldırıldı. `abort(404)` kullanın.
- `forbidden(string $message = '403 - Erişim Yasak'): void`: Kullanımdan kaldırıldı. `abort(403)` kullanın.
- `serverError(string $message = '500 - Sunucu Hatası'): void`: Kullanımdan kaldırıldı. `abort(500)` kullanın.

## Kaynak Referansları
- `core/Response.php:1-66`
