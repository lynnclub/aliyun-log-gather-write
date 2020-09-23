<?php

namespace Lynncho\Aliyunlog\GatherWrite;

use RuntimeException;

class LoggerSingle
{
    protected static $logger;

    public static function init(array $config, \Closure $customerLogger = null)
    {
        self::$logger = new Logger($config, $customerLogger);
    }

    public static function setLogger(LoggerInterface $logger)
    {
        self::$logger = $logger;
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param  string $method
     * @param  array $args
     * @return mixed
     *
     * @throws RuntimeException
     */
    public static function __callStatic($method, $args)
    {
        if (!self::$logger) {
            throw new RuntimeException('Can not get facade object.');
        }

        return self::$logger->$method(...$args);
    }
}
