# Dosya Raporu: tests/Unit/RouteExtendedTest.php

## Amaç
Yönlendirici (router) için genişletilmiş birim (unit) testler.

## Genel Bakış
`Core\Route`'u HTTP fiil kaydı (`get`/`post`/`put`/`patch`/`delete`), `match`/`any` (metotlar, ara yazılım, isim yayılımı), adlandırılmış rota URL oluşturma (parametreler, sorgu parametreleri, bilinmeyen isim geri dönüşü), ön ek grupları (iç içe geçme dahil), ara yazılım ataması ve grup kalıtımı, derlenmiş parametre regex'i (statik nokta kaçırma, isteğe bağlı parametreler), grup get/set, rota kaldırma, closure ve kök rotalar ve isteğe bağlı parametreli URL oluşturma üzerinden doğrular.

## Dosya Konumu
`tests/Unit/RouteExtendedTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class RouteExtendedTest extends Tests\TestCase`

## Test Edilen Konu
- `Core\Route`

## Test Metotları
Fiil kaydı `:45-80`; `match`/`any` `:90-146`; adlandırılmış rotalar `:148-172`; ön ek grupları `:180-190`; ara yazılım `:204-222`; derlenmiş regex `:234`, `:329-341`; grup get/set `:249`; kaldırma `:260`; closure/kök rotalar `:272-285`; isteğe bağlı parametreli URL'ler `:297-319`.

## Çapraz Referanslar
- **Test Eder:** `Core\Route` (bkz. `DOCS/core/Route.md`)

## Kaynak Referansları
- `tests/Unit/RouteExtendedTest.php:1-351`
