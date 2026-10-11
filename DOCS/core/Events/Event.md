# Dosya Raporu: core/Events/Event.php

## Amaç
Tüm uygulama olayları için temel sınıf.

## Genel Bakış
Tüm olaylar için temel işlevsellik sağlar; en önemlisi, olayın sonraki dinleyicilere yayılmasını durdurma yeteneğidir.

## Dosya Konumu
`core/Events/Event.php`

## Ad Alanı
`Core\Events`

## Sınıflar
- `abstract class Event`

## Metotlar
- `stopPropagation(): void`: Olayı durduruldu olarak işaretler ve sonraki dinleyicilerin yürütülmesini engeller.
- `isPropagationStopped(): bool`: Yayılımın durdurulup durdurulmadığını döndürür.

## Kaynak Referansları
- `core/Events/Event.php:1-32`
