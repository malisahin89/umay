# Dosya Raporu: stubs/factory.stub

## Amaç
Bir model fabrikası (factory) için kod oluşturma şablonu.

## Genel Bakış
Konsol oluşturucusu tarafından `Database\Factories` altında, `Core\Factory` sınıfını genişleten ve `$model` özelliği aracılığıyla bir hedef modele bağlı olan yeni bir fabrika iskeleti oluşturmak için kullanılan şablon.

## Dosya Konumu
`stubs/factory.stub`

## Oluşturulan Artifakt
- **Ad Alanı (Namespace):** `Database\Factories`
- **Sınıf:** `{{ClassName}} extends Core\Factory`
- **İçe Aktarmalar (Imports):** `App\Models\{{ModelClass}}`, `Core\Factory`
- **Özellikler:** `protected string $model = {{ModelClass}}::class`
- **Metotlar:** `definition(): array`

## Yer Tutucular
- `{{ClassName}}` — oluşturulan fabrika sınıfı adı.
- `{{ModelClass}}` — fabrikanın ürettiği hedef model sınıfı.

## Çapraz Referanslar
- **Kullanan:** `Core\Console\Kernel::renderStub()` — `core/Console/Kernel.php:361`, `core/Console/Kernel.php:699-703`

## Kaynak Referansları
- `stubs/factory.stub:1-20`
- `core/Console/Kernel.php:361`
