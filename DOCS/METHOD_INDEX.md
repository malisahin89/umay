# Metot Dizini (Method Index)

## Amaç
Çekirdek servis sınıflarının genel (public) metotlarını dizinler. Kapsam: konteynere kaydedilen servisler ve kaynak kodu analiz edilen diğer temel API'lerdir. Herhangi bir sınıfın tam metot listesi için, o sınıfa ait dosya raporuna bakın.

## Core\Application
| Metot | Görünürlük | Statik | İmza | Kaynak |
|--------|-----------|--------|-----------|--------|
| `getInstance` | public | static | `(): self` | `core/Application.php:52` |
| `container` | public | instance | `(): Container` | `:63` |
| `make` | public | instance | `(string $abstract): mixed` | `:72` |
| `instance` | public | instance | `(string, mixed): void` | `:77` |
| `singleton` | public | instance | `(string, callable\|string): void` | `:82` |
| `register` | public | instance | `(string $providerClass): static` | `:93` |
| `boot` | public | instance | `(): static` | `:116` |
| `handleException` | public | instance | `(\Throwable): void` | `:140` |
| `captureRequest` | public | instance | `(): static` | `:159` |

## Core\Database (tamamı statik)
`init(array): Capsule` `:34`; `getConnection(string='default'): Connection` `:103`; `statement(string, array=[]): bool` `:116`; `select(string, array=[]): array` `:125`; `selectOne(string, array=[]): ?object` `:134`; `insert(string, array=[]): bool` `:145`; `update(string, array=[]): int` `:154`; `delete(string, array=[]): int` `:163`; `transaction(callable): mixed` `:172`; `beginTransaction/commit/rollBack(): void` `:181-201`; `closeConnection/closeAllConnections(): void`, `getActiveConnectionCount(): int` `:207-224`.

## Core\Cache
`__construct()` `:36`; `get(string, mixed=null): mixed` `:118`; `set(string, mixed, ?int=null): void` `:159`; `atomic(string, callable, ?int=null): mixed` `:203`; `remember(string, int, callable): mixed` `:263`; `forget(string): void` `:282`; `flush(): void` `:291`; `pull(string, mixed=null): mixed` `:305`; `has(string): bool` `:313`.

## Core\Auth
`provider(): UserProvider` `:56`; `setProvider(UserProvider): void` `:83`; `user(): ?Authenticatable` `:98`; `id(): int\|string\|null` `:121`; `check(): bool` `:136`; `guest(): bool` `:145`; `setUser(Authenticatable): void` `:157`; `login(Authenticatable, bool=false): void` `:171`; `logout(): void` `:209`; `attempt(array): bool` `:254`; `clearCache(): void` `:273`.

## Core\RateLimiter
`__construct()` `:30`; `for(string, int, int=60): void` `:42`; `limiter(string): ?array` `:50`; `tooManyAttempts(string, int, int=60): bool` `:62`; `hit(string, int=60): int` `:83`; `clear(string): void` `:109`; `attempts(string): int` `:117`; `remaining(string, int): int` `:127`; `availableIn(string, int): int` `:135`; `key(string, ?string=null): string` `:149`.

## Core\Validator
`make(array, array, array=[]): static` (static) `:31`; `errors(): array` `:41`; `fails(): bool` `:46`; `passes(): bool` `:51`. (Kural metotları özeldir — bkz. `DOCS/VALIDATION.md`.)

## Core\View
`render(string $template, array $data=[]): void` `:241`. (Şablon yardımcı fonksiyonları dahili olarak kaydedilir — bkz. `DOCS/VIEW_ENGINE.md`.)

## Core\Logger
`error(string, array=[]): void` `:31`; `warning(string, array=[]): void` `:36`; `info(string, array=[]): void` `:41`.

## Core\Csrf (tamamı statik)
`generate(): string` `:9`; `check(mixed): bool` `:25`.

## Core\Csp (tamamı statik)
`nonce(): string` `:32`; `reset(): void` `:41`.

## Core\ExceptionHandler
`handle(\Throwable): void` `:22`. (Diğer işleyiciler özeldir.)

## Çapraz Referanslar
- `DOCS/CLASS_INDEX.md` ve her sınıfın dosya bazlı raporu (özel/korumalı üyeler için).

## Kaynak Referansları
- `core/Application.php`, `core/Database.php`, `core/Cache.php`, `core/Auth.php`, `core/RateLimiter.php`, `core/Validator.php`, `core/View.php`, `core/Logger.php`, `core/Csrf.php`, `core/Csp.php`, `core/ExceptionHandler.php`
