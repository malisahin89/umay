# Eloquent ORM

Eloquent ORM, veritabanınızla etkileşim kurmanızı inanılmaz kolaylaştıran bir yapıdır. Her veritabanı tablonuz, veritabanı ile etkileşim kurmak için kullanılan bir "Model" sınıfına (Active Record deseninde) sahiptir.

## Model Tanımlama

Yeni bir Model oluşturmak için Umay CLI kullanabilirsiniz:

```bash
php umay make:model Post
```

Oluşan model `app/Models/` dizininde yer alır:

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // Hangi tabloyu kullanacağı (Otomatik olarak sınıf adının çoğulu alınır ama siz de belirtebilirsiniz)
    protected $table = 'posts';

    // Toplu atamaya (Mass Assignment) izin verilen alanlar
    protected $fillable = ['user_id', 'title', 'body'];
}
```

## Veri Çekme (Retrieve)

```php
use App\Models\Post;

// Tüm postları getir
$posts = Post::all();

// ID'si 1 olan postu bul (Bulamazsa null döner)
$post = Post::find(1);

// Belirli bir koşula göre getir
$publishedPosts = Post::where('status', 'published')
                      ->orderBy('created_at', 'desc')
                      ->take(10)
                      ->get();
```

## Model Ekleme / Güncelleme (Insert / Update)

Model nesnesi oluşturup `save()` metodu ile kaydedebilirsiniz.

```php
// YENİ EKLEME
$post = new Post();
$post->title = 'Yeni Makale';
$post->body = 'Makale içeriği burada...';
$post->save();

// GÜNCELLEME
$post = Post::find(1);
$post->title = 'Güncellenmiş Başlık';
$post->save();
```

### Toplu Atama (Mass Assignment)

Güvenli bir şekilde dizi (array) vererek tek seferde oluşturmak için `create` metodunu kullanabilirsiniz. Bunun çalışması için modelde `$fillable` dizisinin tanımlı olması şarttır.

```php
$post = Post::create([
    'title' => 'Harika bir yazı',
    'body'  => 'İçerik...'
]);
```

## İlişkiler (Relationships)

Eloquent'in en güçlü yanı ilişkilerdir. Tablolar arasındaki bağları (1-1, 1-N, N-N) modellerinizde tanımlayabilirsiniz.

`User` modelinin birden çok `Post`'a sahip olduğunu düşünelim:

```php
// app/Models/User.php
public function posts()
{
    return $this->hasMany(Post::class);
}

// app/Models/Post.php
public function user()
{
    return $this->belongsTo(User::class);
}
```

Kullanımı:
```php
$user = User::find(1);

// Kullanıcının tüm postlarını getir
$userPosts = $user->posts;

// Postun yazarının adına ulaş (Otomatik join)
$post = Post::find(1);
echo $post->user->name;
```

> [!TIP]
> **N+1 Problemi:** Bir döngü içinde her post için yazarını çekmek (`$post->user`) veritabanına yüzlerce sorgu atılmasına neden olur. Bunu çözmek için Eager Loading (`with`) kullanın:
> `$posts = Post::with('user')->get();`
