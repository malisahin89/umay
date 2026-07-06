<?php

declare(strict_types=1);

namespace Core;

/**
 * Application — framework bootstrap orchestrator.
 *
 * Sits on top of the Container; manages ServiceProviders,
 * initializes the exception handler and base services.
 *
 * Usage (public/index.php):
 *   $app = Application::getInstance();
 *   $app->register(AppServiceProvider::class);
 *   $app->boot();
 *
 * Direct Container access:
 *   Application::getInstance()->container()->make(SomeClass::class);
 *   // or shorter:
 *   Container::getInstance()->make(SomeClass::class);
 *
 *
 * Application — framework bootstrap orkestratörü.
 *
 * Container'ın üzerinde oturur; ServiceProvider'ları yönetir,
 * exception handler'ı ve temel servisleri başlatır.
 *
 * Kullanım (public/index.php):
 *   $app = Application::getInstance();
 *   $app->register(AppServiceProvider::class);
 *   $app->boot();
 *
 * Container'a direkt erişim:
 *   Application::getInstance()->container()->make(SomeClass::class);
 *   // veya kısaca:
 *   Container::getInstance()->make(SomeClass::class);
 */
class Application
{
    protected static ?self $instance = null;

    /** @var ServiceProvider[] */
    private array $providers = [];

    private bool $booted = false;

    private function __construct(private readonly Container $container) {}

    // ── Singleton ─────────────────────────────────────────────────────────────

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self(Container::getInstance());
        }

        return self::$instance;
    }

    // ── Container erişimi ────────────────────────────────────────────────────

    public function container(): Container
    {
        return $this->container;
    }

    /**
     * Shortcut for direct Container access
     * Container'a doğrudan erişim için kısayol
     */
    public function make(string $abstract): mixed
    {
        return $this->container->make($abstract);
    }

    public function instance(string $abstract, mixed $concrete): void
    {
        $this->container->instance($abstract, $concrete);
    }

    public function singleton(string $abstract, callable|string $concrete): void
    {
        $this->container->singleton($abstract, $concrete);
    }

    // ── Provider yönetimi ────────────────────────────────────────────────────

    /**
     * Register a ServiceProvider and run its register() method.
     * ServiceProvider'ı kaydet ve register() metodunu çalıştır.
     */
    public function register(string $providerClass): static
    {
        $provider = new $providerClass($this->container);

        if (! $provider instanceof ServiceProvider) {
            throw new \InvalidArgumentException(
                "Class [{$providerClass}] is not a ServiceProvider instance."
            );
        }

        $provider->register();
        $this->providers[] = $provider;

        return $this;
    }

    /**
     * Run boot() methods of all registered providers.
     * Should be called when the application is fully ready.
     *
     * Tüm kayıtlı provider'ların boot() metodlarını çalıştır.
     * Uygulama tamamen hazır olduğunda çağrılmalı.
     */
    public function boot(): static
    {
        if ($this->booted) {
            return $this;
        }

        foreach ($this->providers as $provider) {
            $provider->boot();
        }

        $this->booted = true;

        return $this;
    }

    // ── Exception Handling ───────────────────────────────────────────────────

    /**
     * Run the application-wide exception handler.
     * ExceptionHandler is resolved from container — can be overridden.
     *
     * Uygulama genelinde exception handler'ı çalıştır.
     * ExceptionHandler container'dan resolve edilir — override edilebilir.
     */
    public function handleException(\Throwable $e): void
    {
        if ($this->container->has(ExceptionHandler::class)) {
            $handler = $this->container->make(ExceptionHandler::class);
        } else {
            $handler = new ExceptionHandler;
        }
        $handler->handle($e);
    }

    // ── Fluent bootstrap helper ──────────────────────────────────────────────

    /**
     * Capture the Request and bind to container.
     * Should be called before dispatch().
     *
     * Request'i container'a kaydet.
     * dispatch() çağrısından önce çalıştırılmalı.
     */
    public function captureRequest(): static
    {
        $request = Request::capture();
        $this->container->instance(Request::class, $request);

        return $this;
    }
}
