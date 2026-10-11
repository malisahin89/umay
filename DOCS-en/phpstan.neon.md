# File Report: phpstan.neon

## Purpose
PHPStan static analysis configuration.

## Overview
Configures the PHPStan analysis level and specifies which paths to analyze and exclude.

## File Location
`phpstan.neon`

## Configuration
- `level`: max
- `paths`: `core`, `app`
- `excludePaths`: `storage/*`, `vendor/*`
- `includes`: `phpstan-baseline.neon`

## Source References
- `phpstan.neon:1-11`
