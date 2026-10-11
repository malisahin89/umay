# Umay Framework - Database & ORM

Umay Framework, veritabanı etkileşimleri için endüstri standardı olan **Eloquent ORM (Illuminate Database)** paketini kullanır. Bu sayede güçlü, güvenli ve modern bir veritabanı yönetimi sunar.

## Temel Özellikler

- **Eloquent ORM**: `app/Models` dizininde tanımlanan nesneler veritabanı tablolarıyla doğrudan eşleşir. Relationships (One-to-Many vb.), mutators, ve accessors tam desteklenir. (Not: Eski özel `Relations/` ve `QueryBuilder` sarmalayıcıları (wrappers) tamamen silinerek, %100 oranında Native Eloquent yapısına geçilmiştir.)
- **Query Builder**: Ham SQL yazmak yerine akıcı (fluent) arayüz ile sorgular oluşturulabilir: `DB::table('users')->where('email', 'user@example.com')->first()`.
- **Migrations (`database/migrations/`)**: Veritabanı şemasını PHP koduyla yönetmek için kullanılır. `php umay migrate` komutu ile çalıştırılır. (İskelette generic bir `users` tablosu migration'ı gelir.)
- **Seeders (`database/seeders/`)**: Veritabanına başlangıç verileri (ör. test kullanıcıları, referans tablolar) eklemek için kullanılır. `php umay db:seed` komutu ile çalıştırılır. (İskelette `DatabaseSeeder` boş gelir; kendi seeder'larınızı `$this->call([...])` ile ekleyin.)

## DB Facade Kullanımı

```php
// Transaction yönetimi
DB::beginTransaction();
try {
    $user->save();
    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
}

// Raw Query çalıştırma
$users = DB::select('SELECT * FROM users WHERE email = ?', ['user@example.com']);
```

Tüm sistem güncel Eloquent standartlarıyla çalışacak şekilde yapılandırılmış olup, ham PDO bağlantıları izole edilmiştir.
