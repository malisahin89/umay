# Views and Template Engine

Umay Framework uses a great template engine called **Plates** for view files, which uses completely native PHP code. You don't need to learn a new syntax like Blade or Twig; you write the PHP you already know, but in a much more organized architecture.

All view files are located in the `views/` directory with a `.php` extension.

## Basic Usage

When creating a view, the basic rule is to print variables through the `htmlspecialchars()` filter (for XSS protection).

`views/profile.php` file:
```php
<h1>Welcome, <?= $this->e($name) ?></h1>
```

Calling it from the Controller:
```php
View::render('profile', ['name' => '<script>alert("XSS")</script>']);
// The output will be safely encoded.
```

## Using Layouts

You should put repeating `<html>`, `<head>`, `<body>` tags, header, and footer into a main Layout file.

### Defining a Main Layout
The starting skeleton comes with a ready-to-use `views/layouts/base.php` that has a `body` section:
```php
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $this->e($title ?? 'Umay') ?></title>
</head>
<body>
    <!-- Content of sub-pages will go here -->
    <?= $this->section('body') ?>
</body>
</html>
```

### Using Layout in Sub-pages
`views/home.php` file:
```php
<?php $this->layout('layouts/base', ['title' => 'Home Page']) ?>

<?php $this->start('body') ?>
    <h1>Welcome to the Home Page</h1>
    <p>This is the content area of the page.</p>
<?php $this->end() ?>
```

## Partials (Includes)

You can take small, repeating HTML blocks (buttons, alert boxes, menus) into separate view files and include them in main pages.

`views/partials/alert.php` file:
```php
<div class="alert alert-<?= $this->e($type) ?>">
    <?= $this->e($message) ?>
</div>
```

To call it inside a page, use `$this->insert()`:
```php
<?= $this->insert('partials/alert', ['type' => 'danger', 'message' => 'An error occurred!']) ?>
```

## Global Data Sharing (View::share)

Instead of defining some data (e.g., site name, user preferences, or global links) repeatedly in every view and layout, you can distribute them to all views from a single point.

```php
// At the start of the application (inside a Provider or Middleware)
View::share('siteName', 'Umay Framework');
View::share(['langUrls' => ['tr' => '/tr', 'en' => '/en']]);
```

You can access these shared data directly within the template:
```php
<footer><?= $this->e($siteName) ?> &copy; <?= date('Y') ?></footer>
```

**Precedence:** Shared data < Page data < Framework globals. This means data passed from the page will override shared data of the same name.
