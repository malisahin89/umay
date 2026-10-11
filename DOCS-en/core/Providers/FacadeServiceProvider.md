# File Report: core/Providers/FacadeServiceProvider.php

## Purpose
Service provider for registering Facade aliases.

## Overview
Registers the short global names (aliases) for all framework facades (e.g., `Cache`, `Auth`, `DB`) into the `config/app.php` aliases list.

## File Location
`core/Providers/FacadeServiceProvider.php`

## Namespace
`Core\Providers`

## Classes
- `class FacadeServiceProvider extends ServiceProvider`

## Methods
- `register(): void`: Binds the `Facade` logic into the container.
- `boot(): void`: Iterates through the configured aliases and registers them globally.

## Dependencies
- `Core\ServiceProvider` (Extends)
- `Core\Support\Facade` (Uses)

## Source References
- `core/Providers/FacadeServiceProvider.php:1-60`
