# Dosya Raporu: core/Facades/View.php

## Amaç
`Core\View` örneği için statik proxy.

## Genel Bakış
`Core\View` sınıfına statik bir arayüz sağlamak için Facade desenini uygular.

## Dosya Konumu
`core/Facades/View.php`

## Ad Alanı
`Core\Facades`

## Sınıflar
- `class View extends Facade`

## Metotlar
- `getFacadeRoot()`: `View::class` döndürür.
- `@method static void share(string|array $key, mixed $value = null)`: Küresel veri paylaşımı için statik proxy.

## Bağımlılıklar
- `Core\Support\Facade` (Genişletir)
- `Core\View` (Çözümlenen kök)

## Kaynak Referansları
- `core/Facades/View.php:1-20`
