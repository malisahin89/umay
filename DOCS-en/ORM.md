# ORM

## Purpose
Documents the object-relational mapping layer built on Eloquent.

## Overview
`Core\Model` is an abstract base class extending `Illuminate\Database\Eloquent\Model`. Application models (`App\Models\*`) extend it and gain the full Eloquent feature set through the connection configured by `Core\Database`.

## Base Model (`Core\Model`)
- `abstract class Model extends EloquentModel`
- Sets `public $timestamps = true`.
- Documented supported features (from Eloquent): eager loading (`with`), query scopes, accessors/mutators, attribute casting (`$casts`), soft deletes (`SoftDeletes` trait), polymorphic and many-to-many relations, `hasManyThrough`, model events, and Collection methods.

## Supporting Components
| Component | Role | Source |
|-----------|------|--------|
| `Core\Concerns\SoftDeletes` | Soft-delete trait (`deleted_at`) | `core/Concerns/SoftDeletes.php` |
| `Core\Migration` | Base for schema migrations (`up`/`down`, `execute`, `tableExists`) | `core/Migration.php` |
| `Core\Migrator` | Runs/tracks migrations via a `migrations` table | `core/Migrator.php` |
| `Core\Seeder` | Base for database seeders (`run`) | `core/Seeder.php` |
| `Core\Factory` | Fake-data factories (`definition`, Faker proxy) | `core/Factory.php` |
| `Core\Paginator` | Pagination over result sets | `core/Paginator.php` |

Application example: `App\Models\User` (see `DOCS/app/Models/User.md`).

## Cross References
- **Connection:** `Core\Database` (see `DOCS/DATABASE.md`)
- **Migrations/Seeders/Factories:** `DOCS/database/index.md`
- **Base model report:** `DOCS/core/Model.md`

## Source References
- `core/Model.php:27-30`
- `core/Concerns/SoftDeletes.php`, `core/Migration.php`, `core/Migrator.php`, `core/Seeder.php`, `core/Factory.php`, `core/Paginator.php`
