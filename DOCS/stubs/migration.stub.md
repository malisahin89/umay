# Dosya Raporu: stubs/migration.stub

## Amaç
Tablo oluşturma migrasyonu için kod oluşturma şablonu.

## Genel Bakış
Konsol oluşturucusu tarafından `database/migrations/` altında yeni bir migrasyon dosyası iskeleti oluşturmak için kullanılan şablon. `Core\Migration` sınıfını genişleten anonim bir sınıf döndürür. `up()` metodu, `id`, `created_at` ve `updated_at` sütunlarına sahip bir tablo oluşturur (`tableExists()` ile korumalı); `down()` metodu ise tabloyu siler.

## Dosya Konumu
`stubs/migration.stub`

## Oluşturulan Artifakt
- **Sınıf:** anonim `class extends Core\Migration` (`return new class ...` aracılığıyla döndürülür)
- **İçe Aktarmalar (Imports):** `Core\Migration`
- **Metotlar:** `up(): void`, `down(): void`
- **Şema:** `id` (INT AUTO_INCREMENT PK), `created_at`, `updated_at`; motor InnoDB, karakter seti `utf8mb4`.

## Yer Tutucular
- `{{tableName}}` — hedef tablo adı.
- `{{ClassName}}` — oluşturucu tarafından sağlanır (bakınız `core/Console/Kernel.php:203-206`), ancak üretilen sınıf anonimdir.

## Çapraz Referanslar
- **Kullanan:** `Core\Console\Kernel::renderStub()` — `core/Console/Kernel.php:203`, `core/Console/Kernel.php:699-703`
- **Taban Sınıf:** `Core\Migration` (bakınız `DOCS/core/Migration.md`)

## Kaynak Referansları
- `stubs/migration.stub:1-27`
- `core/Console/Kernel.php:203-208`
