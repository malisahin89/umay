# Template Engine

## Purpose
Documents the templating layer (League Plates) and the template file structure.

## Overview
Umay uses **League Plates** (`league/plates ^3.4`) as its template engine. Templates are plain PHP files under `views/`, rendered through `Core\View` (see `DOCS/VIEW_ENGINE.md`). Plates is instantiated with `views/` as its root and `views/partials/` registered under the `partials` namespace.

## Template Structure (`views/`)
- **`views/layouts/`** — base layouts (e.g. `base.php`) wrapping page content.
- **`views/partials/`** — reusable components: `alert.php`, `button.php`, `card.php`, `input.php`. Included via `$this->insert('partials::name', [params])`.
- **`views/errors/`** — error pages `403`, `404`, `500` (rendered by `Core\ExceptionHandler`).
- **`views/welcome.php`** — default landing template.

## Usage
- Render from PHP: `View::render('users/index', ['users' => $users])`.
- Inside templates, the helper functions registered by `Core\View` are available as `$this->fn(...)` (e.g. `$this->e()`, `$this->route()`, `$this->csrf()`).

## Cross References
- `DOCS/VIEW_ENGINE.md`, `DOCS/views/index.md`, `DOCS/views/layouts/index.md`, `DOCS/views/partials/index.md`, `DOCS/views/errors/index.md`
- `DOCS/ERROR_HANDLING.md` (error views)

## Source References
- `core/View.php:46-54` (engine + partials folder)
- `composer.json:31` (league/plates)
- `views/` directory
