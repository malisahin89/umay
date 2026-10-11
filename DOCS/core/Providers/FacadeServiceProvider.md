# Dosya Raporu: core/Providers/FacadeServiceProvider.php

## Amaç
Facade takma adlarını kaydetmek için servis sağlayıcı.

## Genel Bakış
Tüm framework facade'ları (örneğin, `Cache`, `Auth`, `DB`) için kısa global isimleri (takma adlar), `config/app.php` takma adlar listesine kaydeder.

## Dosya Konumu
`core/Providers/FacadeServiceProvider.php`

## Ad Alanı
`Core\Providers`

## Sınıflar
- `class FacadeServiceProvider extends ServiceProvider`

## Metotlar
- `register(): void`: `Facade` mantığını konteynere bağlar.
- `boot(): void`: Yapılandırılmış takma adlar üzerinde döner ve onları küresel olarak kaydeder.

## Bağımlılıklar
- `Core\ServiceProvider` (Genişletir)
- `Core\Support\Facade` (Kullanır)

## Kaynak Referansları
- `core/Providers/FacadeServiceProvider.php:1-60`
