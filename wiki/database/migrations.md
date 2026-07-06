# Migration ve Seeder'lar

Umay Framework, veritabanı tablolarınızı oluşturmayı, güncellemeyi ve geri almayı (rollback) yönetmek için bir Migration sistemine sahiptir.

## Migration Oluşturma

Terminalden yeni bir tablo oluşturmak için `umay` komutunu kullanın:

```bash
php umay make:migration create_posts_table
```

Bu komut `database/migrations/` dizininde üzerinde tarih damgası olan bir dosya oluşturur. 

Migration dosyasının içi şu şekildedir:

```php
<?php

declare(strict_types=1);

use Core\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (! $this->tableExists('posts')) {
            $this->execute("
                CREATE TABLE `posts` (
                    `id` INT NOT NULL AUTO_INCREMENT,
                    `user_id` INT NOT NULL,
                    `title` VARCHAR(255) NOT NULL,
                    `body` TEXT NOT NULL,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
        }
    }

    public function down(): void
    {
        $this->execute('DROP TABLE IF EXISTS `posts`');
    }
};
```

> [!NOTE]
> Umay Migration'ları saf SQL yazmanıza olanak tanır. Bu sayede veritabanınıza (MySQL/MariaDB) özgü tüm özellikleri tam performansla kullanabilirsiniz.

## Migration'ları Çalıştırma

Tüm yeni migration'ları çalıştırmak ve tabloları oluşturmak için:

```bash
php umay migrate
```

## Seeder'lar (Tohumlayıcılar)

Uygulamanız kurulduğunda veritabanına varsayılan verileri (Admin kullanıcısı, kategoriler vb.) eklemek için Seeder kullanılır.

```bash
php umay make:seeder PostsSeeder
```

`database/seeders/PostsSeeder.php` dosyasını düzenleyin:

```php
namespace Database\Seeders;

use App\Models\Post;

class PostsSeeder
{
    public function run(): void
    {
        Post::create([
            'user_id' => 1,
            'title' => 'Umay Framework Harika!',
            'body' => 'Bu benim ilk yazım.'
        ]);
    }
}
```

Sonra bu seeder'ı çalıştırmak için:

```bash
php umay db:seed
```
