<?php

namespace OU\Logger;

use OU\RequestId;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class LoggerTest extends TestCase
{
    /**
     * @var Logger
     */
    protected $logger;

    protected function setUp()
    {
        $requestId = new RequestId('test');
        $config = [
            'path' => 'php://temp',
            'filename' => 'app',
            'level' => LogLevel::NOTICE
        ];
        $this->logger = new Logger($requestId, $config);
    }

    public function testLog()
    {
        $this->logger->log(LogLevel::ERROR, 'Message', ['param' => 'value']);
        $this->assertRegExp(
            '#\[\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2} UTC\] \[test\-1\] \[ERROR\] Message \{\"param\"\:\"value\"\}#' . PHP_EOL,
            stream_get_contents($this->logger->getFileResource(), 1024, 0)
        );
    }

    public function testLogException()
    {
        $exception = new \Exception('Message', -1234);
        $this->logger->log(LogLevel::ERROR, $exception, ['param' => 'value']);
        $this->assertRegExp(
            '#\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} UTC\] \[test-1\] \[ERROR\] \[-1234\] Exception Message.*#' . PHP_EOL,
            stream_get_contents($this->logger->getFileResource(), 1024, 0)
        );
    }

    public function testLevelFilter()
    {
        $this->logger->debug('TEST');
        $this->assertEmpty(stream_get_contents($this->logger->getFileResource(), 1024, 0));
    }
}
