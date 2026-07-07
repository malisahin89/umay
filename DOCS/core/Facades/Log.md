# Dosya Raporu: core/Facades/Log.php

## Amaç
`Core\Logger` örneği için statik proxy.

## Genel Bakış
`Core\Logger` sınıfına statik bir arayüz sağlamak için Facade desenini uygular.

## Dosya Konumu
`core/Facades/Log.php`

## Ad Alanı
`Core\Facades`

## Sınıflar
- `class Log extends Facade`

## Metotlar
- `getFacadeRoot()`: `Logger::class` döndürür.

## Bağımlılıklar
- `Core\Support\Facade` (Genişletir)
- `Core\Logger` (Çözümlenen kök)

## Kaynak Referansları
- `core/Facades/Log.php:1-20`
