<?php

declare(strict_types=1);

namespace Core;

/**
 * Flow termination exception — response gönderildikten sonra fırlatılır.
 *
 * Bu exception gerçek bir hata değildir; request lifecycle'ını sonlandırmak için kullanılır.
 * ExceptionHandler bu exception'ı sessizce yakalar ve exit yapar.
 *
 * Kullanıldığı yerler:
 *   - Redirect::to()          → header gönderildikten sonra
 *   - ResponseBuilder::send() → body echo edildikten sonra
 *   - SecurityHeaders         → HTTPS redirect sonrası
 *
 * @see ExceptionHandler::handle()
 */
class TerminateException extends \RuntimeException {}
