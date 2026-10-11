# Directory Structure

The directory structure of Umay Framework is designed to classify your project logically and provide a clean architecture.

## Root Directory

The root directory is the main carrier of your project and houses the framework core (Core), business logic (App), and configurations.

```text
umay/
├── app/          # Application business logic (Controllers, Models, etc.)
├── config/       # Configuration files (app, database, cache)
├── core/         # Framework core (Router, Container, Eloquent Setup)
├── database/     # Migrations, Factories and Seeders
├── public/       # Public directory (index.php, CSS, JS)
├── routes/       # Route definitions (web.php, api.php)
├── storage/      # Logs, cache files and compiled views
├── stubs/        # Template files for Umay CLI
├── tests/        # PHPUnit tests (Unit and Feature)
├── vendor/       # Composer dependencies
└── views/        # Plates template views (.php)
```

## `app/` Directory

The heart of your application is the `app/` directory. You will perform most of your development in this folder. The starting skeleton is **intentionally bare** — only basic building blocks are provided, and you add the rest:

- **`Controllers/`**: Controllers that handle HTTP requests. A base `Controller` class comes in the skeleton.
- **`Middleware/`**: Intermediate layers that filter requests. A generic `ThrottleMiddleware` comes in the skeleton; add your own `Auth`/`Admin` middleware with `php umay make:middleware`.
- **`Models/`**: Eloquent models. A generic `User` model (`name`, `email`, `password`) comes in the skeleton; it implements `Core\Contracts\Authenticatable`.
- **`Providers/`**: Service providers — `EventServiceProvider` (event→listener mapping) and `RouteServiceProvider` (loads route files).
- **`Services/`**: Empty directory for your own service/business logic classes.

> Directories like `Events/`, `Listeners/`, `Mail/`, `Requests/` do not come in the skeleton; they are automatically created as needed with `php umay make:event`, `make:listener`, `make:mail`, `make:request` commands.

## `core/` Directory

This folder contains the inner workings of the Umay Framework. Unless you are a framework developer, it is recommended not to touch this folder:

- **`Application.php`**: System bootstrap and Service Container management.
- **`Route.php`**: URL routing engine.
- **`Database.php`**: Eloquent wrapper.
- All other core components (Request, Response, View, Session).

> [!NOTE]
> Umay Framework architecture provides you with best practices by default so that you stay away from complexity while writing code. Make sure you follow PSR-4 standards when intervening in directories.
