# File Report: core/Exceptions/EntryNotFoundException.php

## Purpose
Custom exception for missing container entries.

## Overview
Thrown by `Container::get()` when a requested abstract is not registered in the container and cannot be auto-wired.

## File Location
`core/Exceptions/EntryNotFoundException.php`

## Namespace
`Core\Exceptions`

## Classes
- `class EntryNotFoundException extends \RuntimeException`

## Source References
- `core/Exceptions/EntryNotFoundException.php:1-10`
