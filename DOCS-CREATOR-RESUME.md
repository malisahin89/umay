# Documentation Resume Task

A previous run of `DOCS-CREATOR.md` produced an **incomplete** `DOCS/` output. This task resumes it: complete the missing work **without** redoing what already exists.

Read `DOCS-CREATOR.md` first — all of its rules (verification, evidence, §2.3 anti-loop, §3 read-group/generate-group, §11 group-by-group) apply here unchanged. This file only defines *what remains* and *the standards already established*.

---

## 1. Established Standards (do not deviate)

These conventions are already in force in `DOCS/`. Every new or corrected file must match them:

- **Language:** English. The base doc set is English (`# File Report: <path>`); do not introduce Turkish narrative files.
- **File report naming:** mirror the source path and strip the source extension → `.md` (e.g. `core/Route.php` → `DOCS/core/Route.md`). Non-`.php` files keep their full name plus `.md` (e.g. `composer.json` → `composer.json.md`).
  - **Single exception:** `public/index.php` → `DOCS/public/index.php.md`, because stripping `.php` would collide with the directory report `public/index.md`.
- **Directory report naming:** each directory's report is `index.md` inside its mirrored folder (§10.2 structure).
- **Format:** file reports follow §10.4; directory reports follow §10.2.
- **`.env` is never documented** (secrets). Document `.env.example` only.
- One source file = one Markdown file. No duplicates, no merged reports.

---

## 2. Already Complete — DO NOT Regenerate

- **Per-file reports** for `app/`, `config/`, `core/` (all subdirs), `database/`, `public/`, `routes/`, `views/` (all subdirs), and root files — already present.
- **Directory reports** (`index.md`) for every documented directory, plus `ROOT.md`.
- **General reports already generated:** `ARCHITECTURE.md`, `FRAMEWORK_FEATURES.md`, `REQUEST_LIFECYCLE.md`, `BOOT_PROCESS.md`, `ROUTING_SYSTEM.md`, `CONTAINER.md`, `SERVICE_PROVIDERS.md`.

Only revisit a completed file if §4 verification reveals a factual error.

---

## 3. Remaining Work

Perform in this order, applying §3 and §11 group-by-group discipline (finish each group fully before the next).

### 3.1 Missing source directories (analyze + document first)

Two source directories were never analyzed. Read every file (§5), then generate per-file reports and an `index.md` directory report for each:

- **`tests/`** (29 files, incl. `tests/Unit/`, `tests/Feature/`, `tests/bootstrap.php`, `tests/TestCase.php`) → `DOCS/tests/**`. Test files are explicitly in scope (§4).
- **`stubs/`** (11 `*.stub` files) → `DOCS/stubs/**`. These are code-generation templates; document each stub's purpose and placeholders. Name them `DOCS/stubs/<name>.stub.md`.

### 3.2 Missing general reports (Groups A–G, §11.1)

Generate only the ones below (the rest of each group is already done). Skip any whose subsystem cannot be verified and record the omission in `FRAMEWORK_FEATURES.md`.

- **Group A:** `PACKAGE_STRUCTURE.md`
- **Group B:** `MIDDLEWARE.md`, `DATABASE.md`, `ORM.md`
- **Group C:** `CONFIGURATION.md`, `CACHE.md`, `SESSION.md`, `COOKIE.md`, `FILESYSTEM.md`
- **Group D:** `SECURITY.md`, `AUTHENTICATION.md`, `AUTHORIZATION.md`, `VALIDATION.md`
- **Group E:** `VIEW_ENGINE.md`, `TEMPLATE_ENGINE.md`
- **Group F:** `ERROR_HANDLING.md`, `LOGGING.md`, `PERFORMANCE.md`
- **Group G:** `DEPENDENCY_GRAPH.md`, `CLASS_GRAPH.md`, `CALL_GRAPH.md`

> Note: earlier drafts placed some subsystem overviews inside directory folders (e.g. a "Views System" / "Database System" narrative). Those non-conforming files were removed. Their content belongs in the general reports above (`VIEW_ENGINE.md`, `TEMPLATE_ENGINE.md`, `DATABASE.md`, `ORM.md`, `ERROR_HANDLING.md`) — re-derive it from source.

### 3.3 Missing index & matrix reports (Groups H–J, §11.2)

- **Group H:** `CLASS_INDEX.md`, `METHOD_INDEX.md`
- **Group I:** `ROUTE_MATRIX.md`, `CONFIGURATION_MATRIX.md`, `SERVICE_MATRIX.md`
- **Group J:** `GLOSSARY.md`

### 3.4 Regenerate `INDEX.md` LAST

The current `INDEX.md` is incomplete (it omits `database/`, `views/`, `public/`, `storage/`, `tests/`, `stubs/` file reports and the root files). After **all** of the above is finished, rebuild `INDEX.md` from scratch so it links to every generated report — general reports (all groups), index/matrix reports, and every directory/file report. `INDEX.md` must be the final file produced (§3 step 6, §12).

---

## 4. Completion Validation

Before declaring done, run the §12 checklist, and additionally confirm:

- `tests/` and `stubs/` each have a full set of file reports and an `index.md`.
- All reports listed in §3.2 and §3.3 exist, or their omission is recorded in `FRAMEWORK_FEATURES.md`.
- No file uses the removed non-standard names (`system.md`, `error_views.md`, `partials.md`) and no duplicate `*.php.md` file reports exist (except the documented `public/index.php.md`).
- No documentation for `.env` exists.
- `INDEX.md` was regenerated last and links to every report.
