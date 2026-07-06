<?php

declare(strict_types=1);

namespace Core;

use Core\Profiler\ProfilerController;

/**
 * @phpstan-consistent-constructor
 */
class Route
{
    protected static array $routes = [];

    protected static array $prefixStack = [];

    protected static array $middlewareStack = [];

    /** @var array<string, string> name → URI */
    protected static array $namedRoutes = [];

    protected static string $currentGroup = 'web';

    /** Reflection cache — controller@method → parameter metadata */
    private static array $reflectionCache = [];

    /** Middleware class resolution cache — name → FQCN */
    private static array $middlewareClassMap = [];

    // ── Route definition methods ──────────────────────────────────────────────
    // ── Route tanım metodları ─────────────────────────────────────────────────

    public static function get(string $uri, \Closure|string $action): static
    {
        return self::addRoute('GET', $uri, $action);
    }

    public static function post(string $uri, \Closure|string $action): static
    {
        return self::addRoute('POST', $uri, $action);
    }

    public static function put(string $uri, \Closure|string $action): static
    {
        return self::addRoute('PUT', $uri, $action);
    }

    public static function patch(string $uri, \Closure|string $action): static
    {
        return self::addRoute('PATCH', $uri, $action);
    }

    public static function delete(string $uri, \Closure|string $action): static
    {
        return self::addRoute('DELETE', $uri, $action);
    }

    /**
     * Same route for multiple HTTP methods.
     * Birden fazla HTTP metodu için aynı route.
     *
     * Route::match(['GET', 'POST'], '/search', 'SearchController@index');
     */
    public static function match(array $methods, string $uri, \Closure|string $action): static
    {
        $registered = [];
        $fullUri = null;
        foreach ($methods as $method) {
            $verb = strtoupper($method);
            $instance = self::addRoute($verb, $uri, $action);
            $registered[] = $verb;
            $fullUri = $instance->uri; // prefix-applied URI (same for every method) // prefix uygulanmış URI (her metot için aynı)
        }

        // Return ONE fluent handle owning ALL registered methods, so a chained
        // ->middleware()/->name() applies to every verb — not just the last one.
        // Tüm kayıtlı metotlara sahip TEK fluent handle döndür; böylece zincirlenen
        // ->middleware()/->name() son metoda değil her metoda uygulanır.
        $handle = new static($fullUri, $registered[0] ?? null);
        $handle->methods = $registered;

        return $handle;
    }

    /**
     * Same route for all HTTP methods.
     * Tüm HTTP metodları için aynı route.
     *
     * Route::any('/webhook', 'WebhookController@handle');
     */
    public static function any(string $uri, \Closure|string $action): static
    {
        return self::match(['GET', 'POST', 'PUT', 'PATCH', 'DELETE'], $uri, $action);
    }

    /**
     * Route that renders a view directly without a controller.
     * Controller olmadan doğrudan view render eden route.
     *
     * Route::view('/about', 'pages/about', ['title' => 'Hakkımızda']);
     */
    public static function view(string $uri, string $view, array $data = []): static
    {
        return self::get($uri, '_view@render')->tap(function () use ($view, $data, $uri) {
            $fullUri = self::buildFullUri($uri);
            self::$routes['GET'][$fullUri]['_view'] = $view;
            self::$routes['GET'][$fullUri]['_view_data'] = $data;
        });
    }

    /**
     * Direct redirect route.
     * Doğrudan redirect route.
     *
     * Route::redirect('/old-path', '/new-path', 301);
     */
    public static function redirect(string $from, string $to, int $status = 302): static
    {
        return self::get($from, '_redirect@handle')->tap(function () use ($from, $to, $status) {
            $fullUri = self::buildFullUri($from);
            self::$routes['GET'][$fullUri]['_redirect'] = $to;
            self::$routes['GET'][$fullUri]['_redirect_status'] = $status;
        });
    }

    /**
     * RESTful resource route set.
     * RESTful resource route seti.
     *
     * Route::resource('users', 'UserController');
     * // GET    /users           → index    (users.index)
     * // GET    /users/create    → create   (users.create)
     * // POST   /users           → store    (users.store)
     * // GET    /users/{id}      → show     (users.show)
     * // GET    /users/{id}/edit → edit     (users.edit)
     * // PUT    /users/{id}      → update   (users.update)
     * // DELETE /users/{id}      → destroy  (users.destroy)
     *
     * Options:
     * Opsiyonlar:
     *   ->only(['index', 'store'])
     *   ->except(['destroy'])
     *   ->middleware('auth')
     */
    public static function resource(string $name, string $controller, array $options = []): ResourceRegistrar
    {
        return new ResourceRegistrar($name, $controller, $options);
    }

    /**
     * API resource — without create/edit view routes.
     * API resource — create/edit view route'ları olmadan.
     *
     * Route::apiResource('posts', 'Api\\PostController');
     * // GET    /posts       → index
     * // POST   /posts       → store
     * // GET    /posts/{id}  → show
     * // PUT    /posts/{id}  → update
     * // DELETE /posts/{id}  → destroy
     */
    public static function apiResource(string $name, string $controller, array $options = []): ResourceRegistrar
    {
        return new ResourceRegistrar($name, $controller, array_merge($options, ['api' => true]));
    }

    // ── Prefix / Group / Middleware ───────────────────────────────────────────

    public static function prefix(string $prefix): static
    {
        self::$prefixStack[] = rtrim($prefix, '/');

        $handle = new static(null, null);
        // Mark that THIS handle pushed a prefix — group() only pops what its own
        // handle pushed. Without this, a stray ->group() on a route handle (which
        // never pushed) would pop an OUTER group's prefix and silently corrupt
        // every route registered after it.
        // Bu handle'ın prefix push'ladığını işaretle — group() yalnızca kendi
        // handle'ının push'ladığını pop'lar. Bu olmadan, bir route handle'ında
        // yanlışlıkla çağrılan ->group() (hiç push'lamamışken) DIŞ grubun prefix'ini
        // pop'lar ve sonrasında kaydedilen her route'u sessizce bozardı.
        $handle->ownsPrefix = true;

        return $handle;
    }

    public function group(\Closure $callback): static
    {
        $callback();

        if ($this->ownsPrefix) {
            array_pop(self::$prefixStack);
        }

        if ($this->hasGroupMiddleware) {
            array_pop(self::$middlewareStack);
        }

        // group() kapandı — bu handle üzerinden artık grup middleware'i eklenemez
        // (bkz. middleware() içindeki koruma).
        // group() is closed — no more group middleware may be added through this
        // handle (see the guard in middleware()).
        $this->groupEnded = true;

        return $this;
    }

    public function name(string $routeName): static
    {
        if (! $this->uri) {
            return $this;
        }

        // Apply the name to every method this handle owns (match()/any() own several).
        // İsmi bu handle'ın sahip olduğu her metoda uygula (match()/any() birden çok).
        foreach ($this->methods as $method) {
            self::$routes[$method][$this->uri]['name'] = $routeName;
        }
        self::$namedRoutes[$routeName] = $this->uri;

        return $this;
    }

    public function middleware(string|array $middlewareName): static
    {
        $names = (array) $middlewareName;

        if ($this->methods !== [] && $this->uri !== null) {
            // Attach to every method this handle owns — match()/any() must protect ALL verbs.
            // Bu handle'ın sahip olduğu her metoda ekle — match()/any() TÜM verb'leri korumalı.
            foreach ($this->methods as $method) {
                foreach ($names as $name) {
                    self::$routes[$method][$this->uri]['middleware'][] = $name;
                }
            }
        } else {
            // group() kapandıktan SONRA middleware() çağrısı, hiç pop'lanmayacak bir
            // stack katmanı push'lar ve sonradan kaydedilen HER route'a sessizce sızardı
            // (yanlış sıra: prefix()->group(...)->middleware()). Sessiz bozulma yerine
            // net hata ver — doğru sıra: prefix()->middleware()->group(...).
            // Calling middleware() AFTER group() closed would push a stack layer nobody
            // ever pops, silently leaking onto EVERY route registered later (wrong
            // order: prefix()->group(...)->middleware()). Fail loudly instead — the
            // correct order is prefix()->middleware()->group(...).
            if ($this->groupEnded) {
                throw new \LogicException(
                    'middleware() must be called BEFORE group(). // middleware(), group()\'tan ÖNCE çağrılmalı.'
                );
            }

            if (! $this->hasGroupMiddleware) {
                self::$middlewareStack[] = [];
                $this->hasGroupMiddleware = true;
            }
            foreach ($names as $name) {
                self::$middlewareStack[count(self::$middlewareStack) - 1][] = $name;
            }
        }

        return $this;
    }

    /**
     * Fluent helper — internal use for Route::view/redirect
     * Fluent helper — Route::view/redirect iç kullanımı için
     */
    protected function tap(\Closure $callback): static
    {
        $callback();

        return $this;
    }

    // ── URL generation ────────────────────────────────────────────────────────
    // ── URL üretme ───────────────────────────────────────────────────────────

    /**
     * Is there a registered route with this name?
     * Bu isimde kayıtlı bir route var mı?
     */
    public static function has(string $name): bool
    {
        return isset(self::$namedRoutes[$name]);
    }

    public static function url(string $name, array $params = []): string
    {
        // Fail loudly on an unknown name instead of silently emitting href="#" —
        // a typo in a template stays invisible for months otherwise. Callers that
        // accept "name OR raw URL" (like the redirect() helper) check has() first.
        // Bilinmeyen isimde sessizce href="#" basmak yerine net hata ver — aksi halde
        // template'teki bir yazım hatası aylarca görünmez kalır. "isim VEYA ham URL"
        // kabul eden çağıranlar (redirect() helper'ı gibi) önce has() kontrol eder.
        if (! isset(self::$namedRoutes[$name])) {
            throw new \InvalidArgumentException("Route [{$name}] is not defined. // Route [{$name}] tanımlı değil.");
        }

        $uri = self::$namedRoutes[$name];

        $usedKeys = [];
        foreach ($params as $key => $value) {
            // Support both required {key} and optional {key?} placeholders.
            // Hem zorunlu {key} hem opsiyonel {key?} placeholder'larını destekle.
            $encoded = rawurlencode((string) $value);
            $required = '{'.$key.'}';
            $optional = '{'.$key.'?}';

            if (str_contains($uri, $required)) {
                $uri = str_replace($required, $encoded, $uri);
                $usedKeys[] = $key;
            } elseif (str_contains($uri, $optional)) {
                $uri = str_replace($optional, $encoded, $uri);
                $usedKeys[] = $key;
            }
        }

        // Drop optional placeholders that were not supplied, together with their
        // leading slash (mirrors the optional route regex: /{name?} → optional).
        // Verilmemiş opsiyonel placeholder'ları baştaki slash'leriyle birlikte kaldır
        // (opsiyonel route regex'iyle uyumlu: /{name?} → opsiyonel).
        $uri = preg_replace('#/\{[a-zA-Z_][a-zA-Z0-9_]*\?\}#', '', $uri) ?? $uri;
        // Edge case: an optional placeholder at the very start (no leading slash).
        // Uç durum: en başta, slash'siz opsiyonel placeholder.
        $uri = preg_replace('#\{[a-zA-Z_][a-zA-Z0-9_]*\?\}#', '', $uri) ?? $uri;
        if ($uri === '') {
            $uri = '/';
        }

        // Any placeholder still present here is a REQUIRED parameter that was not
        // supplied (optional {x?} ones were stripped above). Fail loudly instead of
        // emitting a broken link with a literal "{id}" in the href.
        // Burada hâlâ duran her placeholder, sağlanmamış bir ZORUNLU parametredir
        // (opsiyonel {x?} olanlar yukarıda silindi). Href'te literal "{id}" içeren
        // bozuk bir link üretmek yerine net biçimde hata ver.
        if (preg_match_all('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', $uri, $missing) && $missing[1] !== []) {
            throw new \InvalidArgumentException(sprintf(
                'Route [%s] is missing required parameter(s) // zorunlu parametre(ler) eksik: %s',
                $name,
                implode(', ', $missing[1])
            ));
        }

        $remaining = array_diff_key($params, array_flip($usedKeys));
        if (! empty($remaining)) {
            $uri .= '?'.http_build_query($remaining);
        }

        return $uri;
    }

    // ── Dispatch ─────────────────────────────────────────────────────────────

    public static function dispatch(): void
    {
        $rawPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $uri = rtrim($rawPath, '/');
        if ($uri === '') {
            $uri = '/';
        }

        // ── Canonical trailing-slash redirect ─────────────────────────────────
        // Registered route keys are always slashless (see buildFullUri), so /users/
        // and /users resolve to the same handler. Issue a 301 to the slashless
        // canonical for GET/HEAD so search engines don't index duplicate URLs.
        // Skip "/" and non-idempotent methods (a 301 on POST would drop the body).
        // Kayıtlı route anahtarları daima slash'sızdır (bkz. buildFullUri); /users/
        // ile /users aynı handler'a gider. GET/HEAD için slash'sız kanonik adrese 301
        // ver ki arama motorları yinelenen URL indekslemesin. "/" ve idempotent olmayan
        // metotları atla (POST'ta 301 gövdeyi düşürür).
        // Redirect ONLY when the slashless target actually resolves to a route.
        // Otherwise an unknown "/foo/" would 301 to "/foo" and then 404 — turning a
        // plain 404 into a pointless redirect hop. GET routes are the canonical set
        // (HEAD is served by them), so the existence check runs against GET.
        // YALNIZCA slash'sız hedef gerçekten bir route'a çözülüyorsa yönlendir. Aksi
        // halde bilinmeyen bir "/foo/", "/foo"'ya 301 verip sonra 404'lardı — düz bir
        // 404'ü gereksiz bir redirect adımına çevirir. Kanonik küme GET route'larıdır
        // (HEAD onlarla karşılanır), bu yüzden varlık kontrolü GET'e karşı çalışır.
        $requestMethod = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        if ($uri !== '/' && $rawPath !== $uri
            && in_array($requestMethod, ['GET', 'HEAD'], true)
            && self::hasMatchingRoute('GET', $uri)) {
            $query = $_SERVER['QUERY_STRING'] ?? '';
            http_response_code(301);
            header('Location: '.$uri.(is_string($query) && $query !== '' ? '?'.$query : ''));

            return;
        }

        // ── Profiler route intercept ──────────────────────────────────────────
        // ── Profiler route intercept ─────────────────────────────────────────
        if (str_starts_with($uri, '/_profiler')) {
            self::handleProfilerRoute($uri);

            return;
        }

        // Resolve the active Request once (registered via Application::captureRequest()).
        // Reused below for method spoofing AND route-param binding, so php://input is
        // not read a second time for JSON bodies (Request::__construct already parsed it).
        // Aktif Request'i bir kez çöz (Application::captureRequest() ile kaydedilir). Hem
        // method spoofing hem route-param bağlama için yeniden kullanılır; böylece JSON
        // gövdesi için php://input ikinci kez okunmaz (Request::__construct ayrıştırdı).
        $container = Container::getInstance();
        $request = $container->has(Request::class)
            ? $container->make(Request::class)
            : Request::capture();
        if (! $request instanceof Request) {
            $request = Request::capture();
        }

        // HTTP method — method spoofing support
        // HTML forms can only send GET/POST.
        // Can be overridden with <input type="hidden" name="_method" value="PUT">.
        //
        // HTTP method — method spoofing desteği
        // HTML formlar sadece GET/POST gönderebilir.
        // <input type="hidden" name="_method" value="PUT"> ile override edilebilir.
        $method = $requestMethod;
        if ($method === 'POST') {
            $override = $request->post('_method') ?? $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] ?? '';
            $spoofed = is_string($override) ? strtoupper($override) : '';
            if (in_array($spoofed, ['PUT', 'PATCH', 'DELETE'], true)) {
                $method = $spoofed;
            }
        } elseif ($method === 'HEAD') {
            // HEAD is served by GET handlers (RFC 9110 §9.3.2) — routes are registered
            // per verb, so without this fallback every HEAD request would 404
            // (curl -I, load-balancer health checks, search-engine probes). The SAPI
            // discards the body for HEAD responses.
            // HEAD, GET handler'larıyla karşılanır (RFC 9110 §9.3.2) — route'lar verb
            // bazında kayıtlıdır; bu fallback olmadan her HEAD isteği 404 dönerdi
            // (curl -I, load-balancer health check'leri, arama motoru probe'ları).
            // SAPI, HEAD yanıtlarında gövdeyi zaten atar.
            $method = 'GET';
        }

        $route = null;
        $routeParams = [];
        // The registered route key (pattern), NOT the live request URI. Used as the
        // closure reflection-cache key so a parameterized closure route
        // (e.g. /users/{id}) caches once instead of per distinct URL.
        // Kayıtlı route anahtarı (pattern), canlı istek URI'si DEĞİL. Closure
        // reflection cache anahtarı olarak kullanılır; böylece parametreli bir closure
        // route'u (örn. /users/{id}) her farklı URL için değil, tek kez önbelleklenir.
        $matchedPattern = $uri;

        // 1. Exact match
        if (isset(self::$routes[$method][$uri])) {
            $route = self::$routes[$method][$uri];
        } else {
            // 2. Pattern match — use pre-compiled regex from route registration
            // 2. Pattern match — route kayıt sırasında derlenen regex'i kullan
            foreach (self::$routes[$method] ?? [] as $pattern => $routeData) {
                if (! isset($routeData['_compiled'])) {
                    continue;
                }

                $compiled = $routeData['_compiled'];

                if (preg_match($compiled['regex'], $uri, $matches)) {
                    array_shift($matches);
                    // Optional groups that don't participate are omitted by PCRE; pad so counts align.
                    $matches = array_pad($matches, count($compiled['params']), '');
                    $route = $routeData;
                    // Route keys are URI strings; the is_string guard keeps $matchedPattern
                    // a clean string for the cache key (and satisfies static analysis).
                    $matchedPattern = is_string($pattern) ? $pattern : $uri;
                    $routeParams = array_combine($compiled['params'], array_map(fn ($m) => $m !== '' ? urldecode($m) : null, $matches));
                    break;
                }
            }
        }

        if (! $route) {
            // OPTIONS fallback — no explicit OPTIONS route registered, but the path may
            // exist under another verb. Route it through the GROUP pipeline so the Cors
            // middleware actually runs and can answer cross-origin preflight (it
            // terminates OPTIONS with 204 itself). Without this, every preflight 404'd
            // before the pipeline was built and Cors' OPTIONS branch was unreachable.
            // OPTIONS fallback — açıkça kayıtlı OPTIONS route'u yok ama path başka bir
            // verb altında mevcut olabilir. GRUP pipeline'ından geçir ki Cors middleware
            // gerçekten çalışsın ve cross-origin preflight'ı yanıtlayabilsin (OPTIONS'ı
            // 204 ile kendisi sonlandırır). Bu olmadan her preflight, pipeline daha
            // kurulmadan 404 alıyordu ve Cors'un OPTIONS dalı erişilmezdi.
            if ($method === 'OPTIONS') {
                self::handleOptionsFallback($uri, $request);

                return;
            }

            abort(404);
        }

        // Bind the matched route params onto the Request resolved above, then publish it.
        // Yukarıda çözülen Request'e eşleşen route param'larını bağla, sonra yayınla.
        $request->setRouteParams($routeParams);
        $container->instance(Request::class, $request);

        $isViewRoute = isset($route['_view']);
        $isRedirectRoute = isset($route['_redirect']);
        $isClosure = ! $isViewRoute && ! $isRedirectRoute && $route['action'] instanceof \Closure;

        if ($isViewRoute) {
            // Pseudo-action labels — no controller class to resolve.
            // Sahte action etiketleri — çözülecek controller sınıfı yok.
            $controllerClass = '_view';
            $methodName = 'render';
        } elseif ($isRedirectRoute) {
            $controllerClass = '_redirect';
            $methodName = 'handle';
        } elseif (! $isClosure) {
            // Controller and method validation
            // Controller ve method doğrulaması
            // Guard the 'Controller@method' shape: without '@', the list destructure
            // below would emit a PHP warning and leave $methodName null.
            // 'Controller@method' biçimini güvenceye al: '@' yoksa aşağıdaki list ataması
            // PHP uyarısı verir ve $methodName null kalırdı.
            if (! is_string($route['action']) || ! str_contains($route['action'], '@')) {
                abort(500, 'Invalid route action — expected "Controller@method". // Geçersiz route action — "Controller@method" bekleniyor.');
            }

            [$controllerName, $methodName] = explode('@', $route['action'], 2);

            foreach (explode('\\', $controllerName) as $segment) {
                if (! preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $segment)) {
                    abort(500, 'Invalid controller name! // Geçersiz controller adı!');
                }
            }

            if (! preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $methodName)) {
                abort(500, 'Invalid method name! // Geçersiz metot adı!');
            }

            // Controller namespace is config-driven — the core router no longer
            // hard-codes 'App\Controllers\'. // Controller namespace config'den gelir —
            // çekirdek router artık 'App\Controllers\'ı sabit kodlamaz.
            $namespace = (string) config('app.controller_namespace', 'App\\Controllers\\');
            $controllerClass = $namespace.$controllerName;

            if (! class_exists($controllerClass) || ! method_exists($controllerClass, $methodName)) {
                abort(404);
            }
        } else {
            $controllerClass = 'Closure';
            $methodName = 'handle';
        }

        // DebugBar: save route information
        // DebugBar: route bilgisini kaydet
        if (defined('UMAY_PROFILING') && UMAY_PROFILING) {
            // Look up by the matched route PATTERN, not the live URI — named routes are
            // stored as name → pattern, so a parameterized route (/users/{id}) would
            // otherwise never resolve its name in the profiler.
            // Canlı URI ile değil, eşleşen route PATTERN'iyle ara — named route'lar
            // name → pattern olarak saklanır; aksi halde parametreli bir route
            // (/users/{id}) profiler'da adını asla çözemezdi.
            $routeName = array_search($matchedPattern, self::$namedRoutes, true) ?: '-';
            DebugBar::setRoute([
                'method' => $method,
                'uri' => $uri,
                'name' => $routeName,
                'controller' => $controllerClass,
                'action' => $methodName,
                'middleware' => $route['middleware'] ?? [],
            ]);
            DebugBar::startMeasure('controller');
        }

        // Middleware pipeline — group-based loading from config/middleware.php
        // Middleware pipeline — config/middleware.php'den grup bazlı yükleme
        $group = $route['group'] ?? 'web';
        $mwConfig = config('middleware');
        $groupMiddlewares = array_merge(
            $mwConfig['global'] ?? [],
            $mwConfig[$group] ?? []
        );
        $allMiddlewares = array_merge($groupMiddlewares, $route['middleware'] ?? []);

        // Handler: resolve controller with Container, method injection via cached Reflection
        // Handler: Container ile controller resolve, önbelleğe alınmış Reflection ile method injection
        $handler = function (Request $request) use ($route, $isClosure, $controllerClass, $methodName, $routeParams, $container, $method, $matchedPattern) {
            // Build and cache parameter metadata for this controller@method or closure
            // Bu controller@method veya closure için parametre meta verisini derle ve önbelleğe al
            $cacheKey = $isClosure ? 'closure@'.$method.':'.$matchedPattern : $controllerClass.'@'.$methodName;

            if (! isset(self::$reflectionCache[$cacheKey])) {
                $reflMethod = $isClosure
                    ? new \ReflectionFunction($route['action'])
                    : new \ReflectionMethod($controllerClass, $methodName);

                $paramsMeta = [];
                foreach ($reflMethod->getParameters() as $param) {
                    $type = $param->getType();
                    $typeName = $type instanceof \ReflectionNamedType ? $type->getName() : null;
                    $paramsMeta[] = [
                        'name' => $param->getName(),
                        'type' => $typeName,
                        'hasDefault' => $param->isDefaultValueAvailable(),
                        'default' => $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null,
                        'isRequest' => $typeName === Request::class,
                        'isFormRequest' => $typeName && class_exists($typeName) && is_subclass_of($typeName, Request::class),
                        'allowsNull' => $type === null || $type->allowsNull(),
                    ];
                }
                self::$reflectionCache[$cacheKey] = $paramsMeta;
            }

            $args = [];
            foreach (self::$reflectionCache[$cacheKey] as $meta) {
                if ($meta['isRequest']) {
                    $args[] = $request;
                } elseif ($meta['isFormRequest']) {
                    // FormRequest subclass — createFrom() + auto-validate
                    $args[] = $meta['type']::createFrom($request);
                } elseif (array_key_exists($meta['name'], $routeParams) && $routeParams[$meta['name']] !== null) {
                    // Provided route param (an unmatched optional {x?} is null → fall through).
                    // Coerce the string segment to the declared scalar type — under strict_types
                    // "5" would otherwise TypeError on a typed `int $id` argument.
                    // Verilen route param (eşleşmeyen opsiyonel {x?} null → aşağı düşer).
                    // String parçayı bildirilen scalar tipe çevir — strict_types altında "5"
                    // aksi halde tipli `int $id` argümanında TypeError verirdi.
                    $args[] = self::castRouteParam($routeParams[$meta['name']], $meta['type']);
                } elseif (is_string($meta['type']) && ($container->has($meta['type']) || class_exists($meta['type']))) {
                    // Bind edilmemiş somut sınıflar da auto-wire edilir — constructor
                    // injection (Container::build) ile aynı davranış; aksi halde metotta
                    // hint'lenen bağımsız bir servis 500 verirdi.
                    // Unbound concrete classes auto-wire too — same behaviour as
                    // constructor injection (Container::build); otherwise a plain service
                    // type-hinted on the method would 500.
                    $args[] = $container->make($meta['type']);
                } elseif ($meta['hasDefault']) {
                    $args[] = $meta['default'];
                } elseif ($meta['allowsNull']) {
                    $args[] = null;
                } else {
                    // Required, non-nullable parameter that couldn't be resolved — e.g. an
                    // optional {id?} route bound to `int $id` with no default. Fail with a
                    // clear 500 instead of an opaque TypeError from the controller call.
                    // Çözülemeyen zorunlu, null kabul etmeyen parametre — örn. opsiyonel {id?}
                    // route'unun default'suz `int $id`'ye bağlanması. Controller çağrısından
                    // gelen anlaşılmaz TypeError yerine net bir 500 ver.
                    abort(500, "Route parameter // parametresi [{$meta['name']}] could not be resolved // çözülemedi.");
                }
            }

            if ($isClosure) {
                $result = $route['action'](...$args);
            } else {
                $controller = $container->make($controllerClass);
                $result = $controller->$methodName(...$args);
            }

            if ($result instanceof ResponseBuilder) {
                $result->send();
            } elseif (is_string($result) || is_numeric($result)) {
                echo $result;
            } elseif (is_array($result) || (is_object($result) && ! ($result instanceof \Closure))) {
                header('Content-Type: application/json; charset=utf-8');
                // Match ResponseBuilder::json(): keep UTF-8 (Turkish) chars unescaped and
                // surface an encode failure instead of silently echoing an empty body.
                // ResponseBuilder::json() ile aynı: UTF-8 (Türkçe) karakterleri escape'leme
                // ve sessizce boş body yazmak yerine encode hatasını yüzeye çıkar.
                echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
            }
        };

        // View/redirect routes: override the handler so they execute INSIDE the pipeline.
        // Previously these returned before the pipeline was built, so global/group/route
        // middleware (SecurityHeaders, VerifyCsrfToken, RememberMe, auth…) silently never ran.
        // View/redirect route'ları: handler'ı override et ki pipeline İÇİNDE çalışsınlar.
        // Önceden pipeline kurulmadan return ediyorlardı; global/grup/route middleware
        // (SecurityHeaders, VerifyCsrfToken, RememberMe, auth…) sessizce hiç çalışmıyordu.
        if ($isViewRoute) {
            $handler = function (Request $request) use ($route) {
                Container::getInstance()->make(View::class)
                    ->render($route['_view'], $route['_view_data'] ?? []);
            };
        } elseif ($isRedirectRoute) {
            $handler = function (Request $request) use ($route) {
                http_response_code($route['_redirect_status'] ?? 302);
                header('Location: '.$route['_redirect']);
                throw new TerminateException;
            };
        }

        // Middleware pipeline ($next chain via array_reduce)
        // Middleware pipeline (array_reduce ile $next zinciri)
        $pipeline = array_reduce(
            array_reverse($allMiddlewares),
            function ($next, $middlewareName) {
                return function ($request) use ($next, $middlewareName) {
                    return self::runMiddleware($middlewareName, $request, $next);
                };
            },
            $handler
        );

        $pipeline($request);

        if (defined('UMAY_PROFILING') && UMAY_PROFILING) {
            DebugBar::stopMeasure('controller');
        }
    }

    // ── Internal helpers ──────────────────────────────────────────────────────
    // ── Dahili yardımcılar ────────────────────────────────────────────────────

    /**
     * Apply the active group-prefix stack to a URI and normalise it to a slashless
     * route key ("/" stays "/"). Single source of truth for route keys — addRoute(),
     * view() and redirect() all go through here so their keys can never drift apart.
     *
     * Aktif grup-prefix yığınını bir URI'ye uygular ve slash'sız route anahtarına
     * normalize eder ("/" aynı kalır). Route anahtarları için tek doğruluk kaynağı —
     * addRoute(), view() ve redirect() buradan geçer; anahtarlar asla ayrışamaz.
     */
    private static function buildFullUri(string $uri): string
    {
        $prefix = implode('', self::$prefixStack);
        $fullUri = rtrim($prefix.'/'.ltrim($uri, '/'), '/');

        return $fullUri === '' ? '/' : $fullUri;
    }

    /**
     * Does a registered route (exact key or compiled pattern) match $uri under $method?
     * Gates the canonical trailing-slash redirect so unknown paths keep 404-ing instead
     * of bouncing through a 301.
     *
     * $uri, $method altında kayıtlı bir route ile (birebir anahtar ya da derlenmiş
     * pattern) eşleşiyor mu? Kanonik trailing-slash yönlendirmesini gate'ler; böylece
     * bilinmeyen yollar 301'e sekmek yerine 404 dönmeye devam eder.
     */
    private static function hasMatchingRoute(string $method, string $uri): bool
    {
        $routes = self::$routes[$method] ?? [];
        if (! is_array($routes)) {
            return false;
        }

        if (isset($routes[$uri])) {
            return true;
        }

        foreach ($routes as $routeData) {
            if (! is_array($routeData)) {
                continue;
            }

            $compiled = $routeData['_compiled'] ?? null;
            if (is_array($compiled)
                && isset($compiled['regex'])
                && is_string($compiled['regex'])
                && preg_match($compiled['regex'], $uri) === 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * Answer an OPTIONS request for a path that has no explicit OPTIONS route but
     * matches other verbs. Only global + group middleware run (Cors emits CORS
     * headers and terminates preflight with 204); route-level middleware such as
     * auth is skipped — preflight requests carry no credentials and must not 401.
     * Falls back to 204 + Allow when no middleware terminates earlier; 404 when the
     * path matches no verb at all.
     *
     * Açık OPTIONS route'u olmayan ama başka verb'lerle eşleşen bir path için OPTIONS
     * isteğini yanıtla. Yalnızca global + grup middleware çalışır (Cors, CORS
     * header'larını basar ve preflight'ı 204 ile sonlandırır); auth gibi route-seviye
     * middleware atlanır — preflight kimlik bilgisi taşımaz, 401 almamalıdır.
     * Hiçbir middleware daha önce sonlandırmazsa 204 + Allow döner; path hiçbir
     * verb'le eşleşmiyorsa 404.
     */
    private static function handleOptionsFallback(string $uri, Request $request): void
    {
        $allowed = [];
        $group = null;

        foreach (self::$routes as $verb => $routes) {
            if ($verb === 'OPTIONS' || ! is_array($routes)) {
                continue;
            }

            foreach ($routes as $pattern => $routeData) {
                if (! is_array($routeData)) {
                    continue;
                }

                $compiled = $routeData['_compiled'] ?? null;
                $matches = $pattern === $uri
                    || (is_array($compiled)
                        && isset($compiled['regex'])
                        && is_string($compiled['regex'])
                        && preg_match($compiled['regex'], $uri) === 1);

                if ($matches) {
                    $allowed[] = (string) $verb;
                    $group ??= is_string($routeData['group'] ?? null) ? $routeData['group'] : 'web';
                    break;
                }
            }
        }

        if ($allowed === []) {
            abort(404);
        }

        if (in_array('GET', $allowed, true)) {
            // GET routes also serve HEAD (see dispatch()) — advertise it.
            // GET route'ları HEAD'i de karşılar (bkz. dispatch()) — bunu da bildir.
            $allowed[] = 'HEAD';
        }
        $allowed[] = 'OPTIONS';

        $mwConfig = config('middleware');
        $mwConfig = is_array($mwConfig) ? $mwConfig : [];
        $middlewares = array_values(array_filter(
            array_merge(
                is_array($mwConfig['global'] ?? null) ? $mwConfig['global'] : [],
                is_array($mwConfig[$group] ?? null) ? $mwConfig[$group] : []
            ),
            'is_string'
        ));

        $handler = function (Request $request) use ($allowed) {
            http_response_code(204);
            header('Allow: '.implode(', ', $allowed));
        };

        $pipeline = array_reduce(
            array_reverse($middlewares),
            function ($next, $middlewareName) {
                return function ($request) use ($next, $middlewareName) {
                    return self::runMiddleware($middlewareName, $request, $next);
                };
            },
            $handler
        );

        $pipeline($request);
    }

    private static function addRoute(string $method, string $uri, \Closure|string $action): static
    {
        $fullUri = self::buildFullUri($uri);

        $inheritedMiddleware = ! empty(self::$middlewareStack)
            ? array_merge(...self::$middlewareStack)
            : [];

        // Pre-compile regex for parameterized routes (avoids re-computation during dispatch)
        // Parametreli route'lar için regex'i önceden derle (dispatch sırasında tekrar hesaplanmaz)
        $compiled = null;
        if (str_contains($fullUri, '{')) {
            $compiled = [
                'regex' => self::buildRegex($fullUri),
                'params' => self::extractParamNames($fullUri),
            ];
        }

        self::$routes[$method][$fullUri] = [
            'action' => $action,
            'middleware' => $inheritedMiddleware,
            'name' => null,
            'group' => self::$currentGroup,
            '_compiled' => $compiled,
        ];

        return new static($fullUri, $method);
    }

    private static function buildRegex(string $pattern): string
    {
        // Split on placeholders, keeping them (DELIM_CAPTURE). Static segments are
        // preg_quote'd so regex-special chars (e.g. '.') match literally; only the
        // placeholders become capture groups.
        //
        // Placeholder'lara göre böl ve onları da koru (DELIM_CAPTURE). Statik
        // parçalar preg_quote'lanır (örn. '.' gibi regex özel karakterleri literal
        // eşleşir); yalnızca placeholder'lar capture grubuna dönüşür.
        $parts = preg_split(
            '/(\{[a-zA-Z_][a-zA-Z0-9_]*\??\})/',
            $pattern,
            -1,
            PREG_SPLIT_DELIM_CAPTURE
        );

        if ($parts === false) {
            return '#^'.preg_quote($pattern, '#').'$#';
        }

        $regex = '';
        foreach ($parts as $part) {
            if ($part === '') {
                continue;
            }

            if (preg_match('/^\{[a-zA-Z_][a-zA-Z0-9_]*(\?)?\}$/', $part, $m)) {
                if (isset($m[1])) {
                    // Optional {name?} — absorb the preceding slash so it can be
                    // omitted entirely (/{name?} → optional).
                    // Opsiyonel {name?} — baştaki slash'i de içine al ki tamamen
                    // atlanabilsin (/{name?} → opsiyonel).
                    if (str_ends_with($regex, '/')) {
                        $regex = substr($regex, 0, -1).'(?:/([^/]+))?';
                    } else {
                        $regex .= '(?:([^/]+))?';
                    }
                } else {
                    // Required {name} → ([^/]+)
                    $regex .= '([^/]+)';
                }
            } else {
                $regex .= preg_quote($part, '#');
            }
        }

        return '#^'.$regex.'$#';
    }

    private static function extractParamNames(string $pattern): array
    {
        preg_match_all('/\{([a-zA-Z_][a-zA-Z0-9_]*)\??\}/', $pattern, $matches);

        return $matches[1];
    }

    /**
     * Coerce a string route parameter to the controller's declared scalar type.
     * Route.php runs under strict_types, so a URL segment like "5" would NOT
     * auto-coerce to `int $id` and would TypeError — this restores ergonomic
     * typed route params. A value that cannot represent the declared type
     * (e.g. /users/abc for `int $id`) yields a 404.
     *
     * Bir string route parametresini controller'ın bildirdiği scalar tipe çevirir.
     * Route.php strict_types altında çalıştığından "5" gibi bir URL parçası
     * `int $id`'ye otomatik coerce OLMAZ ve TypeError verirdi — bu, ergonomik
     * tipli route parametrelerini geri kazandırır. Bildirilen tipi temsil
     * edemeyen değer (örn. `int $id` için /users/abc) 404 verir.
     */
    private static function castRouteParam(mixed $value, mixed $type): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        switch ($type) {
            case 'int':
                $int = filter_var($value, FILTER_VALIDATE_INT);
                if ($int === false) {
                    abort(404);
                }

                return $int;
            case 'float':
                $float = filter_var($value, FILTER_VALIDATE_FLOAT);
                if ($float === false) {
                    abort(404);
                }

                return $float;
            case 'bool':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            default:
                // string, untyped, mixed or a class type → pass the raw segment
                // string, tipsiz, mixed veya class tip → ham parçayı geç
                return $value;
        }
    }

    protected static function runMiddleware(string $middlewareName, $request = null, $next = null): mixed
    {
        $originalName = $middlewareName;
        $middlewareParam = null;
        if (str_contains($middlewareName, ':')) {
            [$middlewareName, $middlewareParam] = explode(':', $middlewareName, 2);
        }

        // Resolve middleware class from cache (avoids repeated class_exists lookups)
        // Middleware sınıfını önbellekten çöz (tekrarlı class_exists aramalarını önler)
        if (! isset(self::$middlewareClassMap[$middlewareName])) {
            // kebab-case → StudlyCase: "api-auth" → "ApiAuth"
            $studly = implode('', array_map('ucfirst', explode('-', $middlewareName)));

            // Namespace templates are config-driven (config/middleware.php → 'namespaces').
            // First existing class wins. Defaults keep App→Core resolution order.
            // Namespace şablonları config'den gelir (config/middleware.php → 'namespaces').
            // İlk var olan sınıf kazanır. Varsayılanlar App→Core sırasını korur.
            $templates = config('middleware.namespaces', [
                'App\\Middleware\\{name}Middleware',
                'Core\\Middleware\\{name}',
            ]);

            $class = null;
            foreach ((array) $templates as $template) {
                $candidate = '\\'.ltrim(str_replace('{name}', $studly, (string) $template), '\\');
                if (class_exists($candidate)) {
                    $class = $candidate;
                    break;
                }
            }

            if ($class === null) {
                throw new \Exception("Middleware not found // bulunamadı: $middlewareName");
            }

            self::$middlewareClassMap[$middlewareName] = $class;
        }

        $middlewareClass = self::$middlewareClassMap[$middlewareName];

        $middleware = ($middlewareParam !== null)
            ? new $middlewareClass($middlewareParam)
            : new $middlewareClass;

        // Middleware timing measurement
        // Middleware timing ölçümü
        $profiling = defined('UMAY_PROFILING') && UMAY_PROFILING;
        if ($profiling) {
            $mwStart = microtime(true);
        }

        $result = $middleware->handle($request, $next ?? fn ($req) => $req);

        if ($profiling) {
            $ms = (microtime(true) - $mwStart) * 1000;
            DebugBar::addMiddlewareTiming($originalName, $ms);
            DebugBar::startMeasure('mw:'.$originalName);
            DebugBar::stopMeasure('mw:'.$originalName);
        }

        return $result;
    }

    // ── Route group management ────────────────────────────────────────────────
    // ── Route grup yönetimi ──────────────────────────────────────────────────

    /**
     * Set active route group ('web' or 'api').
     * Used by RouteServiceProvider while loading route files.
     *
     * Aktif route grubunu belirle ('web' veya 'api').
     * RouteServiceProvider tarafından route dosyaları yüklenirken kullanılır.
     */
    public static function setGroup(string $group): void
    {
        self::$currentGroup = $group;
    }

    /**
     * Returns the active route group
     * Aktif route grubunu döndürür
     */
    public static function getGroup(): string
    {
        return self::$currentGroup;
    }

    // ── Internal state ────────────────────────────────────────────────────────
    // ── İç durum ─────────────────────────────────────────────────────────────

    protected ?string $uri;

    protected ?string $method;

    /**
     * HTTP methods this fluent handle owns — match()/any() may own several.
     * Bu fluent handle'ın sahip olduğu HTTP metotları — match()/any() birden çok olabilir.
     *
     * @var list<string>
     */
    protected array $methods = [];

    protected bool $hasGroupMiddleware = false;

    /** Did this fluent handle push onto the prefix stack (created by prefix())?
     *  Bu fluent handle prefix yığınına push'ladı mı (prefix() ile mi oluştu)? */
    protected bool $ownsPrefix = false;

    /** Has group() already run on this handle? Blocks late middleware() calls.
     *  Bu handle'da group() çalıştı mı? Geç middleware() çağrılarını engeller. */
    protected bool $groupEnded = false;

    public function __construct(?string $uri, ?string $method)
    {
        $this->uri = $uri;
        $this->method = $method;
        $this->methods = $method !== null ? [$method] : [];
    }

    /**
     * Returns all registered routes (for debug/test)
     * Tüm kayıtlı route'ları döndürür (debug/test için)
     */
    public static function getRoutes(): array
    {
        return self::$routes;
    }

    /**
     * Remove a specific route from registry.
     * Used by components like ResourceRegistrar that re-register routes.
     *
     * Belirli bir route'u kayıttan sil.
     * ResourceRegistrar gibi yeniden-kayıt yapan bileşenler tarafından kullanılır.
     */
    public static function removeRoute(string $method, string $uri): void
    {
        unset(self::$routes[$method][$uri]);

        // Delete from named routes too
        // Named routes'tan da sil
        foreach (self::$namedRoutes as $name => $namedUri) {
            if ($namedUri === $uri) {
                unset(self::$namedRoutes[$name]);
            }
        }
    }

    /**
     * Handles profiler routes (/_profiler/...).
     * Runs outside normal route pipeline — does not use App\Controllers namespace.
     *
     * Profiler route'larını handle eder (/_profiler/...).
     * Normal route pipeline dışında çalışır — App\Controllers namespace'i kullanmaz.
     */
    private static function handleProfilerRoute(string $uri): void
    {
        // Only GET requests
        // Sadece GET istekleri
        if (strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
            abort(405);
        }

        $request = Request::capture();
        $controller = new ProfilerController;

        // /_profiler veya /_profiler/
        if ($uri === '/_profiler' || $uri === '/_profiler/') {
            $controller->index($request);

            return;
        }

        // /_profiler/{token}
        $token = substr($uri, strlen('/_profiler/'));
        if ($token) {
            $controller->show($request, $token);

            return;
        }

        abort(404);
    }
}
