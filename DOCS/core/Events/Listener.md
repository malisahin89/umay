# Dosya Raporu: core/Events/Listener.php

## Amaç
Tüm olay dinleyicileri için temel sınıf.

## Genel Bakış
Dinleyici sınıfları için kontratı tanımlar. Alt sınıflar, ilişkili olay tetiklendiğinde yürütülecek mantığı tanımlamak için `handle` metodunu uygulamalıdır.

## Dosya Konumu
`core/Events/Listener.php`

## Ad Alanı
`Core\Events`

## Sınıflar
- `abstract class Listener`

## Metotlar
- `handle(Event $event): void`: Olayı işleyen soyut metot.

## Bağımlılıklar
- `Core\Events\Event` (Kullanır)

## Kaynak Referansları
- `core/Events/Listener.php:1-29`
