# Eloquent ORM

Eloquent ORM is a structure that makes interacting with your database incredibly easy. Every database table has a "Model" class (based on the Active Record pattern) used to interact with the database.

## Defining a Model

You can use the Umay CLI to create a new Model:

```bash
php umay make:model Post
```

The resulting model is located in the `app/Models/` directory:

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // Which table to use (Automatic plural of class name, but you can specify it)
    protected $table = 'posts';

    // Fields allowed for Mass Assignment
    protected $fillable = ['user_id', 'title', 'body'];
}
```

## Retrieving Data

```php
use App\Models\Post;

// Get all posts
$posts = Post::all();

// Find post with ID 1 (Returns null if not found)
$post = Post::find(1);

// Retrieve based on specific condition
$publishedPosts = Post::where('status', 'published')
                      ->orderBy('created_at', 'desc')
                      ->take(10)
                      ->get();
```

## Inserting / Updating Models

You can create a model object and save it using the `save()` method.

```php
// NEW INSERTION
$post = new Post();
$post->title = 'New Article';
$post->body = 'Article content here...';
$post->save();

// UPDATE
$post = Post::find(1);
$post->title = 'Updated Title';
$post->save();
```

### Mass Assignment

To create a record in one go by providing an array, you can use the `create` method. This requires the `$fillable` array to be defined in the model.

```php
$post = Post::create([
    'title' => 'A great post',
    'body'  => 'Content...'
]);
```

## Relationships

The strongest side of Eloquent is relationships. You can define ties between tables (1-1, 1-N, N-N) in your models.

Assume the `User` model has many `Post`s:

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

Usage:
```php
$user = User::find(1);

// Get all posts of the user
$userPosts = $user->posts;

// Access the author's name of the post (Automatic join)
$post = Post::find(1);
echo $post->user->name;
```

> [!TIP]
> **N+1 Problem:** Retrieving the author for every post in a loop (`$post->user`) causes hundreds of database queries. To solve this, use Eager Loading (`with`):
> `$posts = Post::with('user')->get();`
