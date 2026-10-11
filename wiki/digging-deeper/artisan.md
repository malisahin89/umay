# Umay Konsol (CLI)

Umay Framework de size günlük görevlerinizi hızlandıracak `umay` komut satırı aracını sunar.

Terminali (Komut İstemi / Bash) açıp proje dizinine gittiğinizde `php umay` yazarak aracı çalıştırabilirsiniz.

## Kullanılabilir Komutlar

Sistemin tüm komut listesini görmek için:
```bash
php umay help
```

### Geliştirici Üreticileri (Make Commands)

Umay sizin için gerekli class (Sınıf) şablonlarını otomatik oluşturur. Elle dosya yaratıp namespace ayarlamakla uğraşmazsınız.

```bash
# Yeni bir Model ve Controller oluştur
php umay make:model Post
php umay make:controller PostController

# Resource (İçinde tüm CRUD metotları hazır olan) Controller oluştur
php umay make:controller ProductController --resource

# Yeni bir Middleware oluştur
php umay make:middleware CheckAgeMiddleware

# Yeni bir Migration dosyası oluştur
php umay make:migration create_products_table

# Veritabanı için Seeder oluştur
php umay make:seeder ProductsSeeder
```

### Veritabanı Komutları

Migration ve Seeder dosyalarını veritabanında koşturmak için kullanılır.

```bash
# Tüm yeni migration'ları çalıştır
php umay migrate

# Migration'ları iptal et ve tabloları sil
php umay migrate:rollback

# Seeder sınıflarını çalıştır ve örnek veri ekle
php umay db:seed
```

### Bakım ve Diğerleri

```bash
# Sistemdeki rotaları terminalde listeler (Debug için mükemmeldir)
php umay route:list

# public/storage -> storage/app/public symlink'i oluşturur (Yüklenen dosyaların web'den erişimi için)
php umay storage:link

# Framework'ün önbellek (Cache) klasörünü temizler
php umay cache:clear
```

> [!TIP]
> `make` komutları çalışırken arka planda framework kök dizinindeki `stubs/` klasöründeki şablonları kullanır. Dosyaların standart üretim kodunu kendi kod yapınıza göre özelleştirmek isterseniz `stubs/` içindeki dosyaları düzenleyebilirsiniz.
