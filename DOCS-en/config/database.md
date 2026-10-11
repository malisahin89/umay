# File Report: config/database.php

## Purpose
Database and Eloquent configuration.

## Overview
Loads `.env` variables using `phpdotenv`, defines database connection settings (MySQL by default), and initializes the Eloquent ORM via `Core\Database::init()`.

## File Location
`config/database.php`

## Configuration
- `driver`: 'mysql'
- `host`: From `DB_HOST` (default: '127.0.0.1')
- `port`: From `DB_PORT` (default: '3306')
- `database`: From `DB_DATABASE` (default: 'umay')
- `username`: From `DB_USERNAME` (default: 'root')
- `password`: From `DB_PASSWORD` (default: '')
- `charset`: From `DB_CHARSET` (default: 'utf8mb4')
- `collation`: From `DB_COLLATION` (default: 'utf8mb4_unicode_ci')
- `prefix`: ''
- `strict`: true

## Internal Workflow
1. Initializes `Dotenv` to load `.env` file safely.
2. Builds the `$config` array.
3. Calls `Core\Database::init($config)` to set up the database connection.
4. Returns the `$config` array for use via the `config()` helper.

## Dependencies
- `Core\Database` (Uses)
- `Dotenv\Dotenv` (Uses)

## Source References
- `config/database.php:1-42`
