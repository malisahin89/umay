# Dosya Raporu: phpstan.neon

## Amaç
PHPStan statik analiz yapılandırması.

## Genel Bakış
PHPStan analiz seviyesini yapılandırır ve hangi yolların analiz edileceğini veya hariç tutulacağını belirtir.

## Dosya Konumu
`phpstan.neon`

## Yapılandırma
- `level`: max
- `paths`: `core`, `app`
- `excludePaths`: `storage/*`, `vendor/*`
- `includes`: `phpstan-baseline.neon`

## Kaynak Referansları
- `phpstan.neon:1-11`
