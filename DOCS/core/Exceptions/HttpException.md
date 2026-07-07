# Dosya Raporu: core/Exceptions/HttpException.php

## Amaç
HTTP'ye özgü hatalar için özel istisna.

## Genel Bakış
Uygulamanın herhangi bir yerinden belirli HTTP yanıtlarını (örneğin, 403, 404, 500) tetiklemek için kullanılır. `ExceptionHandler` tarafından yakalanarak uygun hata görünümü oluşturulur.

## Dosya Konumu
`core/Exceptions/HttpException.php`

## Ad Alanı
`Core\Exceptions`

## Sınıflar
- `class HttpException extends \RuntimeException`

## Metotlar
- `getStatusCode(): int`: İstisna ile ilişkili HTTP durum kodunu döndürür.

## Kaynak Referansları
- `core/Exceptions/HttpException.php:1-25`
