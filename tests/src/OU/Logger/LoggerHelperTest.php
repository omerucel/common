<?php

namespace OU\Logger;

use OU\RequestId;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class LoggerHelperTest extends TestCase
{
    /**
     * @var LoggerHelper
     */
    protected $loggerHelper;

    public function setUp()
    {
        $requestId = new RequestId('test');
        $configs = [
            'default_name' => 'app',
            'path' => 'php://memory',
            'level' => LogLevel::ERROR,
            'app_environment' => 'test'
        ];
        $this->loggerHelper = new LoggerHelper($requestId, $configs);
    }

    public function testGetDefaultLogger()
    {
        $logger = $this->loggerHelper->getDefaultLogger();
        $this->assertInstanceOf(LoggerInterface::class, $logger);
    }

    public function testGetLogger()
    {
        $logger = $this->loggerHelper->getLogger('sql');
        $this->assertInstanceOf(LoggerInterface::class, $logger);
    }
}
