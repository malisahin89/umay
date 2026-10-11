# Dosya Raporu: stubs/listener.stub

## Amaç
Bir olay dinleyicisi (event listener) için kod oluşturma şablonu.

## Genel Bakış
Konsol oluşturucusu tarafından `App\Listeners` altında, `Core\Events\Listener` sınıfını genişleten ve bir `Event` alan `handle()` metoduna sahip yeni bir dinleyici iskeleti oluşturmak için kullanılan şablon.

## Dosya Konumu
`stubs/listener.stub`

## Oluşturulan Artifakt
- **Ad Alanı (Namespace):** `App\Listeners`
- **Sınıf:** `{{ClassName}} extends Core\Events\Listener`
- **İçe Aktarmalar (Imports):** `Core\Events\Event`, `Core\Events\Listener`
- **Metotlar:** `handle(Event $event): void`

## Yer Tutucular
- `{{ClassName}}` — oluşturulan dinleyici sınıfı adı.

## Çapraz Referanslar
- **Kullanan:** `Core\Console\Kernel::renderStub()` — `core/Console/Kernel.php:336`, `core/Console/Kernel.php:699-703`

## Kaynak Referansları
- `stubs/listener.stub:1-16`
- `core/Console/Kernel.php:336`
