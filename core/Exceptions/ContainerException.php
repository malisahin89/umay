<?php

declare(strict_types=1);

namespace Core\Exceptions;

use Psr\Container\ContainerExceptionInterface;

/**
 * Container exception — thrown when a general container error occurs.
 * Container exception — genel bir container hatası oluştuğunda fırlatılır.
 *
 * Implements PSR-11 ContainerExceptionInterface.
 * PSR-11 ContainerExceptionInterface'ini implemente eder.
 *
 * @see https://www.php-fig.org/psr/psr-11/
 */
class ContainerException extends \RuntimeException implements ContainerExceptionInterface {}
