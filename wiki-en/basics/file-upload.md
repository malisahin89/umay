# File Upload

Umay Framework provides a comprehensive `FileUpload` class for secure file uploads.

## Basic Upload Process

Use the `FileUpload::upload()` method to upload a file:

```php
$file = $_FILES['avatar'];

try {
    $path = FileUpload::upload(
        $file, 
        'avatars', // Destination directory (public/avatars)
        true,      // Convert to WebP (Optimize)
        'user_' . $userId // Optional custom filename
    );
    
    echo "File uploaded successfully: " . $path;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

## Security Features

The system automatically performs the following security checks during file upload:

1. **MIME Type Check**: Only permitted formats (JPEG, PNG, GIF, WebP) are accepted.
2. **Size Limit**: A default limit of 2MB is applied.
3. **Path Security (Path Traversal)**: Filenames are sanitized, and dangerous characters like `..` are blocked to ensure files are saved only in the `public/` directory.
4. **Rate Limiting**: A maximum limit of 10 uploads per minute per IP is applied.

## File Management

### Renaming Files
```php
FileUpload::rename('uploads/old.jpg', 'new_name.jpg');
```

### Deleting Files
```php
FileUpload::delete('uploads/file.jpg');
```
