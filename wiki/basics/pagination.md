# Sayfalama (Pagination)

Umay Framework, büyük veri setlerini yönetmek için yerleşik bir sayfalama sistemine sahiptir.

## Eloquent ile Sayfalama

Eloquent modelleri üzerinden `paginate()` metodunu kullanarak kolayca sayfalama yapabilirsiniz:

```php
$posts = Post::where('status', 'published')->paginate(15);

return View::render('posts.index', ['posts' => $posts]);
```

## Görünümde Kullanım

Sayfalama linklerini oluşturmak için `$posts->links()` metodunu çağırın. Sistem varsayılan olarak **Bootstrap 5** uyumlu HTML üretir:

```php
<!-- views/posts/index.php -->
<div class="post-list">
    <?php foreach ($posts->items() as $post): ?>
        <div><?= $post->title ?></div>
    <?php endforeach; ?>
</div>

<div class="pagination-wrapper">
    <?= $posts->links() ?>
</div>
```

## Manuel Sayfalama

Eğer Eloquent dışı bir veri seti kullanıyorsanız, `Paginator::make()` metodunu kullanabilirsiniz:

```php
$data = [ /* raw data */ ];
$total = 100;
$perPage = 15;

$paginator = Paginator::make($data, $total, $perPage);
echo $paginator->links();
```
