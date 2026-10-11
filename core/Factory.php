<?php

declare(strict_types=1);

namespace Core;

/**
 * Model Factory base sınıfı.
 *
 * // database/factories/UserFactory.php:
 * class UserFactory extends Factory
 * {
 *     protected string $model = User::class;
 *
 *     public function definition(): array
 *     {
 *         return [
 *             'name'     => $this->faker()->name(),
 *             'email'    => $this->faker()->uniqueEmail(),
 *             'password' => 'password',  // Model mutator hash'ler
 *             'status'   => 'active',
 *             'role'     => 'member',
 *         ];
 *     }
 *
 *     // State: admin kullanıcı
 *     public function admin(): static
 *     {
 *         return $this->state(['role' => 'admin']);
 *     }
 * }
 *
 * // Kullanım:
 * factory(User::class)->make();             // DB'ye yazmaz
 * factory(User::class)->create();           // DB'ye kaydeder
 * factory(User::class, 5)->create();        // 5 kayıt
 * factory(User::class)->admin()->create();  // State uygulanmış
 * factory(User::class)->state(['name' => 'Ali'])->make(); // Inline override
 *
 * @phpstan-consistent-constructor
 */
abstract class Factory
{
    protected string $model;

    private int $count = 1;

    private array $states = [];

    /** Tanımlanmış varsayılan attribute'lar */
    abstract public function definition(): array;

    // ── Fluent API ────────────────────────────────────────────────────────────

    /** Kaç adet oluşturulacağını belirle */
    public function count(int $count): static
    {
        $clone = clone $this;
        $clone->count = $count;

        return $clone;
    }

    /**
     * Attribute override — definition() çıktısına merge edilir.
     *
     * factory(User::class)->state(['role' => 'admin'])->create();
     */
    public function state(array $attributes): static
    {
        $clone = clone $this;
        $clone->states[] = $attributes;

        return $clone;
    }

    // ── Oluşturma metodları ───────────────────────────────────────────────────

    /**
     * Model örneği oluştur — DB'ye yazmaz.
     *
     * @return object|array<object>
     */
    public function make(array $override = []): mixed
    {
        $items = [];
        for ($i = 0; $i < $this->count; $i++) {
            $attrs = $this->resolveAttributes($override);
            $items[] = $this->newModel($attrs);
        }

        return $this->count === 1 ? $items[0] : $items;
    }

    /**
     * Model oluştur ve DB'ye kaydet.
     *
     * @return object|array<object>
     */
    public function create(array $override = []): mixed
    {
        $items = [];
        for ($i = 0; $i < $this->count; $i++) {
            $attrs = $this->resolveAttributes($override);
            $model = $this->newModel($attrs);
            $model->save();
            $items[] = $model;
        }

        return $this->count === 1 ? $items[0] : $items;
    }

    /**
     * Attribute dizisi döndür — model oluşturmaz.
     */
    public function raw(array $override = []): array
    {
        return $this->resolveAttributes($override);
    }

    // ── Factory registry ──────────────────────────────────────────────────────

    /** model FQCN → factory FQCN eşleşmesi */
    protected static array $registry = [];

    /**
     * Factory sınıfını model ile ilişkilendir.
     * Genellikle DatabaseSeeder veya TestCase::setUp() içinde çağrılır.
     */
    public static function register(string $modelClass, string $factoryClass): void
    {
        static::$registry[$modelClass] = $factoryClass;
    }

    /**
     * Model sınıfından factory örneği döndür.
     * Önce registry'ye bakar, sonra conventions (UserFactory → User) dener.
     */
    public static function forModel(string $modelClass, int $count = 1): static
    {
        // 1. Explicit registry
        if (isset(static::$registry[$modelClass])) {
            $factory = new static::$registry[$modelClass];

            return $count > 1 ? $factory->count($count) : $factory;
        }

        // 2. Convention: App\Models\User → Database\Factories\UserFactory
        $shortName = class_basename($modelClass);
        $conventional = "Database\\Factories\\{$shortName}Factory";

        if (! class_exists($conventional)) {
            throw new \RuntimeException("Factory bulunamadı: {$conventional}. factory() helper veya Factory::register() ile kayıt edin.");
        }

        $factory = new $conventional;

        return $count > 1 ? $factory->count($count) : $factory;
    }

    // ── Faker ─────────────────────────────────────────────────────────────────

    /**
     * Basit dahili faker — gerçek veri üretmek için kullanılır.
     * Production'da faker/faker paketi varsa onu kullanır.
     */
    protected function faker(): FakerProxy
    {
        return new FakerProxy;
    }

    // ── Dahili ───────────────────────────────────────────────────────────────

    private function resolveAttributes(array $override): array
    {
        $attrs = $this->definition();
        foreach ($this->states as $state) {
            $attrs = array_merge($attrs, $state);
        }

        return array_merge($attrs, $override);
    }

    private function newModel(array $attributes): object
    {
        $modelClass = $this->model;
        $model = new $modelClass;

        // Factory verisi güvenilir (definition + state) — guarded alanlar (role/status vb.)
        // da uygulanmalı; mutator'lar yine çalışır. Bu yüzden forceFill kullanılır.
        // Factory data is trusted (definition + state) — guarded fields (role/status etc.)
        // must apply too; mutators still run. Hence forceFill.
        if (method_exists($model, 'forceFill')) {
            $model->forceFill($attributes);
        } else {
            foreach ($attributes as $key => $value) {
                $model->$key = $value;
            }
        }

        return $model;
    }
}

/**
 * Basit faker yardımcısı — faker/faker paketi yoksa fallback.
 */
class FakerProxy
{
    private static int $counter = 0;

    public function name(): string
    {
        $names = ['Ali', 'Ayşe', 'Mehmet', 'Fatma', 'Ahmet', 'Zeynep', 'Mustafa', 'Hatice'];

        return $names[array_rand($names)].' Test'.(++self::$counter);
    }

    public function email(): string
    {
        return 'user'.(++self::$counter).'@example.com';
    }

    public function uniqueEmail(): string
    {
        return 'user_'.uniqid().'@example.com';
    }

    public function word(): string
    {
        $words = ['lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur', 'adipiscing'];

        return $words[array_rand($words)];
    }

    public function sentence(int $words = 6): string
    {
        $result = [];
        for ($i = 0; $i < $words; $i++) {
            $result[] = $this->word();
        }

        return ucfirst(implode(' ', $result)).'.';
    }

    public function paragraph(): string
    {
        return $this->sentence(10).' '.$this->sentence(8).' '.$this->sentence(12);
    }

    public function number(int $min = 1, int $max = 100): int
    {
        return random_int($min, $max);
    }

    public function boolean(): bool
    {
        return (bool) random_int(0, 1);
    }

    public function randomElement(array $array): mixed
    {
        return $array[array_rand($array)];
    }

    public function dateTime(string $format = 'Y-m-d H:i:s'): string
    {
        return date($format, time() - random_int(0, 365 * 24 * 3600));
    }

    public function url(): string
    {
        return 'https://example-'.uniqid().'.com';
    }

    public function slug(string $prefix = 'post'): string
    {
        return $prefix.'-'.uniqid();
    }
}
