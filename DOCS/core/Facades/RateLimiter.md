# Dosya Raporu: core/Facades/RateLimiter.php

## Amaç
`Core\RateLimiter` örneği için statik proxy.

## Genel Bakış
`Core\RateLimiter` sınıfına statik bir arayüz sağlamak için Facade desenini uygular.

## Dosya Konumu
`core/Facades/RateLimiter.php`

## Ad Alanı
`Core\Facades`

## Sınıflar
- `class RateLimiter extends Facade`

## Metotlar
- `getFacadeRoot()`: `RateLimiter::class` döndürür.

## Bağımlılıklar
- `Core\Support\Facade` (Genişletir)
- `Core\RateLimiter` (Çözümlenen kök)

## Kaynak Referansları
- `core/Facades/RateLimiter.php:1-20`
