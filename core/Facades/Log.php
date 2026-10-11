<?php

declare(strict_types=1);

namespace Core\Facades;

use Core\Logger;
use Core\Support\Facade;

/**
 * Log Facade — static proxy for Core\Logger.
 * Log Facade — Core\Logger için statik proxy.
 *
 * Usage / Kullanım:
 *   Log::info('User logged in', ['user_id' => 5]);
 *   Log::error('Payment failed', ['order_id' => 123]);
 *   Log::warning('Low stock', ['product' => 'Widget']);
 *
 * @method static void info(string $message, array $context = [])
 * @method static void error(string $message, array $context = [])
 * @method static void warning(string $message, array $context = [])
 *
 * @see Logger
 */
class Log extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Logger::class;
    }
}
