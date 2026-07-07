# Dosya Raporu: core/Contracts/MiddlewareInterface.php

## Amaç
Middleware sınıfları için arayüz.

## Genel Bakış
Tüm middleware'lerin `handle` metodunu uygulamasını sağlar; bu sayede istekler kontrolcüye ulaşmadan önce bir hat (pipeline) üzerinde zincirlenebilir.

## Dosya Konumu
`core/Contracts/MiddlewareInterface.php`

## Ad Alanı
`Core\Contracts`

## Arayüzler
- `interface MiddlewareInterface`

## Metotlar
- `handle(Request $request, \Closure $next): mixed`: İsteği işler ve zincirdeki bir sonraki işleyicinin sonucunu döndürür.

## Bağımlılıklar
- `Core\Request` (Kullanır)

## Kaynak Referansları
- `core/Contracts/MiddlewareInterface.php:1-35`
