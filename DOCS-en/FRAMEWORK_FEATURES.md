# Framework Features

This report lists the subsystems verified within the Umay framework source code.

| Feature | Status | Implementation Reference |
| :--- | :--- | :--- |
| **Routing** | Verified | `Core\Route` |
| **Request** | Verified | `Core\Request` |
| **Response** | Verified | `Core\ResponseBuilder` |
| **Middleware** | Verified | `Core\Route::runMiddleware` |
| **Dependency Injection** | Verified | `Core\Container` |
| **IoC Container** | Verified | `Core\Container` |
| **Events** | Verified | `Core\Events\Dispatcher` |
| **Service Providers** | Verified | `Core\ServiceProvider` |
| **Validation** | Verified | `Core\Validator` |
| **Session** | Verified | Native PHP sessions; `config/session.php`, `Core\Csrf`/`Core\Auth`/`Core\View` |
| **Cookie** | Verified | `setcookie()` in `Core\Auth` (`remember_me`) + native session cookie (`config/session.php`) |
| **Cache** | Verified | `Core\Cache` |
| **Database** | Verified | `Core\Database` |
| **ORM** | Verified | `Core\Model` |
| **Query Builder** | Verified | `Core\Database` |
| **Views** | Verified | `Core\View` |
| **Template Engine** | Verified | PHP-based templates |
| **Authentication** | Verified | `Core\Auth` |
| **Authorization** | Verified | `Core\Auth\HasApiTokens` (Abilities) |
| **Logging** | Verified | `Core\Logger` |
| **Configuration** | Verified | `config()` helper, `config/` directory |
| **Environment** | Verified | `.env` support via `vlucas/phpdotenv` |
| **Error Handling** | Verified | `Core\ExceptionHandler` |
| **Exception Handling** | Verified | `Core\Exceptions` |
| **Filesystem** | Verified | `Core\FileUpload` |
| **Upload** | Verified | `Core\FileUpload` |
| **Helpers** | Verified | `core/helpers.php` |
| **Console** | Verified | `Core\Console\Kernel` |
| **Queue** | Unable to verify | Not found in source code |
| **Scheduler** | Unable to verify | Not found in source code |
| **Notifications** | Unable to verify | Not found in source code |
| **Mail** | Verified | `Core\Mail\Mailer` |
| **Localization** | Unable to verify | Not found in source code |
| **Security** | Verified | `Core\Csrf`, `Core\Middleware\SecurityHeaders` |

## Subsystems Not Present
The following were searched for and not found in the analyzed source code: **Queue**, **Scheduler**, **Notifications**, **Localization**. No reports were generated for them.

## Scope Exclusions
Per the documentation rules, `vendor/` and every `*.md` file are excluded. Additionally excluded:
- **`docs/`** — the project's published GitHub Pages handbook (Markdown + `.nojekyll` + a landing `index.html`); it is documentation output, not framework source, so it is not reverse-engineered here.
- **`RAPOR/`** — a prior Markdown report set (removed from the working tree).
- **`storage/`** runtime artifacts (cache/logs/profiler) and `.gitkeep` placeholders.
