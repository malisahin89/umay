# File Report: core/Contracts/MiddlewareInterface.php

## Purpose
Interface for middleware classes.

## Overview
Ensures that all middleware implement the `handle` method, which allows them to be chained together in a pipeline to process requests before they reach the controller.

## File Location
`core/Contracts/MiddlewareInterface.php`

## Namespace
`Core\Contracts`

## Interfaces
- `interface MiddlewareInterface`

## Methods
- `handle(Request $request, \Closure $next): mixed`: Processes the request and returns the result of the next handler in the chain.

## Dependencies
- `Core\Request` (Uses)

## Source References
- `core/Contracts/MiddlewareInterface.php:1-35`
