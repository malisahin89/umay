# Yanıtlar (Responses)

Umay Framework'te bir rotadan veya controller metodundan döneceğiniz her değer otomatik olarak bir HTTP yanıtına dönüştürülür.

## Temel Yanıtlar

En basit yöntem, doğrudan bir metin (String) döndürmektir:

```php
Route::get('/', function () {
    return 'Merhaba Dünya!';
});
```

Eğer bir dizi (Array) döndürürseniz, framework bunu otomatik olarak `JSON` formatına çevirir ve `Content-Type: application/json` başlığını ekler:

```php
Route::get('/api/users', function () {
    return [
        'status' => 'success',
        'data' => [
            ['id' => 1, 'name' => 'Ali'],
            ['id' => 2, 'name' => 'Ayşe']
        ]
    ];
});
```

## View (Görünüm) Döndürmek

Sayfa tasarımlarınızı `Core\View` sınıfı aracılığıyla döndürebilirsiniz:

```php
use Core\View;

public function index()
{
    $posts = Post::all();
    
    // views/posts/index.php dosyasını render eder
    View::render('posts.index', [
        'posts' => $posts
    ]);
}
```

## Yönlendirmeler (Redirects)

Bir işlemi tamamladıktan sonra kullanıcıyı başka bir rotaya yönlendirmek için `redirect()` helper fonksiyonunu kullanabilirsiniz.

```php
// Bir URL'ye yönlendirme
redirect('/dashboard');

// İsimlendirilmiş rotaya yönlendirme
redirect('profile.show');
```

Yönlendirme sırasında kullanıcıya bir bildirim mesajı (Flash Message) göstermek isterseniz:

```php
flash('success', 'Hesabınız başarıyla oluşturuldu!');
redirect('home');
```

View tarafında bu mesajı göstermek için:

```php
<?php if (has_flash('success')): ?>
    <div class="alert alert-success">
        <?= flash('success') ?>
    </div>
<?php endif; ?>
```
