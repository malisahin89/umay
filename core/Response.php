<?php

declare(strict_types=1);

namespace Core;

/**
 * @deprecated Use ResponseBuilder, abort() and redirect() helpers.
 * @deprecated ResponseBuilder, abort() ve redirect() helper'larını kullanın.
 *
 * response()->json($data)              → ResponseBuilder::json()
 * response()->html($body)              → ResponseBuilder::html()
 * abort(404)                           → ExceptionHandler → errors/404 view
 * abort(403)                           → ExceptionHandler → errors/403 view
 * abort(500)                           → ExceptionHandler → errors/500 view
 * \Core\Redirect::to($url)             → redirect()
 */
class Response
{
    /**
     * @deprecated Use response()->json($data, $status)
     * @deprecated response()->json($data, $status) kullanın
     */
    public static function json(mixed $data, int $status = 200): void
    {
        response()->json($data, $status)->send();
    }

    /**
     * @deprecated Use \Core\Redirect::to($url)
     * @deprecated \Core\Redirect::to($url) kullanın
     */
    public static function redirect(string $url, int $status = 302): void
    {
        http_response_code($status);
        header('Location: '.str_replace(["\r", "\n"], '', $url));
        throw new RedirectException;
    }

    /**
     * @deprecated Use abort(404)
     * @deprecated abort(404) kullanın
     */
    public static function notFound(string $message = '404 - Sayfa Bulunamadı'): void
    {
        abort(404, $message);
    }

    /**
     * @deprecated Use abort(403)
     * @deprecated abort(403) kullanın
     */
    public static function forbidden(string $message = '403 - Erişim Yasak'): void
    {
        abort(403, $message);
    }

    /**
     * @deprecated Use abort(500)
     * @deprecated abort(500) kullanın
     */
    public static function serverError(string $message = '500 - Sunucu Hatası'): void
    {
        abort(500, $message);
    }
}
