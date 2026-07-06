# Sorgu Oluşturucu (Query Builder)

Umay Framework, Illuminate Database Query Builder'ını kullanır. Bu sayede karmaşık sorguları, güvenli ve okunaklı bir şekilde yazabilirsiniz.

Sorgu oluşturucuya ulaşmak için Eloquent Modellerini kullanabileceğiniz gibi, doğrudan `DB` sınıfını (Kapsayıcıyı) da kullanabilirsiniz.

## Veri Çekme (Select)

```php
use Illuminate\Database\Capsule\Manager as DB;

// Bir tablodaki tüm verileri çekme
$users = DB::table('users')->get();

// Sadece belirli bir koşula uyan ilk satırı çekme
$user = DB::table('users')->where('email', 'admin@example.com')->first();

// Sadece belirli sütunları çekme
$users = DB::table('users')->select('name', 'email')->get();
```

## Nerede (Where) Koşulları

```php
// Basit eşleşme (status = active)
DB::table('users')->where('status', 'active')->get();

// Operatör ile kullanım (votes > 100)
DB::table('users')->where('votes', '>', 100)->get();

// LIKE operatörü (İsmi 'A' ile başlayanlar)
DB::table('users')->where('name', 'LIKE', 'A%')->get();

// AND ve OR koşulları
DB::table('users')
    ->where('votes', '>', 100)
    ->orWhere('name', 'John')
    ->get();
```

## Veri Ekleme (Insert)

```php
DB::table('users')->insert([
    'name' => 'Can',
    'email' => 'can@example.com',
    'status' => 'active'
]);

// Eklenen kaydın ID'sini geri alma
$id = DB::table('users')->insertGetId([
    'name' => 'Elif',
    'email' => 'elif@example.com'
]);
```

## Güncelleme (Update)

```php
// Sadece status=pending olanları active yap
DB::table('users')
    ->where('status', 'pending')
    ->update(['status' => 'active']);
```

## Silme (Delete)

```php
// Belirli bir kullanıcıyı sil
DB::table('users')->where('id', 5)->delete();

// Tüm tabloyu temizle
DB::table('users')->truncate();
```

## Sayfalama (Pagination)

Veritabanından dönen yüzlerce kaydı sayfalara bölmek tek bir metotla yapılır:

```php
// Sayfa başına 15 kayıt getir
$users = DB::table('users')->paginate(15);
```

View (Şablon) tarafında sayfalamayı ekrana basmak için Umay Framework'ün dahili `paginate()` helper'ı ve `Core\Paginator` sınıfı kullanılır. Daha fazlası için Modeller konusuna bakabilirsiniz.
