# Dosya Raporu: core/RedirectException.php

## Amaç
Bir yönlendirmeden sonra istek akışını sonlandırmak için kullanılan Exception.

## Genel Bakış
`Location` başlığı gönderildikten sonra mevcut yürütme yolunu durdurmak için `ExceptionHandler` tarafından yakalanan (veya görmezden gelinen) özel bir exception'dır.

## Dosya Konumu
`core/RedirectException.php`

## Ad Alanı
`Core`

## Sınıflar
- `class RedirectException extends TerminateException`

## Kaynak Referansları
- `core/RedirectException.php:1-11`
