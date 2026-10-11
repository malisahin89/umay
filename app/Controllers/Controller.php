<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * Base controller — extend your controllers from this class.
 * Temel controller — kendi controller'larınızı bundan türetin.
 *
 * The router can dispatch any class under the configured controller namespace
 * (config/app.php → 'controller_namespace'); extending this base is a convention,
 * not a requirement.
 *
 * Router, yapılandırılmış controller namespace'i altındaki herhangi bir sınıfı
 * çalıştırabilir (config/app.php → 'controller_namespace'); bu tabandan türemek
 * bir kuraldır, zorunluluk değil.
 */
abstract class Controller {}
