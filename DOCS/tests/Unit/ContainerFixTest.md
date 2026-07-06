# File Report: tests/Unit/ContainerFixTest.php

## Purpose
Regression tests for container edge cases.

## Overview
Verifies that `Core\Container` throws on circular dependencies and supports union type-hints during autowiring. Defines local fixture classes (`ClassA`, `ClassB`, `FileCache`, `RedisCache`, `ServiceWithUnion`) to drive the scenarios.

## File Location
`tests/Unit/ContainerFixTest.php`

## Namespace
`Tests\Unit`

## Classes
- `class ClassA`, `class ClassB` — circular-dependency fixtures (`:11`, `:15`)
- `class FileCache implements CacheDriver`, `class RedisCache implements CacheDriver` — union fixtures (`:21`, `:22`)
- `class ServiceWithUnion` — union type-hint fixture (`:24`)
- `class ContainerFixTest extends Tests\TestCase` (`:29`)

## Subject Under Test
- `Core\Container`

## Test Methods
- `test_circular_dependency_throws_exception` — `:36`
- `test_union_type_support` — `:46`

## Cross References
- **Tests:** `Core\Container` (see `DOCS/core/Container.md`)

## Source References
- `tests/Unit/ContainerFixTest.php:1-56`
