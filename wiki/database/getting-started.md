# Veritabanı: Başlangıç

Umay Framework, arkasında dünyanın en popüler ve güçlü ORM motoru olan **Eloquent ORM**'yi (Illuminate\Database) barındırır. Bu sayede karmaşık SQL sorguları yazmadan, veritabanı işlemlerini nesne yönelimli (OOP) ve inanılmaz güvenli bir şekilde gerçekleştirebilirsiniz.

## Yapılandırma

Veritabanı ayarlarınız kök dizindeki `.env` dosyasında bulunur.

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=umay_db
DB_USERNAME=root
DB_PASSWORD=
```

Ayrıca `config/database.php` dosyası üzerinden ikincil bağlantılar, SQLite ayarları veya farklı collation/charset yapılandırmaları yapabilirsiniz.

## Güvenlik (SQL Injection Koruması)

Umay Framework'te Query Builder veya Eloquent kullanarak yaptığınız TÜM sorgular, arka planda otomatik olarak **PDO Parameter Binding** (Parametre bağlama) işleminden geçer. Bu sayede SQL Injection saldırılarına karşı %100 koruma altındasınız.

```php
use App\Models\User;

// GÜVENLİ: Dışarıdan gelen veri parametre olarak bağlanır.
$user = User::where('email', $_POST['email'])->first();

// GÜVENLİ: İsimlendirilmiş parametre kullanımı
$users = DB::select('SELECT * FROM users WHERE status = :status', ['status' => 'active']);
```

> [!CAUTION]  
> Sadece `DB::raw()` veya `whereRaw()` kullanırken dışarıdan gelen (kullanıcı kaynaklı) verileri kesinlikle doğrudan sorgunun içine eklemeyin. Raw sorgularda bile PDO parametre bağlamayı kullanın.
