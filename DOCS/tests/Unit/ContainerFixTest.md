# Dosya Raporu: tests/Unit/ContainerFixTest.php

## Amaç
Konteyner uç durumları için regresyon testleri.

## Genel Bakış
`Core\Container`'ın dairesel bağımlılıklarda hata fırlattığını ve otomatik kablolama (autowiring) sırasında union tip-belirteçlerini desteklediğini doğrular. Senaryoları yürütmek için yerel fixture sınıfları (`ClassA`, `ClassB`, `FileCache`, `RedisCache`, `ServiceWithUnion`) tanımlar.

## Dosya Konumu
`tests/Unit/ContainerFixTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class ClassA`, `class ClassB` — dairesel bağımlılık fixture'ları (`:11`, `:15`)
- `class FileCache implements CacheDriver`, `class RedisCache implements CacheDriver` — union fixture'ları (`:21`, `:22`)
- `class ServiceWithUnion` — union tip-belirteç fixture'ı (`:24`)
- `class ContainerFixTest extends Tests\TestCase` (`:29`)

## Test Edilen Konu
- `Core\Container`

## Test Metotları
- `test_circular_dependency_throws_exception` — `:36`
- `test_union_type_support` — `:46`

## Çapraz Referanslar
- **Test Eder:** `Core\Container` (bkz. `DOCS/core/Container.md`)

## Kaynak Referansları
- `tests/Unit/ContainerFixTest.php:1-56`
