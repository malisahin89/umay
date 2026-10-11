<?php

declare(strict_types=1);

namespace Core\Facades;

use Core\Support\Facade;

/**
 * Validator Facade — static proxy for Core\Validator.
 * Validator Facade — Core\Validator için statik proxy.
 *
 * Usage / Kullanım:
 *   $v = Validator::make($data, ['email' => 'required|email']);
 *   if ($v->fails()) { ... }
 *
 * @method static \Core\Validator make(array $data, array $rules, array $messages = [])
 *
 * @see \Core\Validator
 */
class Validator extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Core\Validator::class;
    }
}
