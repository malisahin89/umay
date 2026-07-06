# File Report: core/Csp.php

## Purpose
Content-Security-Policy (CSP) nonce holder.

## Overview
Manages a cryptographically secure random nonce for the current request. This nonce is used in CSP headers and rendered into `<script>` and `<style>` tags to prevent XSS.

## File Location
`core/Csp.php`

## Namespace
`Core`

## Classes
- `final class Csp`

## Properties
- `static ?string $nonce`: The nonce for the current request.

## Methods
- `nonce(): string`: Returns the current nonce, generating it lazily if it doesn't exist.
- `reset(): void`: Clears the current nonce, forcing a new one to be generated on the next access.

## Source References
- `core/Csp.php:1-45`
