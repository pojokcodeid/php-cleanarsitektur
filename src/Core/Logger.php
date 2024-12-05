<?php

namespace App\Core;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;

class Logger {
    private static $logger;

    public static function getLogger() {
        if (!self::$logger) {
            self::$logger = new MonologLogger('app');
            self::$logger->pushHandler(new StreamHandler(__DIR__ . '/../../logs/app.log', MonologLogger::DEBUG));
        }
        return self::$logger;
    }
}
