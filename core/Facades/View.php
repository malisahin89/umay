<?php

declare(strict_types=1);

namespace Core\Facades;

use Core\Support\Facade;

/**
 * View Facade — static proxy for Core\View.
 * View Facade — Core\View için statik proxy.
 *
 * Usage / Kullanım:
 *   View::render('users/index', ['users' => $users]);
 *   View::share('siteName', 'Umay');            // every view & layout / her view & layout
 *   View::share(['langUrls' => $urls]);         // bulk / toplu
 *
 * @method static void render(string $template, array $data = [])
 * @method static void share(string|array<string, mixed> $key, mixed $value = null)
 *
 * @see \Core\View
 */
class View extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Core\View::class;
    }
}
