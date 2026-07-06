# File Report: core/Factory.php

## Purpose
Base class for Model Factories.

## Overview
Provides a fluent API for creating dummy model instances and persisting them to the database for testing or seeding. It supports "states" to modify the default definition.

## File Location
`core/Factory.php`

## Namespace
`Core`

## Classes
- `abstract class Factory`
- `class FakerProxy` (Internal helper for generating dummy data)

## Properties
- `string $model`: The FQCN of the model this factory creates.
- `int $count`: Number of instances to create.
- `array $states`: Queue of attribute overrides (states).

## Methods
- `definition(): array`: Abstract method returning the default attribute set.
- `count(int $count): static`: Sets the number of items to create.
- `state(array $attributes): static`: Adds an attribute override to the state queue.
- `make(array $override = []): mixed`: Creates model instances without saving them to the DB.
- `create(array $override = []): mixed`: Creates model instances and saves them to the DB.
- `raw(array $override = []): array`: Returns the resolved attribute array without creating a model.
- `register(string $modelClass, string $factoryClass): void`: Explicitly maps a model to a factory class.
- `forModel(string $modelClass, int $count = 1): static`: Resolves a factory for a given model using registry or naming conventions.
- `faker(): FakerProxy`: Returns a proxy for generating random data.

## Internal Workflow
1. `resolveAttributes()`: Merges the default `definition()`, any applied `states`, and the provided `$override` array.
2. `newModel()`: Instantiates the model and uses `forceFill()` to populate it, ensuring guarded fields are also set.

## Dependencies
- `Core\FakerProxy` (Uses)

## Source References
- `core/Factory.php:1-281`
