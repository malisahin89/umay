# Dosya Raporu: stubs/test.stub

## Amaç
Bir test durumu için kod oluşturma şablonu.

## Genel Bakış
Konsol oluşturucusu tarafından, `Tests` ad alanı altında, `Tests\TestCase` sınıfını genişleten ve tek bir başarılı örnek test içeren yeni bir test sınıfı iskeleti oluşturmak için kullanılan şablon.

## Dosya Konumu
`stubs/test.stub`

## Oluşturulan Artifakt
- **Ad Alanı (Namespace):** `Tests\{{namespace}}`
- **Sınıf:** `{{ClassName}} extends Tests\TestCase`
- **İçe Aktarmalar (Imports):** `Tests\TestCase`
- **Metotlar:** `test_example(): void`

## Yer Tutucular
- `{{namespace}}` — `Tests` altındaki alt ad alanı (örneğin `Unit`, `Feature`).
- `{{ClassName}}` — oluşturulan test sınıfı adı.

## Çapraz Referanslar
- **Kullanan:** `Core\Console\Kernel::renderStub()` — `core/Console/Kernel.php:395`, `core/Console/Kernel.php:699-703`
- **Taban Sınıf:** `Tests\TestCase` (bakınız `DOCS/tests/TestCase.md`)

## Kaynak Referansları
- `stubs/test.stub:1-15`
- `core/Console/Kernel.php:395`
