![Umay Framework Logo](umay.png)

# Umay Framework

A minimal PHP MVC framework built from scratch.

## Requirements

- PHP 8.2+
- Composer
- MySQL / MariaDB

## Dependencies

- `illuminate/database` ^10.0 — Eloquent ORM
- `illuminate/events` ^10.0 — Model Events & Observers
- `illuminate/pagination` ^10.0 — Eloquent Pagination
- `league/plates` ^3.4 — Template engine
- `vlucas/phpdotenv` ^5.6 — .env loader
- `phpunit/phpunit` ^10.0 (dev) — Testing framework

## Installation

```bash
composer install
cp .env.example .env  # veya .env dosyasını manuel oluştur
# Edit .env file (DB credentials, APP_KEY, etc.)
```

> `.env.example` dosyasını `.env` olarak kopyalayın ve `APP_KEY`, veritabanı bilgileri vb. değerleri doldurun. Hızlı bir `APP_KEY` üretmek için: `php umay key:generate`.

### Production Deployment

```bash
composer install --no-dev --optimize-autoloader --classmap-authoritative
```

> The `--classmap-authoritative` flag forces the autoloader to resolve classes from a pre-built classmap instead of scanning the filesystem, providing significantly faster class loading.

## Quick Start

```bash
php umay migrate
php umay db:seed
php -S localhost:8000 -t public
```

## Console Commands

Umay provides an Artisan-like CLI tool. Usage: `php umay <command> [arguments]`

### Code Generators

| Command | Description | Output |
|---------|-------------|--------|
| `make:controller Name` | Create a controller | `app/Controllers/NameController.php` |
| `make:model Name` | Create a model | `app/Models/Name.php` |
| `make:migration name` | Create a migration | `database/migrations/YYYY_MM_DD_HHMMSS_name.php` |
| `make:middleware Name` | Create a middleware | `app/Middleware/NameMiddleware.php` |
| `make:request Name` | Create a FormRequest | `app/Requests/NameRequest.php` |
| `make:mail Name` | Create a Mailable | `app/Mail/NameMail.php` |
| `make:event Name` | Create an event | `app/Events/Name.php` |
| `make:listener Name` | Create a listener | `app/Listeners/Name.php` |
| `make:factory Name` | Create a factory | `database/factories/NameFactory.php` |
| `make:test Name` | Create a feature test | `tests/Feature/NameTest.php` |
| `make:test Name --unit` | Create a unit test | `tests/Unit/NameTest.php` |

### Database Commands

| Command | Description |
|---------|-------------|
| `migrate` | Run all pending migrations |
| `migrate:rollback` | Rollback the last migration |
| `migrate:fresh` | Drop all tables and re-run all migrations |
| `db:seed` | Run database seeders |

### Utility Commands

| Command | Description |
|---------|-------------|
| `route:list` | List all registered routes |
| `cache:clear` | Clear all cache files |
| `test` | Run PHPUnit tests |
| `test --unit` | Run only unit tests |
| `test --feature` | Run only feature tests |
| `help` | Show available commands |

## Profiler / DebugBar

Umay includes a built-in AJAX-based profiler toolbar that displays at the bottom of every page during development.

### Activation

The profiler is **automatically enabled** when `APP_DEBUG=true` in your `.env` file. No additional setup required.

You can also control it via environment variables:

```env
PROFILER_ENABLED=true
PROFILER_TTL=7200
PROFILER_MAX_ENTRIES=200
PROFILER_IP_WHITELIST=127.0.0.1,::1
```

### What It Shows

- **Response Time** — Total request duration
- **Memory Usage** — Peak memory consumption
- **Database Queries** — SQL queries with execution time, bindings, and caller info
- **Views** — Rendered templates
- **Auth** — Current authenticated user
- **Route** — Matched route, controller, and middleware chain
- **Events** — Dispatched events
- **Mail** — Sent emails
- **Exceptions** — Caught exceptions with stack trace

### Profiler API

The profiler stores data as JSON and exposes an API endpoint:

```
GET /_profiler           → List recent profiler entries
GET /_profiler/{token}   → Get a specific profile detail
```

> **Security:** Access is restricted by `ip_whitelist` in `config/profiler.php`. Only whitelisted IPs can view profiler data. In production, the profiler is disabled by default.

## Helper Functions

Umay includes a variety of global "helper" functions to speed up development:

- **Routing & HTTP:** `route()`, `redirect()`, `back()`, `asset()`, `abort()`, `response()`
- **State & Auth:** `env()`, `config()`, `flash()`, `old()`, `auth()`, `csrf()`
- **Utilities:** `str_slug()`, `str_limit()`, `now()`, `today()`
- **Advanced:** `collect()` (Illuminate Collections), `cache()`, `event()`, `paginator()`, `factory()`
- **Security:** `getRealIP()` (Securely resolves real IP even behind Cloudflare proxies)

## Features

- **Routing:** Named routes, prefix/group, middleware, resource routes, method spoofing
- **IoC Container:** PSR-11 compatible, reflection-based autowiring with reflection caching
- **Request/Response:** Full HTTP abstraction layer
- **Auth:** Pluggable guard — `Authenticatable` + `UserProvider` contracts, session + remember me (bring-your-own user model & provider)
- **Middleware:** Pipeline-based (global + route level) with class resolution caching
- **Validation:** 30+ rules, FormRequest support
- **Cache:** HMAC-signed file-based caching
- **Events:** Singleton event bus + listener
- **Mail:** SMTP + log driver
- **Database:** Full native Eloquent ORM integration (Scopes, Relations, Mutators)
- **Migration/Seeder:** Automated database management
- **Authorization:** Roll-your-own (middleware-based); full RBAC example on the `demo-app` branch
- **Rate Limiting:** Cache-based throttle
- **Pagination:** Eloquent-compatible pagination
- **Security:** CSP, CSRF, XSS protection
- **Console CLI:** Artisan-like command system
- **Profiler/DebugBar:** AJAX-based performance monitor
- **Helpers:** Global utilities for routing, HTTP responses, caching, and string manipulation
- **Network Security:** Built-in Cloudflare IP validation to prevent IP spoofing
- **Performance:** Singleton Plates template engine with component system, pre-compiled route regex, reflection caching, single DB connection, optimized autoloader

## Directory Structure

```
├── core/           ← Framework core
├── app/            ← Application code (clean starter skeleton)
│   ├── Controllers/   (base Controller)
│   ├── Middleware/    (ThrottleMiddleware)
│   ├── Models/        (generic User)
│   ├── Providers/
│   └── Services/
├── config/         ← Configuration
├── database/       ← Migrations, seeders, factories
├── docs/           ← Framework documentation
├── public/         ← Entry point (index.php)
├── routes/         ← Web and API routes
├── stubs/          ← Code generator templates (make:* commands read from here)
├── storage/        ← Cache, logs
├── tests/          ← PHPUnit tests
└── views/          ← Plates template files
```

## Namespaces

- `Core\` — Framework core
- `App\` — Application code
- `Database\Migrations\` — Migration files
- `Database\Seeders\` — Seeder files
- `Tests\` — Test files

## License

MIT License — see [LICENSE](LICENSE) for details.

---

# Umay Framework (Türkçe)

Sıfırdan yazılmış minimal PHP MVC framework.

## Gereksinimler

- PHP 8.2+
- Composer
- MySQL / MariaDB

## Bağımlılıklar

- `illuminate/database` ^10.0 — Eloquent ORM
- `illuminate/events` ^10.0 — Model Event'leri & Observer'lar
- `illuminate/pagination` ^10.0 — Eloquent Sayfalama
- `league/plates` ^3.4 — Template motoru
- `vlucas/phpdotenv` ^5.6 — .env yükleme
- `phpunit/phpunit` ^10.0 (dev) — Test framework

## Kurulum

```bash
composer install
cp .env.example .env  # veya .env dosyasını manuel oluştur
# .env dosyasını düzenleyin (DB bilgileri, APP_KEY vb.)
```

> `.env.example` dosyasını `.env` olarak kopyalayın ve `APP_KEY`, veritabanı bilgileri vb. değerleri doldurun. Hızlı bir `APP_KEY` üretmek için: `php umay key:generate`.

### Production Yayınlama

```bash
composer install --no-dev --optimize-autoloader --classmap-authoritative
```

> `--classmap-authoritative` flag'i autoloader'ı dosya sistemi taraması yerine önceden derlenmiş classmap'ten çözümlemeye zorlar. Bu sayede sınıf yükleme önemli ölçüde hızlanır.

## Hızlı Başlangıç

```bash
php umay migrate
php umay db:seed
php -S localhost:8000 -t public
```

## Konsol Komutları

Umay, CLI tabanlı bir komut aracı sunar. Kullanım: `php umay <komut> [argümanlar]`

### Kod Üreticiler

| Komut | Açıklama | Çıktı |
|-------|----------|-------|
| `make:controller Name` | Controller oluştur | `app/Controllers/NameController.php` |
| `make:model Name` | Model oluştur | `app/Models/Name.php` |
| `make:migration name` | Migration oluştur | `database/migrations/YYYY_MM_DD_HHMMSS_name.php` |
| `make:middleware Name` | Middleware oluştur | `app/Middleware/NameMiddleware.php` |
| `make:request Name` | FormRequest oluştur | `app/Requests/NameRequest.php` |
| `make:mail Name` | Mailable oluştur | `app/Mail/NameMail.php` |
| `make:event Name` | Event oluştur | `app/Events/Name.php` |
| `make:listener Name` | Listener oluştur | `app/Listeners/Name.php` |
| `make:factory Name` | Factory oluştur | `database/factories/NameFactory.php` |
| `make:test Name` | Feature test oluştur | `tests/Feature/NameTest.php` |
| `make:test Name --unit` | Unit test oluştur | `tests/Unit/NameTest.php` |

### Veritabanı Komutları

| Komut | Açıklama |
|-------|----------|
| `migrate` | Bekleyen migration'ları çalıştır |
| `migrate:rollback` | Son migration'ı geri al |
| `migrate:fresh` | Tüm tabloları sil ve migration'ları yeniden çalıştır |
| `db:seed` | Seeder'ları çalıştır |

### Yardımcı Komutlar

| Komut | Açıklama |
|-------|----------|
| `route:list` | Kayıtlı route'ları listele |
| `cache:clear` | Cache dosyalarını temizle |
| `test` | PHPUnit testlerini çalıştır |
| `test --unit` | Sadece unit testleri çalıştır |
| `test --feature` | Sadece feature testleri çalıştır |
| `help` | Kullanılabilir komutları göster |

## Profiler / DebugBar

Umay, geliştirme sırasında her sayfanın altında görünen AJAX tabanlı bir profiler toolbar içerir.

### Aktivasyon

`.env` dosyasında `APP_DEBUG=true` olduğunda profiler **otomatik olarak aktif** olur. Ek kurulum gerekmez.

Ortam değişkenleriyle de kontrol edebilirsiniz:

```env
PROFILER_ENABLED=true
PROFILER_TTL=7200
PROFILER_MAX_ENTRIES=200
PROFILER_IP_WHITELIST=127.0.0.1,::1
```

### Gösterdiği Bilgiler

- **Yanıt Süresi** — Toplam istek süresi
- **Bellek Kullanımı** — En yüksek bellek tüketimi
- **Veritabanı Sorguları** — SQL sorguları, çalışma süresi, binding'ler ve çağıran bilgisi
- **View'lar** — Render edilen template'ler
- **Auth** — Giriş yapmış kullanıcı
- **Route** — Eşleşen route, controller ve middleware zinciri
- **Event'ler** — Dispatch edilen event'ler
- **Mail** — Gönderilen e-postalar
- **Exception'lar** — Yakalanan hatalar ve stack trace

### Profiler API

Profiler verileri JSON olarak saklanır ve bir API endpoint'i sunar:

```
GET /_profiler           → Son profil kayıtlarını listele
GET /_profiler/{token}   → Belirli bir profil detayını getir
```

> **Güvenlik:** Erişim, `config/profiler.php` içindeki `ip_whitelist` ile kısıtlanır. Sadece beyaz listedeki IP'ler profiler verilerini görebilir. Production'da profiler varsayılan olarak kapalıdır.

## Yardımcı Fonksiyonlar (Helpers)

Umay, geliştirmeyi hızlandırmak için çeşitli global "helper" fonksiyonları içerir:

- **Routing & HTTP:** `route()`, `redirect()`, `back()`, `asset()`, `abort()`, `response()`
- **State & Auth:** `env()`, `config()`, `flash()`, `old()`, `auth()`, `csrf()`
- **Araçlar:** `str_slug()`, `str_limit()`, `now()`, `today()`
- **Gelişmiş:** `collect()` (Illuminate Collections), `cache()`, `event()`, `paginator()`, `factory()`
- **Güvenlik:** `getRealIP()` (Cloudflare proxy arkasında bile gerçek IP'yi güvenli şekilde alır)

## Özellikler

- **Routing:** Named routes, prefix/group, middleware, resource routes, method spoofing
- **IoC Container:** PSR-11 uyumlu, reflection önbellekli autowiring
- **Request/Response:** Tam HTTP soyutlama katmanı
- **Auth:** Takılabilir guard — `Authenticatable` + `UserProvider` sözleşmeleri, session + remember me (kendi kullanıcı modeliniz & provider'ınız)
- **Middleware:** Pipeline tabanlı (global + route seviyesi), sınıf çözümleme önbellekli
- **Validation:** 30+ kural, FormRequest desteği
- **Cache:** HMAC-imzalı dosya tabanlı önbellek
- **Events:** Singleton event bus + listener
- **Mail:** SMTP + log driver
- **Veritabanı:** Tam yerleşik (Native) Eloquent ORM entegrasyonu (Scope'lar, İlişkiler, Mutator'lar)
- **Migration/Seeder:** Otomatik veritabanı yönetimi
- **Yetkilendirme:** Kendiniz kurun (middleware tabanlı); tam RBAC örneği `demo-app` branch'inde
- **Rate Limiting:** Cache tabanlı throttle
- **Pagination:** Eloquent uyumlu sayfalama
- **Security:** CSP, CSRF, XSS koruması
- **Console CLI:** Komut satırı aracı
- **Profiler/DebugBar:** AJAX tabanlı performans izleyici
- **Helpers:** Yönlendirme, HTTP yanıtları, önbellekleme ve metin işleme için yardımcı araçlar
- **Ağ Güvenliği:** IP sahtekarlığını önlemek için dahili Cloudflare IP doğrulama sistemi
- **Performans:** Singleton Plates template engine ve component sistemi, önceden derlenmiş route regex, reflection önbellekleme, tek DB bağlantısı, optimize autoloader

## Dizin Yapısı

```
├── core/           ← Framework çekirdeği
├── app/            ← Uygulama kodu (temiz başlangıç iskeleti)
│   ├── Controllers/   (Controller tabanı)
│   ├── Middleware/    (ThrottleMiddleware)
│   ├── Models/        (generic User)
│   ├── Providers/
│   └── Services/
├── config/         ← Konfigürasyon
├── database/       ← Migration, seeder, factory
├── docs/           ← Framework dokümantasyonu
├── public/         ← Entry point (index.php)
├── routes/         ← Web ve API rotaları
├── stubs/          ← Code generator şablonları (make:* komutları buradan okur)
├── storage/        ← Cache, loglar
├── tests/          ← PHPUnit testleri
└── views/          ← Plates template dosyaları
```

## Namespace'ler

- `Core\` — Framework çekirdeği
- `App\` — Uygulama kodu
- `Database\Migrations\` — Migration dosyaları
- `Database\Seeders\` — Seeder dosyaları
- `Tests\` — Test dosyaları

## Lisans

MIT Lisansı — detaylar için [LICENSE](LICENSE) dosyasına bakın.
