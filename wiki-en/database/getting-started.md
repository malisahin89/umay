# Database: Getting Started

Umay Framework incorporates **Eloquent ORM** (Illuminate\Database), one of the world's most popular and powerful ORM engines. This allows you to perform database operations in an object-oriented (OOP) and incredibly secure way without writing complex SQL queries.

## Configuration

Your database settings are located in the `.env` file in the root directory.

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=umay_db
DB_USERNAME=root
DB_PASSWORD=
```

You can also configure secondary connections, SQLite settings, or different collation/charset configurations via the `config/database.php` file.

## Security (SQL Injection Protection)

ALL queries performed using the Query Builder or Eloquent in Umay Framework automatically go through **PDO Parameter Binding** in the background. This ensures you are 100% protected against SQL Injection attacks.

```php
use App\Models\User;

// SECURE: External data is bound as a parameter.
$user = User::where('email', $_POST['email'])->first();

// SECURE: Using named parameters
$users = DB::select('SELECT * FROM users WHERE status = :status', ['status' => 'active']);
```

> [!CAUTION]  
> Only when using `DB::raw()` or `whereRaw()`, never add external (user-sourced) data directly into the query. Even in raw queries, use PDO parameter binding.
