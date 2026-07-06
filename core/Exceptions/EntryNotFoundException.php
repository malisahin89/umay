<?php

declare(strict_types=1);

namespace Core\Exceptions;

use Psr\Container\NotFoundExceptionInterface;

/**
 * Entry not found exception — thrown when a requested entry is not found in the container.
 * Kayıt bulunamadı exception — container'da istenen kayıt bulunamadığında fırlatılır.
 *
 * Implements PSR-11 NotFoundExceptionInterface.
 * PSR-11 NotFoundExceptionInterface'ini implemente eder.
 *
 * @see https://www.php-fig.org/psr/psr-11/
 */
class EntryNotFoundException extends ContainerException implements NotFoundExceptionInterface {}
