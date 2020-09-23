<?php

namespace Lynncho\Aliyunlog\GatherWrite;

interface LoggerInterface
{
    /**
     * Add addition fields to Log Item
     *
     * @param array $fields
     */
    public function additionFields(array $fields);

    /**
     * Push logs
     *
     * @return \Aliyun_Log_Models_PutLogsResponse|false
     * @throws \Aliyun_Log_Exception
     */
    public function push();
}