# Query Builder

Umay Framework uses the Illuminate Database Query Builder. This allows you to write complex queries in a secure and readable way.

You can access the query builder using Eloquent Models or directly via the `DB` class (the Container).

## Data Retrieval (Select)

```php
use Illuminate\Database\Capsule\Manager as DB;

// Retrieve all data from a table
$users = DB::table('users')->get();

// Retrieve only the first row that matches a specific condition
$user = DB::table('users')->where('email', 'admin@example.com')->first();

// Retrieve only specific columns
$users = DB::table('users')->select('name', 'email')->get();
```

## Where Conditions

```php
// Simple match (status = active)
DB::table('users')->where('status', 'active')->get();

// Using an operator (votes > 100)
DB::table('users')->where('votes', '>', 100)->get();

// LIKE operator (Starting with 'A')
DB::table('users')->where('name', 'LIKE', 'A%')->get();

// AND and OR conditions
DB::table('users')
    ->where('votes', '>', 100)
    ->orWhere('name', 'John')
    ->get();
```

## Data Insertion (Insert)

```php
DB::table('users')->insert([
    'name' => 'Can',
    'email' => 'can@example.com',
    'status' => 'active'
]);

// Retrieve the ID of the inserted record
$id = DB::table('users')->insertGetId([
    'name' => 'Elif',
    'email' => 'elif@example.com'
]);
```

## Updating (Update)

```php
// Set only those with status=pending to active
DB::table('users')
    ->where('status', 'pending')
    ->update(['status' => 'active']);
```

## Deletion (Delete)

```php
// Delete a specific user
DB::table('users')->where('id', 5)->delete();

// Clear the entire table
DB::table('users')->truncate();
```

## Pagination

Splitting hundreds of records returned from the database into pages is done with a single method:

```php
// Retrieve 15 records per page
$users = DB::table('users')->paginate(15);
```

On the View (Template) side, Umay Framework's built-in `paginate()` helper and `Core\Paginator` class are used to render the pagination. For more, see the Models section.
