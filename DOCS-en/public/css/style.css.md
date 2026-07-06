# File Report: public/css/style.css

## Purpose
Base stylesheet served from the web root.

## Overview
A small, standalone CSS file defining the layout and component styles for a basic admin-style UI: page body, header bar, a fixed sidebar with navigation links, the main content area, and alert boxes.

## File Location
`public/css/style.css`

## Selectors / Rules
- `body` — base font (`'Segoe UI', sans-serif`), light background (`#f4f6f9`), no margin.
- `header` — dark bar (`#343a40`), white text, flex layout (space-between, centered).
- `.sidebar` — fixed left sidebar, 220px wide, full height, dark background (`#222d32`).
- `.sidebar a` / `.sidebar a:hover` — block navigation links, hover background `#1a2226`.
- `.main-content` — offset by the sidebar width (`margin-left: 220px`), padded.
- `.alert`, `.alert-success`, `.alert-error` — notification boxes with success (`#d4edda`/`#155724`) and error (`#f8d7da`/`#721c24`) color pairs.

## External Usage
- Served statically from the web root; referenced by templates via the `asset()` helper (see `DOCS/core/View.md`, `asset()` in `DOCS/core/helpers.md`).

> No verified `<link>` reference to `css/style.css` was found in the analyzed `views/` templates; it is served as a static asset.

## Source References
- `public/css/style.css:1-57`
