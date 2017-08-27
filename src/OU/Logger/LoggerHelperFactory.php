<?php

namespace OU\Logger;

use OU\RequestId;
use Zend\Config\Config;

class LoggerHelperFactory
{
    /**
     * @param RequestId $requestId
     * @param Config $config
     * @return LoggerHelper
     */
    public static function create(RequestId $requestId, Config $config)
    {
        $configs = $config->logger->toArray();
        $configs['app_environment'] = $config->environment;
        return new LoggerHelper($requestId, $configs);
    }
}
