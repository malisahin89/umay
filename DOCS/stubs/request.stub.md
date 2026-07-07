# Dosya Raporu: stubs/request.stub

## Amaç
Bir form isteği (form request) için kod oluşturma şablonu.

## Genel Bakış
Konsol oluşturucusu tarafından `App\Requests` altında, `authorize()`, `rules()` ve `messages()` metotları ile `Core\FormRequest` sınıfını genişleten yeni bir form isteği iskeleti oluşturmak için kullanılan şablon.

## Dosya Konumu
`stubs/request.stub`

## Oluşturulan Artifakt
- **Ad Alanı (Namespace):** `App\Requests`
- **Sınıf:** `{{ClassName}} extends Core\FormRequest`
- **İçe Aktarmalar (Imports):** `Core\FormRequest`
- **Metotlar:** `authorize(): bool` (`true` döndürür), `rules(): array`, `messages(): array`

## Yer Tutucular
- `{{ClassName}}` — oluşturulan form isteği sınıfı adı.

## Çapraz Referanslar
- **Kullanan:** `Core\Console\Kernel::renderStub()` — `core/Console/Kernel.php:260`, `core/Console/Kernel.php:699-703`
- **Taban Sınıf:** `Core\FormRequest` (bakınız `DOCS/core/FormRequest.md`)

## Kaynak Referansları
- `stubs/request.stub:1-29`
- `core/Console/Kernel.php:260`
