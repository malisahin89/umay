# Oturum (Session)

## Amaç
Oturum kullanımını ve yapılandırmasını belgeler.

## Genel Bakış
Umay, yerel PHP oturumlarını (`$_SESSION`) kullanır. Özel bir oturum yöneticisi sınıfı yoktur; bileşenler oturumu talep üzerine başlatır (`session_status() === PHP_SESSION_NONE` $\to$ `session_start()`) ve doğrudan `$_SESSION` üzerinden okuma/yazma yapar. Oturum ayarları `config/session.php` içinde tanımlanmıştır.

## Yapılandırma (`config/session.php`)
- `lifetime` — dakika cinsinden, varsayılan 30 (`SESSION_LIFETIME`).
- `cookie` — oturum çerezi adı, varsayılan `umay_session` (`SESSION_COOKIE`).
- `secure` — HTTPS üzerinden otomatik olarak etkinleştirilir, `SESSION_SECURE` ile zorlanabilir.
- `http_only` — `true`.
- `same_site` — varsayılan `Strict` (`SESSION_SAME_SITE`).

## Framework Genelinde Oturum Kullanımı
- **CSRF** (`Core\Csrf`): `$_SESSION['csrf_token']` değerini saklar/okur; gerekirse oturumu başlatır.
- **Kimlik Doğrulama** (`Core\Auth`): `$_SESSION['user_id']` değerini okur; `login()` fonksiyonu `session_regenerate_id(true)` çağrısını yapar, `user_id`/`login_time` değerlerini ayarlar ve CSRF token'ını döndürür; `logout()` fonksiyonu `$_SESSION`'ı temizler, oturum çerezini sona erdirir ve `session_destroy()` çağrısını yapar.
- **Görünüm** (`Core\View`): Oturumu başlatır ve işleme (render) sırasında flash verileri (`_flash`, `_flash_errors`, `_old`) tüketir.
- **Testler** (`Tests\TestCase`): Dizi tabanlı bir oturum kullanır ve her testte bunu sıfırlar.

## Güvenlik Gözlemleri
- Varsayılan olarak `http_only` + `SameSite=Strict`; HTTPS altında `secure`.
- Giriş yapıldığında oturum kimliği yeniden oluşturulur (fixation savunması); ayrıcalık değiştiğinde CSRF token'ı döndürülür.

## Çapraz Referanslar
- `Core\Csrf` (bkz. `DOCS/core/Csrf.md`), `Core\Auth` (bkz. `DOCS/AUTHENTICATION.md`), `Core\View` (bkz. `DOCS/VIEW_ENGINE.md`)

## Kaynak Referansları
- `config/session.php:5-28`
- `core/Csrf.php:9-40`, `core/Auth.php:171-241`, `core/View.php:241-305`
