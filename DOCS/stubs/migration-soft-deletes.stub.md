# Dosya Raporu: stubs/migration-soft-deletes.stub

## Amaç
Soft-delete sütunu ekleyen bir migrasyon için kod oluşturma şablonu.

## Genel Bakış
`Core\Migration` sınıfını genişleten anonim bir sınıf döndüren şablon. `up()` metodu `ALTER TABLE ... ADD COLUMN deleted_at TIMESTAMP NULL` ifadesini yürütür; `down()` metodu ise sütunu siler. `Core\Concerns\SoftDeletes` içindeki soft-delete davranışını tamamlar.

## Dosya Konumu
`stubs/migration-soft-deletes.stub`

## Oluşturulan Artifakt
- **Sınıf:** anonim `class extends Core\Migration`
- **İçe Aktarmalar (Imports):** `Core\Migration`
- **Metotlar:** `up(): void`, `down(): void`
- **Şema değişikliği:** `deleted_at TIMESTAMP NULL DEFAULT NULL` ekler / siler.

## Yer Tutucular
- `{{tableName}}` — değiştirilecek hedef tablo.

## Çapraz Referanslar
- **Taban Sınıf:** `Core\Migration` (bakınız `DOCS/core/Migration.md`)
- **İlgili:** `Core\Concerns\SoftDeletes` (bakınız `DOCS/core/Concerns/SoftDeletes.md`)

> Analiz edilen kaynak kodunda (`core/Console/Kernel.php`) doğrulanmış bir `renderStub('migration-soft-deletes')` çağrısı bulunamadı. Şablon mevcut ancak oluşturucu çağrısı onaylanamadı.

## Kaynak Referansları
- `stubs/migration-soft-deletes.stub:1-24`
