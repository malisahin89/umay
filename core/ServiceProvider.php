<?php

declare(strict_types=1);

namespace Core;

/**
 * Servis Sağlayıcı — framework bootstrap lifecycle'ının temel taşı.
 *
 * Her servis iki aşamada çalışır:
 *   register() — Container'a binding tanımla (diğer provider'lara bağımlı olma)
 *   boot()     — register() sonrası çalışır; diğer binding'lere güvenle erişilebilir
 *
 * Kullanım:
 *   class CacheServiceProvider extends ServiceProvider {
 *       public function register(): void {
 *           $this->container->singleton(Cache::class, fn() => new Cache());
 *       }
 *       public function boot(): void {
 *           // Cache başlatma işlemleri
 *       }
 *   }
 *
 *   // Application'a kaydet:
 *   Application::getInstance()->register(CacheServiceProvider::class);
 */
abstract class ServiceProvider
{
    public function __construct(protected readonly Container $container) {}

    /**
     * Container'a binding'ler ekle.
     * Bu aşamada diğer provider'ların binding'lerine güvenilmez.
     */
    abstract public function register(): void;

    /**
     * Tüm register() çağrılarından sonra çalışır.
     * Route tanımları, event listener'lar vb. buraya gider.
     */
    public function boot(): void {}
}
