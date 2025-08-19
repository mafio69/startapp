<?php

require_once 'environment.php';

class Logger
{
    private static $logFile = '/var/log/app/application.log';

    public static function log(string $level, string $message, array $context = []): void
    {
        $levels = ['DEBUG' => 0, 'INFO' => 1, 'WARNING' => 2, 'ERROR' => 3];
        $currentLevel = Environment::getLogLevel();

        if ($levels[$level] >= $levels[$currentLevel]) {
            $timestamp = date('Y-m-d H:i:s');
            $contextStr = $context ? ' ' . json_encode($context) : '';
            $logLine = "[$timestamp] $level: $message$contextStr" . PHP_EOL;

            file_put_contents(self::$logFile, $logLine, FILE_APPEND | LOCK_EX);
        }
    }

    public static function debug(string $message, array $context = []): void
    {
        self::log('DEBUG', $message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::log('WARNING', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::log('ERROR', $message, $context);
    }
}
