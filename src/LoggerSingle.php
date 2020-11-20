<?php

namespace Lynncho\Aliyunlog\GatherWrite;

use RuntimeException;

/**
 * Class LoggerSingle
 * @package Lynncho\Aliyunlog\GatherWrite
 *
 * @method static emergency($message, array $context = array())
 * @method static alert($message, array $context = array())
 * @method static critical($message, array $context = array())
 * @method static error($message, array $context = array())
 * @method static warning($message, array $context = array())
 * @method static notice($message, array $context = array())
 * @method static info($message, array $context = array())
 * @method static debug($message, array $context = array())
 * @method static log($level, $message, array $context = array())
 * @method static addLogItemFields(array $fields, $newLogItem = false)
 * @method static push()
 * @method static \Aliyun_Log_Client getLogClient()
 * @method static array getConfig()
 */
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
