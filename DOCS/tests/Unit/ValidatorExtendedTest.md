# Dosya Raporu: tests/Unit/ValidatorExtendedTest.php

## Amaç
Doğrulayıcının (validator) kural seti için genişletilmiş birim (unit) testler.

## Genel Bakış
Temellerin ötesindeki `Core\Validator` kurallarını doğrular: `sometimes`, `confirmed`, `same`, `in`, `not_in`, `alpha`, `alpha_num`, `url`, `regex`, `digits`, `digits_between`, `date`, `before`, `after`, dizi-değer tür reddi, özel mesajlar, zorunlu olmayan kuralların boş değerleri atlaması, birleştirilmiş kurallar, `passes` ve dizi formundaki kurallar.

## Dosya Konumu
`tests/Unit/ValidatorExtendedTest.php`

## İsim Uzayı
`Tests\Unit`

## Sınıflar
- `class ValidatorExtendedTest extends Tests\TestCase`

## Test Edilen Konu
- `Core\Validator`

## Test Metotları
`sometimes` `:22-31`; `confirmed` `:42-51`; `same` `:62-71`; `in`/`not_in` `:82-111`; `alpha`/`alpha_num` `:122-142`; `url` `:150-156`; `regex` `:164-170`; `digits`/`digits_between` `:178-198`; `date`/`before`/`after` `:206-240`; dizi reddi `:248`; özel mesajlar `:259`; boş değer atlama `:274`; birleştirilmiş kurallar `:285`; `passes` `:299`; dizi formundaki kurallar `:313`.

## Çapraz Referanslar
- **Test Eder:** `Core\Validator` (bkz. `DOCS/core/Validator.md`)

## Kaynak Referansları
- `tests/Unit/ValidatorExtendedTest.php:1-322`
