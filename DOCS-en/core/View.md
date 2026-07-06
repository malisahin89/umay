# View.php

## Purpose
The `View` class is a wrapper for the Plates template engine, providing a simplified API for rendering HTML templates and a rich set of helper functions.

## Metadata
- **Namespace**: `Core`
- **File Location**: `core\View.php`

## Dependencies
- `League\Plates\Engine`
- `Core\Csrf`
- `Core\Csp`

## Key Methods
- `render(string $template, array $data)`: Renders a template file, injects global data (errors, flash messages), and echoes the output.
- `share(string|array $key, mixed $value = null)`: Shares data globally across all views and layouts. Precedence: shared < page $data < framework globals.
- `engine()`: Returns the shared Plates Engine instance.

## Template Helper Functions
The class registers several helper functions within the Plates engine for use in templates:
- **Security**: `csrf()` (hidden input), `csrf_token()` (raw value), `e()` (XSS escape), `nonce()` (CSP nonce).
- **HTTP**: `method(string $method)` (for PUT/PATCH/DELETE spoofing).
- **Routing/Assets**: `route($name, $params)`, `url($name)`, `asset($path)`.
- **Input**: `old($key, $default)` (retrieves flashed input).
- **Auth**: `auth()` (current user), `guest()` (boolean check).
- **Config**: `config($key, $default)`, `app_name()`.
- **Utilities**: `now($format)`, `class_if($classes)` (conditional CSS classes).
- **Validation**: `has_error($field)`, `error($field)`.
- **Flash**: `flash($key)` (reads and clears flash messages).
- **Debug**: `dd($value)` (dump and die).

## Internal Workflow (Render)
1. **Session Start**: Ensures the session is active.
2. **Flash Consumption**: Reads `success` and `error` flash messages from the session and stores them in `$consumedFlash` to ensure consistency between globals and helpers.
3. **Global Data Injection**: Merges shared data (from `share()`), page-specific data, and framework globals (`title`, `errors`, `success`, `error`, `user_id`).
4. **Profiler Integration**: If profiling is enabled:
    - Measures the render time.
    - Records the view in the profiler.
    - Injects the `DebugBar` HTML toolbar before the `</body>` tag.
5. **Post-Render Cleanup**: Clears `_old` and `_flash_errors` from the session.
