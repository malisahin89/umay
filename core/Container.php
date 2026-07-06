<?php

declare(strict_types=1);

namespace Core;

use Core\Exceptions\ContainerException;
use Core\Exceptions\EntryNotFoundException;
use Psr\Container\ContainerInterface;

/**
 * @phpstan-consistent-constructor
 */
class Container implements ContainerInterface
{
    protected static ?self $instance = null;

    private array $bindings = [];

    private array $instances = []; // singleton cache // singleton önbelleği

    private array $resolving = []; // circular dependency protection // circular dependency koruması

    /** Reflection cache — avoids re-creating ReflectionClass for the same concrete */
    private array $reflectionCache = [];

    public static function getInstance(): static
    {
        if (static::$instance === null) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    public function singleton(string $abstract, callable|string $concrete): void
    {
        $this->bindings[$abstract] = ['concrete' => $concrete, 'singleton' => true];
    }

    public function bind(string $abstract, callable|string $concrete): void
    {
        $this->bindings[$abstract] = ['concrete' => $concrete, 'singleton' => false];
    }

    public function instance(string $abstract, mixed $instance): void
    {
        $this->instances[$abstract] = $instance;
    }

    public function make(string $abstract): mixed
    {
        // Check singleton cache first
        // Önce singleton cache'e bak
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        // Circular dependency check
        // Circular dependency kontrolü
        if (isset($this->resolving[$abstract])) {
            $chain = implode(' → ', array_keys($this->resolving)).' → '.$abstract;
            throw new ContainerException("Container: Circular dependency detected // tespit edildi: {$chain}");
        }

        $this->resolving[$abstract] = true;

        try {
            // Is there a binding?
            // Binding var mı?
            if (isset($this->bindings[$abstract])) {
                $binding = $this->bindings[$abstract];
                $concrete = $binding['concrete'];

                $object = is_callable($concrete)
                    ? $concrete($this)
                    : $this->build($concrete);

                if ($binding['singleton']) {
                    $this->instances[$abstract] = $object;
                }

                return $object;
            }

            // Build directly
            // Doğrudan build et
            return $this->build($abstract);
        } finally {
            unset($this->resolving[$abstract]);
        }
    }

    /**
     * PSR-11 compliant — returns entry from container.
     * PSR-11 uyumlu — container'dan kayıt döndürür.
     *
     * Unlike make(), this method throws NotFoundExceptionInterface
     * when the entry is not registered (no auto-wire fallback).
     *
     * make()'ten farklı olarak, bu metot kayıtlı olmayan girişler için
     * NotFoundExceptionInterface fırlatır (auto-wire denemesi yapmaz).
     *
     * @throws EntryNotFoundException Entry not registered // Kayıt bulunamadı
     * @throws ContainerException Resolution error // Çözümleme hatası
     */
    public function get(string $id): mixed
    {
        if (! $this->has($id)) {
            throw new EntryNotFoundException(
                "Container: Entry [{$id}] is not registered. // Container: [{$id}] kaydı bulunamadı."
            );
        }

        try {
            return $this->make($id);
        } catch (EntryNotFoundException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new ContainerException(
                "Container: Error resolving [{$id}]. // Container: [{$id}] çözümlenirken hata.",
                0,
                $e
            );
        }
    }

    public function has(string $abstract): bool
    {
        return isset($this->instances[$abstract]) || isset($this->bindings[$abstract]);
    }

    private function build(string $concrete): mixed
    {
        if (! isset($this->reflectionCache[$concrete])) {
            $this->reflectionCache[$concrete] = new \ReflectionClass($concrete);
        }
        $reflector = $this->reflectionCache[$concrete];
        $constructor = $reflector->getConstructor();

        if (! $constructor) {
            return new $concrete;
        }

        $args = [];
        foreach ($constructor->getParameters() as $param) {
            $type = $param->getType();

            // Union type support (PHP 8.0+): If ReflectionUnionType, take the first resolvable
            // Union type desteği (PHP 8.0+): ReflectionUnionType ise ilk resolve edilebileni al
            if ($type instanceof \ReflectionUnionType) {
                $resolved = false;
                foreach ($type->getTypes() as $unionType) {
                    if ($unionType instanceof \ReflectionNamedType
                        && ! $unionType->isBuiltin()
                        && $this->has($unionType->getName())
                    ) {
                        $args[] = $this->make($unionType->getName());
                        $resolved = true;
                        break;
                    }
                }
                if (! $resolved) {
                    if ($param->isDefaultValueAvailable()) {
                        $args[] = $param->getDefaultValue();
                    } else {
                        throw new ContainerException("Container: Union type [{$type}] cannot be resolved // çözülemiyor.");
                    }
                }

                continue;
            }

            // Named type (single type)
            // Named type (tekil tip)
            if ($type instanceof \ReflectionNamedType && ! $type->isBuiltin()) {
                if ($this->has($type->getName())) {
                    $args[] = $this->make($type->getName());
                } elseif (class_exists($type->getName())) {
                    // Even if there is no binding, try auto-wire if class exists
                    // Binding olmasa bile class varsa auto-wire dene
                    $args[] = $this->make($type->getName());
                } elseif ($param->isDefaultValueAvailable()) {
                    $args[] = $param->getDefaultValue();
                } else {
                    throw new ContainerException("Container: [{$type->getName()}] cannot be resolved // çözülemiyor.");
                }
            } elseif ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
            } else {
                throw new ContainerException("Container: Parameter // parametresi [{$param->getName()}] cannot be resolved // çözülemiyor.");
            }
        }

        return $reflector->newInstanceArgs($args);
    }
}
