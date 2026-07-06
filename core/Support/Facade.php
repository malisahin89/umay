<?php

declare(strict_types=1);

namespace Core\Support;

use Core\Container;

/**
 * Abstract Facade — static proxy for services resolved from the Container.
 * Abstract Facade — Container'dan çözümlenen servislere statik proxy.
 *
 * How it works / Nasıl çalışır:
 *   1. Developer calls Cache::get('key')
 *      Geliştirici Cache::get('key') çağırır
 *
 *   2. PHP triggers __callStatic() because no real static method exists
 *      PHP __callStatic() tetikler çünkü gerçek statik metot yoktur
 *
 *   3. Facade resolves the real service class from Container
 *      Facade, Container'dan gerçek servis sınıfını çözer
 *
 *   4. The method call is forwarded to the resolved instance
 *      Metot çağrısı çözümlenen instance'a yönlendirilir
 *
 * Creating a Facade / Facade oluşturma:
 *   class Cache extends Facade {
 *       protected static function getFacadeAccessor(): string {
 *           return \Core\Cache::class;
 *       }
 *   }
 *
 * Testing (swap with mock) / Test (mock ile değiştir):
 *   Cache::swap(new FakeCacheForTest());
 *   Cache::clearResolvedInstances();
 */
abstract class Facade
{
    /**
     * Resolved instance cache — avoids repeated Container lookups.
     * Çözümlenmiş instance önbelleği — tekrarlı Container aramalarını önler.
     *
     * @var array<string, object>
     */
    protected static array $resolvedInstances = [];

    /**
     * Returns the Container key or class name for this Facade.
     * Bu Facade için Container anahtarını veya sınıf adını döndürür.
     *
     * Each Facade subclass MUST implement this method.
     * Her Facade alt sınıfı bu metodu ZORUNLU olarak implement etmelidir.
     */
    abstract protected static function getFacadeAccessor(): string;

    /**
     * Resolve the underlying service instance from Container.
     * Arka plandaki servis instance'ını Container'dan çöz.
     */
    public static function getFacadeRoot(): object
    {
        $accessor = static::getFacadeAccessor();

        // Return from cache if already resolved
        // Daha önce çözümlendiyse cache'den döndür
        if (isset(static::$resolvedInstances[$accessor])) {
            return static::$resolvedInstances[$accessor];
        }

        // Always resolve through Container — leverages auto-wiring for unbound classes.
        // Her zaman Container üzerinden çöz — bind edilmemiş sınıflar için auto-wiring kullanır.
        $container = Container::getInstance();

        try {
            $instance = $container->make($accessor);
        } catch (\Throwable $e) {
            throw new \RuntimeException(
                "Facade: [{$accessor}] could not be resolved from Container. "
                ."// Facade: [{$accessor}] Container'dan çözümlenemedi.",
                0,
                $e
            );
        }

        static::$resolvedInstances[$accessor] = $instance;

        return $instance;
    }

    /**
     * Swap the resolved instance (useful for testing / mocking).
     * Çözümlenmiş instance'ı değiştir (test / mock için kullanışlı).
     *
     * Cache::swap(new FakeCacheForTest());
     */
    public static function swap(object $instance): void
    {
        $accessor = static::getFacadeAccessor();
        static::$resolvedInstances[$accessor] = $instance;

        // Also update the Container binding so other resolvers see the swap
        // Container binding'ini de güncelle, diğer resolver'lar da görsün
        Container::getInstance()->instance($accessor, $instance);
    }

    /**
     * Clear all resolved Facade instances (call between tests).
     * Tüm çözümlenmiş Facade instance'larını temizle (testler arasında çağır).
     */
    public static function clearResolvedInstances(): void
    {
        static::$resolvedInstances = [];
    }

    /**
     * Clear a single resolved instance.
     * Tek bir çözümlenmiş instance'ı temizle.
     */
    public static function clearResolvedInstance(string $accessor): void
    {
        unset(static::$resolvedInstances[$accessor]);
    }

    /**
     * Magic method — forwards static calls to the resolved instance.
     * Sihirli metot — statik çağrıları çözümlenmiş instance'a yönlendirir.
     *
     * This is the HEART of the Facade system.
     * Bu, Facade sisteminin KALBİDİR.
     *
     * Note: No method_exists() check — allows the resolved instance to use
     * PHP's __call() magic method for dynamic method delegation.
     * Not: method_exists() kontrolü yok — resolved instance'ın PHP'nin
     * __call() sihirli metodu ile dinamik metot delege etmesine izin verir.
     */
    public static function __callStatic(string $method, array $args): mixed
    {
        $instance = static::getFacadeRoot();

        return $instance->$method(...$args);
    }
}
