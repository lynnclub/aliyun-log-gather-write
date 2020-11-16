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
$logger->log(LogLevel::DEBUG, '[LoggerTest]log debug', ['log info' => ['test']]);
$logger->info('[LoggerTest]info', ['info' => ['test']]);

$logger->error('[LoggerTest]error', ['info' => ['error']]);
$logger->addLogItemFields(['TestField' => 123]);

$logger->addLogItemFields(['NewLogTestField' => '[LoggerTest]1234'], true);

LoggerSingle::setLogger($logger);
LoggerSingle::info('[LoggerTest]LoggerSingle info', ['info' => ['test']]);

$logger->push();

LoggerSingle::init($config);
LoggerSingle::error('[LoggerTest]LoggerSingle error', ['info' => ['test']]);
LoggerSingle::push();