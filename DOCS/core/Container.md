# Dosya Raporu: core/Container.php

## Amaç
Bağımlılık Enjeksiyonu (DI) Konteyneri.

## Genel Bakış
Sınıf bağımlılıklarını yöneten PSR-11 uyumlu bir konteynerdır. Singleton bağlamalarını, fabrika bağlamalarını ve PHP Reflection aracılığıyla otomatik "auto-wiring" özelliğini destekler.

## Dosya Konumu
`core/Container.php`

## Ad Alanı
`Core`

## İçe Aktarmalar
- `Core\Exceptions\ContainerException`
- `Core\Exceptions\EntryNotFoundException`
- `Psr\Container\ContainerInterface`

## Sınıflar
- `class Container implements ContainerInterface`

## Özellikler
- `static ?self $instance`: Konteynerin singleton örneği.
- `array $bindings`: Soyut-somut eşleme kayıt defteri.
- `array $instances`: Singleton örnekleri için önbellek.
- `array $resolving`: Döngüsel bağımlılık tespiti için izleyici.
- `array $reflectionCache`: `ReflectionClass` nesneleri için önbellek.

## Metotlar
- `getInstance(): static`: Singleton örneğini döndürür.
- `singleton(string $abstract, callable|string $concrete): void`: Bir sınıfı singleton olarak bağlar.
- `bind(string $abstract, callable|string $concrete): void`: Bir sınıfı fabrika olarak bağlar.
- `instance(string $abstract, mixed $instance): void`: Belirli bir nesne örneğini bağlar.
- `make(string $abstract): mixed`: İstenen sınıfın örneğini çözer ve döndürür.
- `get(string $id): mixed`: Kaydedilmiş bir girdiyi almak için PSR-11 metodu.
- `has(string $abstract): bool`: Bir girdinin kaydedilip kaydedilmediğini kontrol eder.
- `build(string $concrete): mixed`: Bağımlılıkları otomatik olarak bağlamak için Reflection kullanan dahili metot.

## Dahili İş Akışı (Auto-wiring)
1. Bir kurucu (constructor) olup olmadığını kontrol eder.
2. Her parametre için türü belirler.
3. Tür bir sınıf/arayüz ise, onu çözmek için özyinelemeli olarak `make()` çağrılır.
4. Birleşim Türlerini (Union Types - PHP 8.0+) destekler ve çözülebilen ilk türü seçer.
5. Mevcutsa varsayılan değerlere geri döner.

## Bağımlılıklar
- `Psr\Container\ContainerInterface` (Uygular)

## Kaynak Referansları
- `core/Container.php:1-197`
