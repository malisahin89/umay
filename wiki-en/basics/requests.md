# Requests

In Umay Framework, HTTP requests coming to your application are captured and managed by the `Core\Request` object.

## Accessing the Request Object

In Controller methods, you can access the `Request` object via Dependency Injection:

```php
namespace App\Controllers;

use Core\Request;

class UserController
{
    public function store(Request $request)
    {
        $name = $request->input('name');
    }
}
```

## Retrieving Input

Various methods are available for retrieving form data (POST/GET) from the user:

```php
// Get only POST data (Default value: 'Guest')
$name = $request->post('name', 'Guest');

// Get only GET (Query String) data
$page = $request->get('page', 1);

// Get data regardless of GET or POST
$email = $request->input('email');

// Retrieve all data as an Array
$allData = $request->all();

// Retrieve only specific fields
$credentials = $request->only(['email', 'password']);

// Retrieve all data except specific fields
$data = $request->except(['_csrf', 'password_confirmation']);
```

## File Uploads

The `Core\FileUpload` class is used for file uploads.

```php
use Core\FileUpload;

public function uploadAvatar(Request $request)
{
    // Is there a file?
    if ($request->hasFile('avatar')) {
        
        $uploader = new FileUpload();
        
        // Allow only JPG and PNG (Security)
        $uploader->setAllowedMimeTypes(['image/jpeg', 'image/png']);
        
        // Upload to storage/uploads/avatars directory
        $path = $uploader->upload('avatar', 'avatars');
        
        return 'File uploaded: ' . $path;
    }
}
```

> [!IMPORTANT]  
> The Umay Framework `FileUpload` module has built-in protection against Path Traversal attacks. It automatically cleans null bytes (`\0`) and `../` characters.

## Data Validation

Before saving incoming data, you can validate it against rules using the `Core\Validator` class:

```php
use Core\Validator;

$validator = new Validator();

$data = $request->all();
$rules = [
    'username' => 'required|alphanumeric',
    'email'    => 'required|email|unique:users,email',
    'password' => 'required|min:8'
];

if (! $validator->validate($data, $rules)) {
    // Validation failed!
    $errors = $validator->errors();
    
    // Write errors to session and return to previous form
    flash('errors', $errors);
    back();
    return;
}

// If successful, save...
```
