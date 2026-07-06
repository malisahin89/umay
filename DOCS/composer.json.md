# Dosya Raporu: composer.json

## Amaç
Proje bağımlılık yönetimi ve otomatik yükleme (autoloading) yapılandırması.

## Genel Bakış
Proje meta verilerini, gerekli bağımlılıkları, geliştirme araçlarını ve uygulama için PSR-4 otomatik yükleme eşleşmelerini tanımlar.

## Dosya Konumu
`composer.json`

## Bağımlılıklar
- `php`: >=8.2
- `illuminate/database`: ^10.0
- `illuminate/events`: ^10.0
- `illuminate/pagination`: ^10.0
- `league/plates`: ^3.4
- `vlucas/phpdotenv`: ^5.6

## Geliştirme Bağımlılıkları
- `laravel/pint`: ^1.29
- `phpstan/phpstan`: ^2.1
- `phpunit/phpunit`: ^10.0

## Otomatik Yükleme (Autoloading)
- `App\`: `app/`
- `Core\`: `core/`
- `Database\Seeders\`: `database/seeders/`
- `Database\Factories\`: `database/factories/`
- `Tests\`: `tests/` (dev)

## Kaynak Referansları
- `composer.json:1-65`
