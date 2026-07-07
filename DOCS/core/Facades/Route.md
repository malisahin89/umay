# Dosya Raporu: core/Facades/Route.php

## Amaç
`Core\Route` sınıfı için statik proxy.

## Genel Bakış
`Core\Route` sınıfına statik bir arayüz sağlamak için Facade desenini uygular. `Route` kayıt için statik mimari kullandığından, facade öncelikle bir tutarlılık katmanı görevi görür.

## Dosya Konumu
`core/Facades/Route.php`

## Ad Alanı
`Core\Facades`

## Sınıflar
- `class Route extends Facade`

## Metotlar
- `getFacadeRoot()`: `Route::class` döndürür.

## Bağımlılıklar
- `Core\Support\Facade` (Genişletir)
- `Core\Route` (Çözümlenen kök)

## Kaynak Referansları
- `core/Facades/Route.php:1-20`
