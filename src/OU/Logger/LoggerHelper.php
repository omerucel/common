<?php

namespace OU\Logger;

use Psr\Log\LoggerInterface;
use OU\RequestId;

class LoggerHelper
{
    /**
     * @var RequestId
     */
    protected $requestId;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    protected $loggers = [];

    /**
     * @param RequestId $requestId
     * @param array $config
     */
    public function __construct(RequestId $requestId, array $config)
    {
        $this->requestId = $requestId;
        $this->config = $config;
    }

    /**
     * @return LoggerInterface
     */
    public function getDefaultLogger()
    {
        return $this->getLogger($this->config['default_name']);
    }

    /**
     * @param $name
     * @return LoggerInterface
     */
    public function getLogger($name)
    {
        if (!isset($this->loggers[$name])) {
            $filename = $name . '-' . $this->config['app_environment'];
            $config = array_merge($this->config, ['filename' => $filename]);
            $this->loggers[$name] = new Logger($this->requestId, $config);
        }
        return $this->loggers[$name];
    }

    public function resetLoggers()
    {
        /**
         * @var Logger $logger
         */
        foreach ($this->loggers as $name => $logger) {
            $logger->resetLineCounter();
        }
    }
}
