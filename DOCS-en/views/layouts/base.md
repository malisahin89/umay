# File Report: views/layouts/base.php

## Purpose
Base HTML layout template.

## Overview
Provides the standard HTML structure, including metadata, CSS imports (Tailwind CSS, Google Fonts, Font Awesome), and a placeholder for the body content.

## File Location
`views/layouts/base.php`

## Key Features
- **Dynamic Title**: Uses the `$title` variable passed from the controller or view.
- **CSP Integration**: Uses `$this->nonce()` to provide nonces for inline styles, ensuring compatibility with strict Content Security Policies.
- **Content Sections**: Uses `<?= $this->section('body') ?>` to inject content from child views.

## Source References
- `views/layouts/base.php:1-38`
