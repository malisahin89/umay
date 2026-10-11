# Dosya Raporu: core/Facades/Auth.php

## Amaç
`Core\Auth` örneği için statik proxy.

## Genel Bakış
Kapsayıcıdan (container) çözümlenen `Core\Auth` sınıfına statik bir arayüz sağlamak için Facade desenini uygular.

## Dosya Konumu
`core/Facades/Auth.php`

## Ad Alanı
`Core\Facades`

## Sınıflar
- `class Auth extends Facade`

## Metotlar
- `getFacadeRoot()`: `Auth::class` döndürür.

## Bağımlılıklar
- `Core\Support\Facade` (Genişletir)
- `Core\Auth` (Çözümlenen kök)

## Kaynak Referansları
- `core/Facades/Auth.php:1-30`
