# Migrations and Seeders

Umay Framework has a Migration system to manage creating, updating, and rolling back your database tables.

## Creating Migrations

Use the `umay` command from the terminal to create a new table:

```bash
php umay make:migration create_posts_table
```

This command creates a timestamped file in the `database/migrations/` directory.

The migration file looks like this:

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
> Umay Migrations allow you to write raw SQL. This way, you can use all specific features of your database (MySQL/MariaDB) with full performance.

## Running Migrations

To run all new migrations and create the tables:

```bash
php umay migrate
```

## Seeders

Seeders are used to add default data (Admin users, categories, etc.) to the database when the application is installed.

```bash
php umay make:seeder PostsSeeder
```

Edit the `database/seeders/PostsSeeder.php` file:

```php
namespace Database\Seeders;

use App\Models\Post;

class PostsSeeder
{
    public function run(): void
    {
        Post::create([
            'user_id' => 1,
            'title' => 'Umay Framework is Great!',
            'body' => 'This is my first post.'
        ]);
    }
}
```

Then, to run this seeder:

```bash
php umay db:seed
```

(End of file - total 92 lines)
