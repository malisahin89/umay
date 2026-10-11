# Pagination

Umay Framework has a built-in pagination system to manage large data sets.

## Pagination with Eloquent

You can easily paginate using the `paginate()` method via Eloquent models:

```php
$posts = Post::where('status', 'published')->paginate(15);

return View::render('posts.index', ['posts' => $posts]);
```

## Usage in View

Call the `$posts->links()` method to generate pagination links. The system produces Bootstrap 5 compatible HTML by default:

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

## Manual Pagination

If you are using a data set other than Eloquent, you can use the `Paginator::make()` method:

```php
$data = [ /* raw data */ ];
$total = 100;
$perPage = 15;

$paginator = Paginator::make($data, $total, $perPage);
echo $paginator->links();
```
