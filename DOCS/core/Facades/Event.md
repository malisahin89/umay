# Dosya Raporu: core/Facades/Event.php

## Amaç
`Core\Events\Dispatcher` örneği için statik proxy.

## Genel Bakış
`Core\Events\Dispatcher` sınıfına statik bir arayüz sağlamak için Facade desenini uygular.

## Dosya Konumu
`core/Facades/Event.php`

## Ad Alanı
`Core\Facades`

## Sınıflar
- `class Event extends Facade`

## Metotlar
- `getFacadeRoot()`: `Dispatcher::class` döndürür.

## Bağımlılıklar
- `Core\Support\Facade` (Genişletir)
- `Core\Events\Dispatcher` (Çözümlenen kök)

## Kaynak Referansları
- `core/Facades/Event.php:1-20`
