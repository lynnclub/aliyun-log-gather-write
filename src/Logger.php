<?php

namespace Lynncho\Aliyunlog\GatherWrite;

use Psr\Log\AbstractLogger;
use RuntimeException;

class Logger extends AbstractLogger implements LoggerInterface
{
    protected $config = [
        'endPoint',
        'accessId',
        'accessKey',
        'project',
        'logStore',
    ];

    protected $logItems = [];

    protected $logItem;

    protected $customerLogger = null;

    /**
     * Logger constructor.
     *
     * @param array $config
     * @param \Closure $customerLogger
     */
    public function __construct(array $config, \Closure $customerLogger = null)
    {
        if (!isset($config['endPoint'], $config['accessId'], $config['accessKey'], $config['project'], $config['logStore'])) {
            throw new RuntimeException('Aliyun log config error.');
        }

        $this->config = $config;
        $this->customerLogger = $customerLogger;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @throws \Exception
     */
    public function log($level, $message, array $context = array())
    {
        $this->logItem = new \Aliyun_Log_Models_LogItem;
        $this->logItem->pushBack('Message', $message);
        $this->logItem->pushBack('Level', strtoupper($level));
        $this->logItem->pushBack('Date', date('Y-m-d H:i:s'));
        $this->logItem->pushBack('Content', json_encode($context, JSON_UNESCAPED_UNICODE));

        $this->logItems[] = $this->logItem;

        $customerLogger = $this->customerLogger;
        if ($customerLogger instanceof \Closure) {
            $customerLogger($level, $message, $context);
        }
    }

    /**
     * Add fields to Log Item
     *
     * @param array $fields
     * @param bool $newLogItem
     */
    public function addLogItemFields(array $fields, $newLogItem = false)
    {
        if ($newLogItem) {
            $this->logItems[] = $this->logItem = new \Aliyun_Log_Models_LogItem;

            $logItem = &$this->logItem;
        } else {
            $logItem = \end($this->logItems);
        }

        foreach ($fields as $key => $field) {
            $logItem->pushBack($key, $field);
        }
    }

    /**
     * Push logs
     *
     * @return \Aliyun_Log_Models_PutLogsResponse|false
     * @throws \Aliyun_Log_Exception
     */
    public function push()
    {
        if (empty($this->logItems)) {
            return false;
        }

        $putLogsRequest = new \Aliyun_Log_Models_PutLogsRequest($this->config['project'], $this->config['logStore'], null, null, $this->logItems);

        $client = new \Aliyun_Log_Client($this->config['endPoint'], $this->config['accessId'], $this->config['accessKey']);
        return $client->putLogs($putLogsRequest);
    }

}
