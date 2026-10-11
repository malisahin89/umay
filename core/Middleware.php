<?php

declare(strict_types=1);

namespace Core;

/**
 * @deprecated CSRF kontrolü artık Core\Middleware\VerifyCsrfToken tarafından
 * middleware pipeline içinde yapılmaktadır. Bu sınıf kullanılmamaktadır.
 */
class Middleware
{
    // Kullanım dışı — bkz. core/Middleware/VerifyCsrfToken.php
}
