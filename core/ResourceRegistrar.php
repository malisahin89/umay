<?php

declare(strict_types=1);

namespace Core;

/**
 * RESTful resource route kayıt yardımcısı.
 *
 * Route::resource('users', 'UserController');
 * Route::resource('users', 'UserController')->only(['index', 'store']);
 * Route::resource('users', 'UserController')->except(['destroy'])->middleware('auth');
 *
 * Route::apiResource('posts', 'Api\\PostController'); // create/edit view route'ları yok
 */
class ResourceRegistrar
{
    /** Tüm resource verb → method eşleşmesi */
    private static array $verbs = [
        'index' => [['GET'], '', 'index'],
        'create' => [['GET'], '/create', 'create'],
        'store' => [['POST'], '', 'store'],
        'show' => [['GET'], '/{id}', 'show'],
        'edit' => [['GET'], '/{id}/edit', 'edit'],
        // update hem PUT hem PATCH kabul eder — yalnızca PUT kayıtlıyken gerçek bir
        // PATCH isteği (veya method_field('PATCH') kullanan form) 404 dönerdi.
        // update accepts both PUT and PATCH — with only PUT registered, a real PATCH
        // request (or a form using method_field('PATCH')) would 404.
        'update' => [['PUT', 'PATCH'], '/{id}', 'update'],
        'destroy' => [['DELETE'], '/{id}', 'destroy'],
    ];

    /** API resource'da create + edit yoktur */
    private static array $apiExclude = ['create', 'edit'];

    private string $name;

    private string $controller;

    private bool $api;

    private array $only = [];

    private array $except = [];

    private array $middlewares = [];

    public function __construct(string $name, string $controller, array $options = [])
    {
        $this->name = trim($name, '/');
        $this->controller = $controller;
        $this->api = $options['api'] ?? false;
    }

    /**
     * Deferred registration (PendingResourceRegistration pattern):
     * routes are registered once, after all fluent only()/except()/middleware()
     * calls complete and the object is destroyed at end of statement. This keeps
     * the active prefix stack intact and avoids fragile remove-then-readd logic
     * that silently leaked routes inside Route::prefix()->group(...).
     *
     * Ertelenmiş kayıt (PendingResourceRegistration deseni):
     * route'lar, tüm fluent only()/except()/middleware() çağrıları bitip nesne
     * statement sonunda yok edildiğinde TEK kez kaydedilir. Böylece aktif prefix
     * stack korunur ve Route::prefix()->group(...) içinde route sızdıran kırılgan
     * remove-then-readd mantığına gerek kalmaz.
     */
    public function __destruct()
    {
        $this->register();
    }

    /** only(['index', 'store']) — sadece bu action'ları kaydet */
    public function only(array $actions): static
    {
        $this->only = $actions;

        return $this;
    }

    /** except(['destroy']) — bu action'ları kaydetme */
    public function except(array $actions): static
    {
        $this->except = $actions;

        return $this;
    }

    /** Tüm resource route'larına middleware ekle */
    public function middleware(string|array $middleware): static
    {
        $this->middlewares = array_merge($this->middlewares, (array) $middleware);

        return $this;
    }

    /** Route'ları kaydet (yalnızca __destruct'tan, tek sefer) */
    private function register(): void
    {
        $exclude = $this->api ? self::$apiExclude : [];
        $exclude = array_merge($exclude, $this->except);

        foreach (self::$verbs as $action => [$httpMethods, $suffix, $method]) {
            // only filtresi
            if (! empty($this->only) && ! in_array($action, $this->only, true)) {
                continue;
            }
            // except filtresi
            if (in_array($action, $exclude, true)) {
                continue;
            }

            $uri = '/'.$this->name.$suffix;
            $routeName = $this->name.'.'.$action;
            $routeAction = $this->controller.'@'.$method;

            // Route::match() çoklu verb'i (update: PUT+PATCH) tek handle ile kaydeder;
            // zincirlenen name()/middleware() tüm verb'lere uygulanır.
            // Route::match() registers multi-verb actions (update: PUT+PATCH) under one
            // handle; chained name()/middleware() apply to every verb.
            $instance = Route::match((array) $httpMethods, $uri, $routeAction);

            $instance->name($routeName);

            foreach ($this->middlewares as $mw) {
                $instance->middleware($mw);
            }
        }
    }
}
