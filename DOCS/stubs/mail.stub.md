# Dosya Raporu: stubs/mail.stub

## Amaç
Bir mailable sınıfı için kod oluşturma şablonu.

## Genel Bakış
Konsol oluşturucusu tarafından `App\Mail` altında, `Core\Mail\Mailable` sınıfını genişleten yeni bir mailable iskeleti oluşturmak için kullanılan şablon. Oluşturulan `build()` metodu, konu ve metin gövdesini ayarlayan akıcı bir zincir döndürür.

## Dosya Konumu
`stubs/mail.stub`

## Oluşturulan Artifakt
- **Ad Alanı (Namespace):** `App\Mail`
- **Sınıf:** `{{ClassName}} extends Core\Mail\Mailable`
- **İçe Aktarmalar (Imports):** `Core\Mail\Mailable`
- **Metotlar:** `__construct()`, `build(): static`

## Yer Tutucular
- `{{ClassName}}` — oluşturulan mailable sınıfı adı.

## Çapraz Referanslar
- **Kullanan:** `Core\Console\Kernel::renderStub()` — `core/Console/Kernel.php:288`, `core/Console/Kernel.php:699-703`

## Kaynak Referansları
- `stubs/mail.stub:1-22`
- `core/Console/Kernel.php:288`
