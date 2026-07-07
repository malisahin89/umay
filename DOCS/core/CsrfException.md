# Dosya Raporu: core/CsrfException.php

## Amaç
CSRF hataları için özel istisna.

## Genel Bakış
Bir CSRF token'ı eksik veya geçersiz olduğunda fırlatılır. `ExceptionHandler` tarafından yakalanarak 419 yanıtı döndürülür.

## Dosya Konumu
`core/CsrfException.php`

## Ad Alanı
`Core`

## Sınıflar
- `class CsrfException extends \Exception`

## Kaynak Referansları
- `core/CsrfException.php:1-10`
