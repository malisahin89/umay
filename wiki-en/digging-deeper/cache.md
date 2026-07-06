# Caching (Cache)

To increase your application's performance, it is very important to cache expensive database queries or slow service responses.

Umay Framework has a simple and fast file-based (File) Cache mechanism.

## Cache Facade Usage

You can access caching operations most quickly via `Core\Facades\Cache`.

### Saving Data (Set)
```php
use Core\Facades\Cache;

// Stores data with the key 'users_list' for 3600 seconds (1 hour) in cache
Cache::set('users_list', $usersArray, 3600);
```

### Reading Data (Get)
```php
// Returns null if cache does not exist or has expired
$users = Cache::get('users_list');

// If you want to provide a default value:
$users = Cache::get('users_list', []);
```

### Checking Data (Has)
```php
if (Cache::has('users_list')) {
    echo "Coming from cache!";
}
```

### Remember 

One of the methods you will use most often is `remember`. This method directly retrieves data if it's in the cache; otherwise, it runs the Closure (anonymous) function you provide, fetches the data, saves it to the cache, and then returns the result.

```php
$users = Cache::remember('users_list', 3600, function () {
    return User::all();
});
```
This single line of code instantly solves problems like N+1 or database exhaustion.

### Deleting and Clearing (Forget / Flush)

```php
// Deletes only a single key
Cache::forget('users_list');

// Completely cleans the entire cache folder (storage/cache)!
Cache::flush();
```

> [!TIP]
> When you have changed data (for example, a new user is added to the system), you can invalidate the old list by calling `Cache::forget('users_list')` immediately after saving the relevant model. Thus, the next listing request will immediately go to the database and cache the updated data.
