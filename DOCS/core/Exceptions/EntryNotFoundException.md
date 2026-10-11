# Dosya Raporu: core/Exceptions/EntryNotFoundException.php

## Amaç
Eksik konteyner girdileri için özel istisna.

## Genel Bakış
İstenen soyutlama konteynere kaydedilmediğinde ve otomatik olarak bağlanamadığında (auto-wired) `Container::get()` tarafından fırlatılır.

## Dosya Konumu
`core/Exceptions/EntryNotFoundException.php`

## Ad Alanı
`Core\Exceptions`

## Sınıflar
- `class EntryNotFoundException extends \RuntimeException`

## Kaynak Referansları
- `core/Exceptions/EntryNotFoundException.php:1-10`
