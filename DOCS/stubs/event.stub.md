# Dosya Raporu: stubs/event.stub

## Amaç
Bir olay (event) sınıfı için kod oluşturma şablonu.

## Genel Bakış
Konsol oluşturucusu tarafından `App\Events` altında, boş bir yapılandırıcıya (constructor) sahip `Core\Events\Event` sınıfını genişleten yeni bir olay iskeleti oluşturmak için kullanılan şablon.

## Dosya Konumu
`stubs/event.stub`

## Oluşturulan Artifakt
- **Ad Alanı (Namespace):** `App\Events`
- **Sınıf:** `{{ClassName}} extends Core\Events\Event`
- **İçe Aktarmalar (Imports):** `Core\Events\Event`
- **Metotlar:** `__construct()`

## Yer Tutucular
- `{{ClassName}}` — oluşturulan olay sınıfı adı.

## Çapraz Referanslar
- **Kullanan:** `Core\Console\Kernel::renderStub()` — `core/Console/Kernel.php:312`, `core/Console/Kernel.php:699-703`

## Kaynak Referansları
- `stubs/event.stub:1-15`
- `core/Console/Kernel.php:312`
