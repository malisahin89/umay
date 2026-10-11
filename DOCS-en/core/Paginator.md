# File Report: core/Paginator.php

## Purpose
Pagination utility for rendering navigation links.

## Overview
Wraps Eloquent's `LengthAwarePaginator` or raw data to generate Bootstrap 5 compatible pagination HTML. It handles page range calculations (sliding window) and preserves query parameters.

## File Location
`core/Paginator.php`

## Namespace
`Core`

## Imports
- `Illuminate\Contracts\Pagination\LengthAwarePaginator`

## Classes
- `class Paginator`

## Properties
- `int $currentPage`: Current active page.
- `int $lastPage`: Total number of pages.
- `int $perPage`: Items per page.
- `int $total`: Total number of items.
- `mixed $items`: The actual data items.
- `string $path`: The base URL path.
- `array $queryParams`: Query parameters excluding 'page'.

## Methods
- `fromEloquent(LengthAwarePaginator $paginator): static`: Creates a `Paginator` from an Eloquent pagination result.
- `make(mixed $items, int $total, int $perPage = 15, ?int $currentPage = null): static`: Creates a `Paginator` from raw data.
- `links(string $style = 'bootstrap'): string`: Renders pagination HTML ('bootstrap' or 'simple').
- `pageUrl(int $page): string`: Generates a URL for a specific page, preserving other query params.
- `getPageRange(): array`: Calculates which page numbers to show (always first, last, and current ±2).

## Source References
- `core/Paginator.php:1-288`
