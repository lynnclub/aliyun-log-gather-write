<?php

require __DIR__ . '/../vendor/autoload.php';

use Lynncho\Aliyunlog\GatherWrite\Logger;
use Lynncho\Aliyunlog\GatherWrite\LoggerSingle;
use Psr\Log\LogLevel;

$config = [
    'endPoint' => '',
    'accessId' => '',
    'accessKey' => '',
    'project' => '',
    'logStore' => ''
];

$logger = new Logger($config);
$logger->log(LogLevel::DEBUG, 'log info', ['log info' => ['test']]);
$logger->info('info', ['info' => ['test']]);

$logger->error('error', ['info' => ['error']]);
$logger->additionFields(['TestField' => 123]);

LoggerSingle::setLogger($logger);
LoggerSingle::info('LoggerSingle info', ['info' => ['test']]);

$logger->push();

LoggerSingle::init($config);
LoggerSingle::error('LoggerSingle error', ['info' => ['test']]);
LoggerSingle::push();