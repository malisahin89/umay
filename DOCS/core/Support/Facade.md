# Dosya Raporu: core/Support/Facade.php

## Amaç
Tüm framework Facade'ları için temel sınıf.

## Genel Bakış
Alt katmandaki servisi `Container`'dan çözen ve istenen metodu üzerinde çağıran statik bir `__callStatic` metodu sağlayarak Facade desenini uygular.

## Dosya Konumu
`core/Support/Facade.php`

## Ad Alanı
`Core\Support`

## Sınıflar
- `class Facade`

## Metotlar
- `getFacadeRoot(): mixed`: Proxy'lenen servisin sınıf adını döndüren soyut metot.
- `__callStatic(string $method, array $args): mixed`: Statik çağrıları yakalar, kök servisi konteynerdan çözer ve metodu yürütür.

## Bağımlılıklar
- `Core\Container` (Kullanır)

## Kaynak Referansları
- `core/Support/Facade.php:1-50`
