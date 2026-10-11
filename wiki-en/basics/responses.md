# Responses

In Umay Framework, any value returned from a route or controller method is automatically converted into an HTTP response.

## Basic Responses

The simplest method is to return a string directly:

```php
Route::get('/', function () {
    return 'Hello World!';
});
```

If you return an array, the framework automatically converts it to `JSON` format and adds the `Content-Type: application/json` header:

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

## Returning a View

You can return page designs via the `Core\View` class:

```php
use Core\View;

public function index()
{
    $posts = Post::all();
    
    // Renders the views/posts/index.php file
    View::render('posts.index', [
        'posts' => $posts
    ]);
}
```

## Redirects

To redirect a user to another route after completing an operation, you can use the `redirect()` helper function.

```php
// Redirect to a URL
redirect('/dashboard');

// Redirect to a named route
redirect('profile.show');
```

If you want to show a notification message (Flash Message) during redirection:

```php
flash('success', 'Your account has been created successfully!');
redirect('home');
```

To display this message on the View side:

```php
<?php if (has_flash('success')): ?>
    <div class="alert alert-success">
        <?= flash('success') ?>
    </div>
<?php endif; ?>
```
