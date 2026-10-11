# Kurulum

Umay Framework, modern PHP standartları kullanılarak geliştirilmiş, son derece hafif ancak bir o kadar da yetenekli bir web framework'üdür. Geliştirici deneyimini (DX) hedeflerken, gereksiz karmaşıklıktan uzak durur.

## Sistem Gereksinimleri

Umay Framework'ü sunucunuzda veya yerel ortamınızda çalıştırabilmeniz için aşağıdaki gereksinimlerin karşılanması gerekir:

- **PHP**: `>= 8.2`
- **Veritabanı**: MySQL 8.0+ / MariaDB 10.4+ veya testler için SQLite
- **Eklentiler**: `PDO`, `Mbstring`, `JSON`, `OpenSSL`
- **Web Sunucusu**: Apache (mod_rewrite aktif) veya Nginx

## Yeni Bir Proje Oluşturma

Umay Framework projesini başlatmak oldukça basittir. Composer yüklü bir sistemde aşağıdaki adımları izleyin:

```bash
# Projeyi GitHub üzerinden veya yerel arşivinizden klonlayın
git clone https://github.com/malisahin89/umay.git my-project

# Proje dizinine geçin
cd my-project

# Bağımlılıkları kurun
composer install
```

## Yapılandırma (.env)

Kurulum tamamlandıktan sonra kök dizinde `.env` dosyasını oluşturun:

```bash
# Kopyalama yöntemi (eğer .env.example varsa):
cp .env.example .env

# veya manuel oluştur:
touch .env
```

`APP_KEY` değerinizin benzersiz olduğundan emin olun. Geliştirme ortamında `APP_ENV=local` ve `APP_DEBUG=true` ayarlarını kullanmanız hataları daha rahat görmenizi sağlar.

```ini
APP_NAME="Umay Framework"
APP_ENV=local
APP_KEY=your_secret_key_here
APP_DEBUG=true

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=umay_db
DB_USERNAME=root
DB_PASSWORD=
```

## İlk Çalıştırma

Geliştirme ortamınızda projenizi hemen ayağa kaldırmak için Laragon/Valet gibi araçları kullanabileceğiniz gibi, PHP'nin dahili sunucusunu da kullanabilirsiniz:

```bash
php -S localhost:8000 -t public
```

Tarayıcınızdan `http://localhost:8000` adresine gittiğinizde Umay Framework karşılama ekranını göreceksiniz.
