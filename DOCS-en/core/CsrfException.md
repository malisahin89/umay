# File Report: core/CsrfException.php

## Purpose
Custom exception for CSRF failures.

## Overview
Thrown when a CSRF token is missing or invalid. Caught by `ExceptionHandler` to return a 419 response.

## File Location
`core/CsrfException.php`

## Namespace
`Core`

## Classes
- `class CsrfException extends \Exception`

## Source References
- `core/CsrfException.php:1-10`
