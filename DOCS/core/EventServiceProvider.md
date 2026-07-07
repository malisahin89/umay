# Dosya Raporu: core/EventServiceProvider.php

## Amaç
Olay dinleyicilerini (event listeners) kaydetmek için temel sınıf.

## Genel Bakış
Olay-dinleyici eşlemelerini tanımlamak için bir yapı sağlar. Uygulama düzeyindeki olay sağlayıcıları bu sınıfı genişletir ve `$listen` özelliğini geçersiz kılar.

## Dosya Konumu
`core/EventServiceProvider.php`

## Ad Alanı
`Core`

## İçe Aktarmalar
- `Core\Events\Dispatcher`

## Sınıflar
- `abstract class EventServiceProvider extends ServiceProvider`

## Özellikler
- `array $listen`: Olay sınıflarının dinleyici sınıfları dizileriyle eşleşmesi.

## Metotlar
- `register(): void`: `Dispatcher` singleton'ını konteynere bağlar.
- `boot(): void`: Tanımlanan `$listen` eşlemesini `Dispatcher`'a kaydeder.

## Bağımlılıklar
- `Core\ServiceProvider` (Genişletir)
- `Core\Events\Dispatcher` (Kullanır)

## Kaynak Referansları
- `core/EventServiceProvider.php:1-48`
