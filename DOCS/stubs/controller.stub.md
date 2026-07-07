# Dosya Raporu: stubs/controller.stub

## Amaç
Bir RESTful kontrolcü için kod oluşturma şablonu.

## Genel Bakış
Konsol oluşturucusu tarafından `App\Controllers` altında yeni bir kontrolcü iskeleti oluşturmak için kullanılan şablon. `Controller` sınıfını genişleten ve yedi RESTful eylem metoduna (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`) sahip bir sınıf üretir.

## Dosya Konumu
`stubs/controller.stub`

## Oluşturulan Artifakt
- **Ad Alanı (Namespace):** `App\Controllers`
- **Sınıf:** `{{ClassName}} extends Controller`
- **İçe Aktarmalar (Imports):** `Core\Facades\View`, `Core\Request`
- **Metotlar:** `index()`, `create()`, `store(Request)`, `show(Request, string $id)`, `edit(Request, string $id)`, `update(Request, string $id)`, `destroy(Request, string $id)`

## Yer Tutucular
- `{{ClassName}}` — oluşturulan kontrolcü sınıfı adı.
- `{{viewPrefix}}` — `View::render()` çağrılarında kullanılan görünüm yolu ön eki.

## Çapraz Referanslar
- **Kullanan:** `Core\Console\Kernel::renderStub()` — `core/Console/Kernel.php:155`, `core/Console/Kernel.php:699-703`

## Kaynak Referansları
- `stubs/controller.stub:1-46`
- `core/Console/Kernel.php:155-176`
