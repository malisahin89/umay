<?php

declare(strict_types=1);

namespace Core;

class Csrf
{
    public static function generate(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        /** @var string $token */
        $token = $_SESSION['csrf_token'];

        return $token;
    }

    public static function check(mixed $token): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (! is_string($token) || $token === '') {
            return false;
        }

        if (! isset($_SESSION['csrf_token']) || ! is_string($_SESSION['csrf_token'])) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $token);
    }
}
