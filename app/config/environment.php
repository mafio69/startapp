<?php

class Environment
{
    public static function isDevelopment(): bool
    {
        // Użyj null coalesce operator dla bezpiecznego dostępu
        return ($_ENV['APP_ENV'] ?? '') === 'development' ||
            ($_ENV['PHP_ENV'] ?? '') === 'dev' ||
            (extension_loaded('xdebug') && function_exists('xdebug_info'));
    }

    public static function isProduction(): bool
    {
        return ($_ENV['APP_ENV'] ?? '') === 'production' ||
            ($_ENV['PHP_ENV'] ?? '') === 'prod';
    }

    public static function getLogLevel(): string
    {
        return self::isDevelopment() ? 'DEBUG' : 'WARNING';
    }

    public static function shouldDisplayErrors(): bool
    {
        return self::isDevelopment();
    }
}
