---
trigger: always_on
description: Genel kodlama prensipleri — düşün, basit tut, cerrahi değişiklik yap, hedefe odaklan, ölü kodu düzenleme. Türkçe iletişim.
---

# Be CAREFUL

## 1. Think Before Coding

**Don't assume. Don't hide confusion. Surface tradeoffs.**

- State assumptions explicitly. If uncertain, ask.
- Multiple interpretations exist? Present them — don't pick silently.
- Simpler approach exists? Say so. Push back when warranted.
- Something unclear? Stop, name it, ask.

## 2. Simplicity First

**Minimum code that solves the problem. Nothing speculative.**

- No features beyond what was asked.
- No abstractions for single-use code.
- No "flexibility" or "configurability" that wasn't requested.
- No error handling for impossible scenarios.
- If 200 lines could be 50, rewrite. Ask: "Would a senior engineer call this overcomplicated?" If yes, simplify.

## 3. Surgical Changes

**Touch only what you must. Clean up only your own mess.**

- Don't "improve" adjacent code, comments, or formatting.
- Don't refactor things that aren't broken.
- Match existing style, even if you'd do it differently.
- Notice unrelated dead code? Mention it — don't delete it.
- Remove only the imports/variables/functions YOUR changes orphaned.
- Don't remove pre-existing dead code unless asked.
- Test: every changed line should trace to the user's request.

## 4. No Blind Writes

**Never edit blindly. Read before write.**

- Read the file before any change.
- Verify current exact state before overwriting any part.
- Prevents conflicts and stale-context assumptions.

## 5. Idempotency & Side-Effects

**Write predictable code. Analyze blast radius.**

- **Idempotency:** Safe to run multiple times (e.g. `IF NOT EXISTS` in SQL, reversible migrations).
- **Side-effects:** If a change alters a return type, signature, or thrown exception, update all callers or warn the user immediately.

## 6. Goal-Driven Execution

**Define success criteria. Loop until verified.**

Turn tasks into verifiable goals:

- "Add validation" → "Write tests for invalid inputs, then make them pass"
- "Fix the bug" → "Write a test that reproduces it, then make it pass"
- "Refactor X" → "Ensure tests pass before and after"

For multi-step tasks, state a brief plan:

```
1. [Step] → verify: [check]
2. [Step] → verify: [check]
```

## 7. Dead Code Check (Before Every Edit AND Listing)

**Before touching OR listing ANY file, verify it's alive. Never edit or report dead code without labeling it.**

Applies to: editing a file/function, listing files in scan results, suggesting files for bulk changes. Checklist before including a file in output:

1. **File references:** `grep` project-wide for imports/requires/includes/dynamic refs.
2. **Function/method calls:** `grep` the function/class name project-wide.
3. **Route usage:** confirm the route is registered and reachable from the entry point.
4. **Backup/legacy files:** names with `_backup`, `_old`, `_eski`, `_copy`, `_v2`, `test_`, or "unused" comments → dead code, don't edit.
5. **Config-driven files:** confirm the required config flag/env var is active.

### Decision Matrix

| Situation                                 | Action                                        |
| :---------------------------------------- | :-------------------------------------------- |
| File/function called from multiple places | ✅ Edit, check side effects                   |
| File/function called from one place       | ✅ Edit, also review the caller               |
| File/function called from nowhere         | ⛔ **Don't edit — dead code.** Inform user    |
| Backup/legacy file                        | ⛔ **Don't edit — inform user**               |
| Not sure                                  | ⚠️ Ask user, don't edit without confirmation  |

## 8. Safe File Operations

**Never delete, create, or overwrite files recklessly.**

- **Don't delete files without asking.**
- **Don't write files to random or unrelated locations.**
- **Don't write/overwrite files via terminal** (`echo`, `cat`, etc.). Use editor tools (`replace_file_content`, etc.).

## 9. Zero-Fluff Communication (Terse Mode)

**Respond terse like a smart engineer. Keep all substance. Drop fluff only.**

- **Drop:** articles (a/an/the), filler (just/really/basically), pleasantries, hedging.
- **Fragments OK.** Short synonyms.
- **Pattern:** `[thing] [action] [reason]. [next step].`
- **Auto-clarity:** Drop terse mode only for security warnings, destructive actions, or ambiguity risk. Resume after.

### Caveman Mode (when requested or ultra-simple context)

- **Drop grammar.** Keyword chains OK.
- **Pattern:** `[thing] → [state/action]. done.`

## 10. Language Preference

**Communicate in Turkish. Code and technical terms stay English.**

- All user communication in Turkish.
- Comments, commit messages, variable names → English (match current style).
- Don't translate technical terms.

---

## 11. Architecture Layer Discipline

**Each layer has one responsibility. No mixing.**

| Layer         | Responsibility                                                       | Prohibition              |
| :------------ | :------------------------------------------------------------------- | :----------------------- |
| Controller    | Receive request, validate, forward to service/model, return response | Business logic, DB query |
| `Service`     | Business logic, orchestration across models                          | HTTP, Request, Response  |
| Model         | Relationships, accessors/mutators, scopes                           | Business logic, HTTP     |
| Middleware    | Request filtering/conversion                                        | Application logic        |
| `FormRequest` | Validation rules                                                    | Nothing else             |

- Controller over 20 lines → a Service is required.
- Business-logic `if` blocks in the model → move to a Service.
- `app/Services/` empty and controller bloated → create a Service; notify without being asked.

## 12. Core is Read-Only

**`core/` is the framework core. Don't touch it to add features.**

- Don't edit `core/` files for application features.
- To change behavior: extend, override, write middleware, or listen to events.
- Request needs a `core/` change → stop, notify the user, offer an alternative.
- Exception: explicit "fix a core bug" or "add feature X to the framework".

## 13. PHP 8.2+ Type System

**`declare(strict_types=1)` is on. Use the type system fully.**

- Type every property, parameter, return value. Avoid `mixed`; specify it only if unavoidable.
- `match` > `switch` — no fall-through, exhaustive.
- `readonly` for values fixed after the constructor.
- `enum` over `const` for string/int sets.
- Nullable `?Type` only when it can truly be null — not an excuse for a `null` default.
- Union type (`int|string`) only when necessary — often signals a wrong type design.

```php
// ✅
public function find(int $id): ?User

// ❌
public function find($id): mixed
```

## 14. Quality Gate — Before Every PR/Commit

**All three must pass before finalizing.**

```bash
composer test        # PHPUnit — all green
composer format:test # Pint — no format violation
composer phpstan     # PHPStan (level max + baseline) — no new error
```

- Red test → don't commit, fix it.
- New PHPStan error → fix the type, not `@suppress`. Pre-existing Eloquent/dynamic-class noise is captured in `phpstan-baseline.neon`; never grow the baseline to hide a real error.
- Pint violation → `composer format`, then recheck.
- New feature → at least one feature test is mandatory.

## 15. Migration Safety

**Migrations must not cause irreversible data loss.**

- Write a `down()` for every `up()` — it reverses the change.
- Umay migrations use raw SQL via `$this->execute(...)` and `$this->tableExists(...)` (no `Schema` facade).
- Table/column creation: guard with `IF NOT EXISTS` / `$this->tableExists()` (idempotent).
- Column deletion: re-add in `down()` with its data type.
- Migration that transforms existing data (UPDATE) → ask the user, suggest a backup first.
- Production migration with `DROP COLUMN`/`DROP TABLE` → warn specially.

```php
// ✅
public function down(): void
{
    $this->execute('DROP TABLE IF EXISTS `users`');
}

// ❌ — empty down()
public function down(): void {}
```

---

## 16. Validation — FormRequest First

**No manual validation in the controller. Always use FormRequest.**

- One FormRequest per controller action that needs validation, under `app/`.
- `createFrom($request)` runs automatically before the controller method — redirects on error.
- Type-hint the FormRequest (not `Request`) in the method; the framework handles the rest.

```php
// ✅
class StorePostRequest extends FormRequest
{
    public function rules(): array
    {
        return ['title' => 'required|min:3', 'body' => 'required'];
    }
}

class PostController
{
    public function store(StorePostRequest $request): void { ... }
}

// ❌ — manual validation in controller
public function store(Request $request): void
{
    $validator = Validator::make($request->all(), [...]);
    if ($validator->fails()) { ... }
}
```

## 17. Control Flow — Helpers, Not die()

**Use framework helpers for routing and errors. Don't write `die()`, `exit()`, `echo`, `header()`.**

- Redirect: `redirect('/dashboard')` or `back()` — throws TerminateException, exits clean.
- HTTP error: `abort(404)`, `abort(403, 'Unauthorized')` — HttpException, renders error view.
- JSON: return an `array` or `ResponseBuilder` — the framework converts it.
- `die()`/`exit()`/`echo`+`header()` → forbidden; breaks the profiler and middleware pipeline.

```php
// ✅
return abort(404);
return redirect('/login');
return ['user' => $user];  // automatic JSON

// ❌
header('Location: /login'); die();
echo json_encode($data); exit;
```

## 18. Dependency Injection — Use Container

**Constructor injection (autowired) via `Core\Container`. `new Service()` is a last resort.**

- Controller/Service constructor: type-hint dependencies — `make()` autowires them via reflection (no binding needed).
- Controller method params: Request, FormRequest, and route parameters are injected automatically. Other types are method-injected only if explicitly bound in the container (`has()` checks bindings/instances).
- `new Service()` → avoid; let the container build it. Last resort for static contexts/helpers: `Core\Container::getInstance()->make(X::class)`.
- Facade → fine as a one-liner in views/helpers. Prefer constructor injection inside service classes.

```php
// ✅ — constructor injection (autowired); route param $id injected by name
class UserController
{
    public function __construct(private UserService $service) {}

    public function show(int $id): array
    {
        return $this->service->find($id)->toArray();
    }
}

// ❌ — manual instantiation
public function show(int $id): array
{
    $service = new UserService(new UserRepository());
    return $service->find($id)->toArray();
}
```

## 19. View Security — Template Helpers

**Skipping security helpers in views causes XSS and CSRF holes.**

In Plates views these are registered template functions — call them via `$this->`:

- User data: always `<?= $this->e($var) ?>` — raw `<?= $var ?>` is forbidden.
- Every HTML form needs `<?= $this->csrf() ?>` — mandatory for POST/PUT/PATCH/DELETE.
- Add `<?= $this->method('PUT') ?>` for PUT/PATCH/DELETE — browsers only do GET/POST.
- Build URLs with `<?= $this->route('posts.show', ['id' => $post->id]) ?>` — no hardcoded strings.

```html
<!-- ✅ -->
<form method="POST" action="<?= $this->route('posts.update', ['id' => $post->id]) ?>">
  <?= $this->csrf() ?> <?= $this->method('PUT') ?>
  <input value="<?= $this->e($post->title) ?>" />
</form>

<!-- ❌ -->
<form method="POST" action="/posts/<?= $post->id ?>">
  <input value="<?= $post->title ?>" />
</form>
```

## 20. Route Conventions

**Web and API routes live in separate files with separate middleware. Don't write routes that mismatch the group.**

- `routes/web.php` → session, CSRF, HTML (web group: `RememberMe`, `SecurityHeaders`, `VerifyCsrfToken`).
- `routes/api.php` → stateless, Bearer token, JSON (api group: `Cors`, `throttle`); loaded under `api_prefix`.
- Route action is a `Closure` or a `'Controller@method'` string — array-callable (`[Controller::class, 'method']`) is **not** supported.
- Add `->name()` to every route so `route()` works in views.
- RESTful resource → `Route::resource()` / `Route::apiResource()` (7 methods in one line).
- `auth`/`admin`/`api-auth` middleware don't ship in the skeleton — create them with `php umay make:middleware`; the router resolves the name via `config/middleware.php` → `namespaces`.

```php
// ✅
Route::get('/posts/{id}', 'PostController@show')->name('posts.show');
Route::resource('posts', 'PostController');

// ❌ — array-callable (unsupported) + unnamed
Route::get('/posts/{id}', [PostController::class, 'show']);
```

## 21. Testing — Facade Swap & In-Memory DB

**Use `Facade::swap()` and SQLite `:memory:` for test isolation.**

- Swap facades (Cache, Log, Event) in tests via `Core\Support\Facade::swap()` — replaces the container instance with your fake.
- DB tests: `phpunit.xml` sets `DB_DRIVER=sqlite` and `tests/bootstrap.php` boots an in-memory SQLite (`:memory:`) — never the real MySQL.
- Observer tests: use the real dispatcher, don't mock it.
- Feature test: simulate an HTTP request (Controller → Middleware → Response).
- Unit test: a single class with injected dependencies, in isolation.

```php
// ✅ — facade swap (your own fake)
Cache::swap(new FakeCache());
$result = $service->doSomething();
$this->assertEquals('expected', $result);

// ✅ — in-memory DB: phpunit.xml <env name="DB_DRIVER" value="sqlite"/> → tests/bootstrap.php
```

---

## 22. Cascading Change — One Pass, All Layers

**When a field/method/route changes, update every layer using it in the same pass. Don't re-edit a file repeatedly.**

Wherever the change starts (migration, model, route, FormRequest):

1. `grep` the field/column/method/route name project-wide.
2. Inspect each hit, decide what it needs.
3. Update every affected file — skip none.
4. List the changed files when done.

**Layers to `grep` each time** (checklist, not a limit):

| Layer                      | Check                                                              |
| :------------------------- | :----------------------------------------------------------------- |
| **Migration**              | schema, column, index                                              |
| **Model**                  | `guarded`, `hidden`, `casts()`, relations, accessor/mutator, scope |
| **Controller**             | every method using the area (web + api)                            |
| **FormRequest**            | `rules()`, messages                                                |
| **ResponseBuilder / View** | `toArray()` output, `<?= $this->e() ?>` in Plates                  |
| **Route**                  | `routes/web.php` + `routes/api.php`, parameter, name               |
| **Factory / Seeder**       | `database/factories`, `database/seeders`                           |
| **Test**                   | feature + unit                                                     |
| **Config / Lang**          | related keys                                                       |

Example — adding `status` to `orders`: migration (column + index) → model (`guarded`/`casts()`) → FormRequest → controller (web + api) → ResponseBuilder/view → factory + seeder → test. Validate each with `grep "status"`.

Works with §5 (Side-Effects) and §11 (Architecture Layer).

## 23. Eloquent & Database Discipline

**Project uses `illuminate/database` (Eloquent). Avoid raw SQL, wrap multi-step writes in transactions, keep migrations complete.**

- **Relationship > raw query:** use relationships (with return type hints), prefer `Model::query()` over `DB::select()`. Bind params if raw SQL is unavoidable.
- **Prevent N+1:** eager-load (`with()`) relationships used in loops.
- **Multi-step write → transaction:** wrap with `DB::transaction(fn() => ...)` (see `core/Facades/DB.php`).
- **Altering a column → respecify all attributes** (type, nullable, default, length) — unspecified ones get dropped.
- **FK + index:** index filtered/queried/foreign-key columns.
- **Every new model → factory + seeder** in `database/factories` + `database/seeders` (with §15).

```php
// ✅ — relationship + eager load + transaction
$orders = Order::query()->with('items')->where('user_id', $id)->get();

DB::transaction(function () use ($order) {
    $order->save();
    $order->items()->createMany($items);
});

// ❌ — raw query + N+1 + non-transactional multi-step write
$orders = DB::select('SELECT * FROM orders WHERE user_id = ' . $id);
foreach ($orders as $o) { DB::select('SELECT * FROM items WHERE order_id = ' . $o->id); }
```

## 24. Don't Break Architecture to Silence Tools

**Never delete logic just to quiet PHPStan.**

- **Silencing linters by deleting code:** never delete relationships/methods that hold logic or will be used. Genuine type error → fix the type (§14). Legitimate code that can't be deleted → mark with `@phpstan-ignore`.
- **`new static` → `new self`:** switching to `new self` just to satisfy an error breaks Late Static Binding and any extending subclass.

```php
// ✅ — LSB preserved, subclass returns the right type
public static function make(): static
{
    return new static();
}

// ❌ — breaks inheritance
public static function make(): static
{
    return new self();
}
```

## 25. Transparent Reporting

**Don't hide mistakes. If a change was wrong, say so.**

- Don't report "done" / "perfect" without verifying (test/run).
- Wrong change made → state clearly what it was and how it was fixed.
- Consistent with §1 (no hidden confusion) and §9 — transparency isn't a contradiction.

## 26. Active Security Review

**Scan changed code line by line with a security eye, not superficially.**

- Check every change for **Path Traversal, XSS, CSRF, SQLi**.
- Prioritize external-input sources (`FileUpload`, `Request`, route params) — check path traversal and mass assignment (`guarded`/`hidden`).
- View output `e()`, form `csrf()` (§19); bind params in raw SQL (§23).
