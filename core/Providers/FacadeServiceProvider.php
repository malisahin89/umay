<?php

declare(strict_types=1);

namespace Core\Providers;

use Core\Auth;
use Core\Cache;
use Core\Database;
use Core\Events\Dispatcher;
use Core\Logger;
use Core\RateLimiter;
use Core\Route;
use Core\ServiceProvider;
use Core\Support\Facade;
use Core\Validator;
use Core\View;

/**
 * FacadeServiceProvider — registers core services as singletons in the Container
 * and boots Facade aliases from config/app.php.
 *
 * FacadeServiceProvider — core servisleri Container'a singleton olarak kaydeder
 * ve config/app.php'den Facade alias'larını boot eder.
 *
 * This provider is registered BEFORE other providers in public/index.php.
 * Bu provider public/index.php'de diğer provider'lardan ÖNCE kaydedilir.
 *
 * Lifecycle / Yaşam döngüsü:
 *   register() → Bind core classes to Container as singletons
 *                Core sınıfları Container'a singleton olarak bağla
 *   boot()     → Register class aliases from config('app.aliases')
 *                config('app.aliases') üzerinden class alias'ları kaydet
 */
class FacadeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register core services as singletons in the Container.
        // This allows Facades to resolve them via Container::make().
        //
        // Core servisleri Container'a singleton olarak kaydet.
        // Bu, Facade'lerin Container::make() ile çözmesini sağlar.
        //
        // All services below are instance-based and can be fully mocked via swap().
        // Aşağıdaki tüm servisler instance-based olup swap() ile tamamen mock'lanabilir.

        $this->container->singleton(Cache::class, function () {
            return new Cache;
        });

        $this->container->singleton(Auth::class, function () {
            return new Auth;
        });

        $this->container->singleton(Logger::class, function () {
            return new Logger;
        });

        // Route uses static architecture internally (dispatch, prefix stack, middleware stack).
        // Constructor requires two nullable params for fluent chaining.
        //
        // Route dahili olarak statik mimari kullanır (dispatch, prefix stack, middleware stack).
        // Constructor, fluent zincirleme için iki nullable parametre gerektirir.
        $this->container->singleton(Route::class, function () {
            return new Route(null, null);
        });

        // Database is fully static (Eloquent Capsule integration).
        // Register a plain instance as Facade proxy target.
        //
        // Database tamamen statik (Eloquent Capsule entegrasyonu).
        // Facade proxy hedefi olarak basit bir instance kaydet.
        $this->container->singleton(Database::class, function () {
            return new Database;
        });

        $this->container->singleton(Dispatcher::class, function () {
            return Dispatcher::getInstance();
        });

        // Validator has a private constructor and uses Validator::make() factory.
        // The Facade proxy target is a plain instance — __callStatic forwards
        // to $instance->make() which calls `new static(...)` internally.
        //
        // Validator private constructor'a sahip ve Validator::make() factory kullanır.
        // Facade proxy hedefi basit bir instance — __callStatic
        // $instance->make() çağırır, bu da dahili olarak `new static(...)` çağırır.
        $this->container->singleton(Validator::class, function () {
            return new class
            {
                public function make(array $data, array $rules, array $messages = []): Validator
                {
                    return Validator::make($data, $rules, $messages);
                }
            };
        });

        $this->container->singleton(View::class, function () {
            return new View;
        });

        $this->container->singleton(RateLimiter::class, function () {
            return new RateLimiter;
        });
    }

    public function boot(): void
    {
        // Register class aliases from config/app.php → 'aliases'
        // config/app.php → 'aliases' üzerinden class alias'ları kaydet
        //
        // This enables using short names like `Cache` instead of `\Core\Facades\Cache`
        // Bu, `\Core\Facades\Cache` yerine kısa `Cache` ismi kullanmayı sağlar
        $aliases = config('app.aliases', []);

        foreach ($aliases as $alias => $facadeClass) {
            // Only register if the alias is not already defined as a class
            // Alias zaten bir sınıf olarak tanımlıysa kaydetme
            if (! class_exists($alias, false)) {
                class_alias($facadeClass, $alias);
            }
        }
    }
}
