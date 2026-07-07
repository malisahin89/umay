# Dosya Raporu: core/Profiler/Contracts/DataCollectorInterface.php

## Amaç
Profiler veri toplayıcıları için arayüz.

## Genel Bakış
Belirli türde tanısal veriler toplayan sınıflar için kontratı tanımlar. `Profiler`, isteğin kapsamlı bir görünümünü elde etmek için birden fazla toplayıcıyı kullanabilir.

## Dosya Konumu
`core/Profiler/Contracts/DataCollectorInterface.php`

## Ad Alanı
`Core\Profiler\Contracts`

## Arayüzler
- `interface DataCollectorInterface`

## Metotlar
- `collect(): array`: Tanısal verileri toplar ve bir dizi olarak döndürür.

## Kaynak Referansları
- `core/Profiler/Contracts/DataCollectorInterface.php:1-15`
