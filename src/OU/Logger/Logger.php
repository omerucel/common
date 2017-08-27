<?php

namespace OU\Logger;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use OU\RequestId;

class Logger extends AbstractLogger
{
    private static $LEVEL = [
        LogLevel::DEBUG => 7,
        LogLevel::INFO => 6,
        LogLevel::NOTICE => 5,
        LogLevel::WARNING => 4,
        LogLevel::ERROR => 3,
        LogLevel::CRITICAL => 2,
        LogLevel::ALERT => 1,
        LogLevel::EMERGENCY => 0
    ];

    /**
     * @var RequestId
     */
    protected $requestId;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var int
     */
    protected $lineCounter = 0;
    protected $fileResource;
    protected $fileResourceDate;

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
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return void
     */
    public function log($level, $message, array $context = array())
    {
        if (isset(static::$LEVEL[$level]) && static::$LEVEL[$level] > static::$LEVEL[$this->config['level']]) {
            return;
        }
        $this->lineCounter++;
        $logLine = $this->format($level, $message, $context);
        fwrite($this->getFileResource(), $logLine);
    }

    /**
     * @param $level
     * @param $message
     * @param array $context
     * @return string
     */
    protected function format($level, $message, array $context = array())
    {
        if ($message instanceof \Throwable) {
            $message = '[' . $message->getCode() . '] ' . get_class($message) . ' ' . $message->getMessage() . ' '
                . $message->getFile() . ':' . $message->getLine() . ' ' . $message->getTraceAsString();
            $message = str_replace("\n", '', $message);
        }
        if (empty($context) == false) {
            $message.= ' ' . json_encode($context);
        }
        return '[' . date('Y-m-d H:i:s e') . '] '
            . '[' . $this->requestId . '-' . $this->lineCounter . '] '
            . '[' . strtoupper($level) . '] '
            . $message . PHP_EOL;
    }

    /**
     * @param null $currentDate
     * @return bool|resource
     */
    public function getFileResource($currentDate = null)
    {
        if ($currentDate == null) {
            $currentDate = date('Y-m-d');
        }
        if ($this->fileResource == null || $this->fileResourceDate != $currentDate) {
            $filePath = $this->config['path'] . '/' . $this->config['filename'] . '-' . $currentDate . '.log';
            $this->fileResource = fopen($filePath, 'a');
            $this->fileResourceDate = $currentDate;
        }
        return $this->fileResource;
    }

    public function resetLineCounter()
    {
        $this->lineCounter = 0;
    }
}
