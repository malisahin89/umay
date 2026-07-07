# Dosya Raporu: app/Providers/EventServiceProvider.php

## Amaç
Olay dinleyicilerini (event listeners) kaydetmek için servis sağlayıcısı.

## Genel Bakış
Olaylar ve onlara karşılık gelen dinleyiciler arasındaki eşleşmeyi tanımlamak için temel `EventServiceProvider` sınıfını genişletir.

## Dosya Konumu
`app/Providers/EventServiceProvider.php`

## İsim Uzayı
`App\Providers`

## İçe Aktarmalar
- `Core\EventServiceProvider as BaseEventServiceProvider`

## Sınıflar
- `class EventServiceProvider extends BaseEventServiceProvider`

## Özellikler
- `array $listen`: Olayların dinleyici dizileriyle eşleşmesi. Şu an boş.

## Bağımlılıklar
- `Core\EventServiceProvider` (Genişletir)

## Kaynak Referansları
- `app/Providers/EventServiceProvider.php:1-25`
