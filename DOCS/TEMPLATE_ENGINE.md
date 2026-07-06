# Şablon Motoru (Template Engine)

## Amaç
Şablonlama katmanını (League Plates) ve şablon dosya yapısını belgeler.

## Genel Bakış
Umay, şablon motoru olarak **League Plates** (`league/plates ^3.4`) kullanır. Şablonlar `views/` altında yer alan düz PHP dosyalarıdır ve `Core\View` aracılığıyla işlenir (bkz. `DOCS/VIEW_ENGINE.md`). Plates, `views/` dizinini kök dizin olarak ve `views/partials/` dizinini `partials` ad alanı (namespace) altında kaydederek başlatılır.

## Şablon Yapısı (`views/`)
- **`views/layouts/`** — sayfa içeriğini sarmalayan temel düzenler (örneğin `base.php`).
- **`views/partials/`** — yeniden kullanılabilir bileşenler: `alert.php`, `button.php`, `card.php`, `input.php`. `$this->insert('partials::name', [params])` aracılığıyla eklenir.
- **`views/errors/`** — hata sayfaları `403`, `404`, `500` (`Core\ExceptionHandler` tarafından işlenir).
- **`views/welcome.php`** — varsayılan açılış şablonu.

## Kullanım
- PHP'den işleme: `View::render('users/index', ['users' => $users])`.
- Şablonlar içinde, `Core\View` tarafından kaydedilen yardımcı fonksiyonlar `$this->fn(...)` şeklinde kullanılabilir (örneğin `$this->e()`, `$this->route()`, `$this->csrf()`).

## Çapraz Referanslar
- `DOCS/VIEW_ENGINE.md`, `DOCS/views/index.md`, `DOCS/views/layouts/index.md`, `DOCS/views/partials/index.md`, `DOCS/views/errors/index.md`
- `DOCS/ERROR_HANDLING.md` (hata görünümleri)

## Kaynak Referansları
- `core/View.php:46-54` (motor + partials klasörü)
- `composer.json:31` (league/plates)
- `views/` dizini
