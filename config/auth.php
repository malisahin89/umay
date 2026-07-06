<?php

declare(strict_types=1);

use App\Models\User;
use Core\Auth\EloquentUserProvider;

/**
 * Authentication Configuration
 *
 * Pluggable wiring: the core (Core\Auth) learns WHICH user model and WHICH
 * "provider" to use from here — it never references App\ directly. To write your
 * own login logic, implement Core\Contracts\UserProvider and point a provider's
 * 'driver' at your class.
 *
 * Takılabilir bağlama: çekirdek (Core\Auth) HANGİ kullanıcı modelini ve HANGİ
 * "provider"ı kullanacağını buradan öğrenir — App\'i asla doğrudan tanımaz. Kendi
 * login mantığınızı yazmak için Core\Contracts\UserProvider implemente edin ve bir
 * provider'ın 'driver'ını kendi sınıfınıza yönlendirin.
 */
return [

    /*
     * Active provider — a key from the 'providers' list below.
     * Aktif provider — aşağıdaki 'providers' listesinden bir anahtar.
     */
    'default' => $_ENV['AUTH_PROVIDER'] ?? 'eloquent',

    /*
     * Provider definitions. Each has a 'driver' (a Core\Contracts\UserProvider class).
     * The default Eloquent driver also expects a 'model' (an Authenticatable).
     *
     * Provider tanımları. Her biri bir 'driver' (Core\Contracts\UserProvider sınıfı) içerir.
     * Varsayılan Eloquent driver'ı ayrıca bir 'model' (Authenticatable) bekler.
     */
    'providers' => [
        'eloquent' => [
            'driver' => EloquentUserProvider::class,
            'model' => User::class,
        ],

        // Example — your own login logic (no core changes needed):
        // Örnek — kendi login mantığınız (çekirdek değişmeden):
        // 'api' => [
        //     'driver' => \App\Auth\ApiUserProvider::class,
        // ],
    ],
];
