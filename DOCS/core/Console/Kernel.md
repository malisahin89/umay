# Dosya Raporu: core/Console/Kernel.php

## Amaç
Çerçeve için konsol komut çalıştırıcısı.

## Genel Bakış
`php umay <komut>` aracılığıyla girilen CLI komutlarını işler. Uygulama şablonları (controllers, models, vb.) oluşturmaktan veritabanı migrasyonlarını yönetmeye ve testleri çalıştırmaya kadar her şeyi yönetir.

## Dosya Konumu
`core/Console/Kernel.php`

## İsim Uzayı
`Core\Console`

## Sınıflar
- `class Kernel`

## Metotlar
- `handle(array $argv): int`: Ana giriş noktası. İlk argümana göre komutu dağıtır.
- `keyGenerate(array $args): int`: Rastgele bir `APP_KEY` oluşturur ve `.env` dosyasını günceller.
- `makeController()`, `makeModel()`, `makeMigration()`, `makeMiddleware()`, `makeRequest()`, `makeMail()`, `makeEvent()`, `makeListener()`, `makeFactory()`, `makeTest()`: Şablonlara göre yeni dosyalar oluşturur.
- `migrate()`: `Migrator` aracılığıyla bekleyen migrasyonları çalıştırır.
- `migrateRollback()`: En son migrasyonu geri alır.
- `migrateFresh()`: Veritabanını sıfırlar ve tüm migrasyonlar ile seeder'ları yeniden çalıştırır.
- `storageLink(): int`: Yüklenen dosyalara web üzerinden erişim sağlamak için `public/storage`'dan `storage/app/public`'e bir sembolik bağlantı oluşturur.
- `dbSeed(array $args): int`: Veritabanı seeder'larını çalıştırır.
- `routeList(): int`: Tüm kayıtlı rotaları ve bunların işleyicilerini içeren bir tablo çıktısı verir.
- `cacheClear(): int`: Depolama dizinindeki tüm önbellek dosyalarını siler.
- `runTests(array $args): int`: PHPUnit testlerini yürütür.

## Dahili İş Akışı
- **Şablon Oluşturma**: `.stub` dosyalarındaki yer tutucuları gerçek sınıf isimleri ve değişkenlerle değiştirmek için `renderStub()` kullanır.
- **Durum Dönüştürme**: Doğru isimlendirme standartları için `studlyCase()`, `snakeCase()` ve `pluralSnake()` fonksiyonlarını içerir.

## Bağımlılıklar
- `Core\Migration` (Kullanır)
- `Core\Migrator` (Kullanır)
- `Core\Route` (Kullanır)
- `Core\Seeder` (Kullanır)

## Kaynak Referansları
- `core/Console/Kernel.php:1-781`
