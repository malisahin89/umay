<?php

declare(strict_types=1);

namespace Core\Contracts;

use Core\Request;

/**
 * Middleware Interface — contract that all middleware classes must adhere to.
 * Middleware Interface — tüm middleware sınıflarının uyması gereken sözleşme.
 *
 * Usage / Kullanım:
 *   class AuthMiddleware implements MiddlewareInterface
 *   {
 *       public function handle(Request $request, \Closure $next): mixed
 *       {
 *           if (!Auth::check()) {
 *               return redirect('/login');
 *           }
 *           return $next($request);
 *       }
 *   }
 */
interface MiddlewareInterface
{
    /**
     * Process an incoming request.
     * Gelen isteği işle.
     *
     * @param  Request  $request  Incoming HTTP request // Gelen HTTP isteği
     * @param  \Closure  $next  Next middleware/handler in the pipeline // Pipeline'daki bir sonraki middleware/handler
     */
    public function handle(Request $request, \Closure $next): mixed;
}
