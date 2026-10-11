# Dosya Raporu: stubs/model.stub

## Amaç
Eloquent tarzı bir model için kod oluşturma şablonu.

## Genel Bakış
Konsol oluşturucusu tarafından `App\Models` altında, bir `$table` adı ve boş bir `$fillable` dizisi ile `Core\Model` sınıfını genişleten yeni bir model iskeleti oluşturmak için kullanılan şablon.

## Dosya Konumu
`stubs/model.stub`

## Oluşturulan Artifakt
- **Ad Alanı (Namespace):** `App\Models`
- **Sınıf:** `{{ClassName}} extends Core\Model`
- **İçe Aktarmalar (Imports):** `Core\Model`
- **Özellikler:** `protected $table = '{{tableName}}'`, `protected $fillable = []`

## Yer Tutucular
- `{{ClassName}}` — oluşturulan model sınıfı adı.
- `{{tableName}}` — arka plandaki veritabanı tablosu.

## Çapraz Referanslar
- **Kullanan:** `Core\Console\Kernel::renderStub()` — `core/Console/Kernel.php:180`, `core/Console/Kernel.php:699-703`
- **Taban Sınıf:** `Core\Model` (bakınız `DOCS/core/Model.md`)

## Kaynak Referansları
- `stubs/model.stub:1-16`
- `core/Console/Kernel.php:180`
