# Umay Framework - Tests (Test Süitleri)

Umay Framework, **PHPUnit** kullanılarak yazılmış tam donanımlı bir test mimarisine sahiptir. Toplamda **289 test**, 26 dosya. (Bu testler çekirdeği kapsar; başlangıç iskeleti uygulamaya özel test içermez.)

## Dizin Yapısı
- **`tests/Unit/`**: Bağımsız sınıf ve bileşenlerin (Cache, Router, Validator, Facade vb.) test edildiği birim testleri dizini. (25 dosya)
- **`tests/Feature/`**: Uygulamanın uçtan uca çalışmasının (ör. HTTP istekleri, Veritabanı kayıtları) test edildiği entegrasyon testleri. (1 dosya)
- **`tests/TestCase.php`**: Tüm testlerin türediği ana test sınıfı.
- **`tests/bootstrap.php`**: Test ortamı bootstrap dosyası.

## Statik Analiz (PHPStan)
Projede katı tip güvenliği (Strict Type Safety) esas alınmıştır. Kök dizinde yer alan `phpstan.neon` yapılandırma dosyası ile tüm `core/` ve `app/` dizinleri **Level Max** (en üst seviye) zorluğunda analiz edilir.

## Test Altyapısı
Tüm testler ana `TestCase` sınıfından türer. Testler sırasında asıl veritabanınız (MySQL) yerine **SQLite In-Memory** (bellek içi) veritabanı kullanılır. Bu, testlerin hızlı çalışmasını sağlarken gerçek veritabanını kirletmez. Kök dizindeki `phpunit.xml`, `Unit`/`Feature` testsuite'lerini tanımlar ve `DB_DRIVER=sqlite` ortam değişkenini set ederek bu davranışı otomatik sağlar (`tests/bootstrap.php` bu değişkeni okur).

## Testleri Çalıştırmak
Konsol üzerinden şu komutlarla testler ve analiz başlatılabilir:
```bash
# Tüm test paketi
composer test

# Sadece birim / entegrasyon testleri
composer test:unit
composer test:feature

# phpunit'i doğrudan da çalıştırabilirsiniz
php vendor/bin/phpunit
php vendor/bin/phpunit --testsuite Unit

# Statik Kod Analizi (Level Max)
vendor/bin/phpstan analyse core app
```
