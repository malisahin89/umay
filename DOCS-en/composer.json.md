# File Report: composer.json

## Purpose
Project dependency management and autoloading configuration.

## Overview
Defines project metadata, required dependencies, development tools, and PSR-4 autoloading mapping for the application.

## File Location
`composer.json`

## Dependencies
- `php`: >=8.2
- `illuminate/database`: ^10.0
- `illuminate/events`: ^10.0
- `illuminate/pagination`: ^10.0
- `league/plates`: ^3.4
- `vlucas/phpdotenv`: ^5.6

## Dev Dependencies
- `laravel/pint`: ^1.29
- `phpstan/phpstan`: ^2.1
- `phpunit/phpunit`: ^10.0

## Autoloading
- `App\`: `app/`
- `Core\`: `core/`
- `Database\Seeders\`: `database/seeders/`
- `Database\Factories\`: `database/factories/`
- `Tests\`: `tests/` (dev)

## Source References
- `composer.json:1-65`
