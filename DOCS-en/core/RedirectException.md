# File Report: core/RedirectException.php

## Purpose
Exception used to terminate the request flow after a redirect.

## Overview
A specialized exception that is caught by the `ExceptionHandler` (or ignored) to stop the current execution path once a `Location` header has been sent.

## File Location
`core/RedirectException.php`

## Namespace
`Core`

## Classes
- `class RedirectException extends TerminateException`

## Source References
- `core/RedirectException.php:1-11`
