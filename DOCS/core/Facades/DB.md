# Dosya Raporu: core/Facades/DB.php

## Amaç
`Core\Database` örneği için statik proxy.

## Genel Bakış
`Core\Database` sınıfına statik bir arayüz sağlamak için Facade desenini uygular.

## Dosya Konumu
`core/Facades/DB.php`

## Ad Alanı
`Core\Facades`

## Sınıflar
- `class DB extends Facade`

## Metotlar
- `getFacadeRoot()`: `Database::class` döndürür.

## Bağımlılıklar
- `Core\Support\Facade` (Genişletir)
- `Core\Database` (Çözümlenen kök)

## Kaynak Referansları
- `core/Facades/DB.php:1-20`
