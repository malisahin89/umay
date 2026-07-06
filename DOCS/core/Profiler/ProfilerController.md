# File Report: core/Profiler/ProfilerController.php

## Purpose
Controller for viewing the profiler data.

## Overview
Provides an endpoint (`/_profiler/{token}`) that reads a specific profile JSON file from storage and renders it as a detailed HTML report.

## File Location
`core/Profiler/ProfilerController.php`

## Namespace
`Core\Profiler`

## Classes
- `class ProfilerController`

## Methods
- `index(Request $request): void`: Renders the profiler index page listing recent profiles.
- `show(Request $request, string $token): void`: Renders the detailed report for a specific token.

## Dependencies
- `Core\Request` (Uses)
- `Core\Profiler\ProfilerStorage` (Uses)
- `Core\View` (Uses)

## Source References
- `core/Profiler/ProfilerController.php:1-120`
