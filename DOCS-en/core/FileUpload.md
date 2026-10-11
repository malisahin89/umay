# File Report: core/FileUpload.php

## Purpose
Secure file upload and processing utility.

## Overview
Handles file uploads with strict security checks (MIME type, size, path traversal protection). It can automatically convert images to WebP format for optimization.

## File Location
`core/FileUpload.php`

## Namespace
`Core`

## Imports
- `Core\Facades\Log`

## Classes
- `class FileUpload`

## Properties
- `$allowedTypes`: List of permitted MIME types (JPEG, PNG, GIF, WebP).
- `$mimeToExt`: Mapping from MIME types to file extensions.
- `$maxSize`: Maximum allowed file size (2MB).
- `$quality`: WebP compression quality (70).

## Methods
- `upload(array $file, string $directory = 'uploads', bool $convertToWebP = true, ?string $customFilename = null): string`: The main upload process. Includes rate limiting, security checks, and optional WebP conversion.
- `rename(string $oldPath, string $newFilename): string|false`: Safely renames an uploaded file, ensuring the new path remains within the `public/` directory.
- `delete(string $filePath): bool`: Deletes a file after verifying it is inside the `public/` directory.

## Internal Workflow
1. **Rate Limiting**: Limits uploads to 10 per minute per IP using `Cache::atomic`.
2. **Security Checks**: Verifies `is_uploaded_file`, checks file size, and validates MIME type via `finfo`.
3. **Path Traversal Protection**: Rejects directories containing `..` or absolute paths.
4. **Secure Naming**: Generates a cryptographically random filename unless a custom one is provided (which is then sanitized).
5. **WebP Conversion**: Uses GD's `imagewebp` if enabled and supported.
6. **Path Verification**: `assertPathInsidePublic()` ensures all file operations are restricted to the `public/` folder.

## Dependencies
- `Core\Cache` (Uses for rate limiting)
- `Core\Facades\Log` (Uses for logging traversal attempts)

## Source References
- `core/FileUpload.php:1-298`
