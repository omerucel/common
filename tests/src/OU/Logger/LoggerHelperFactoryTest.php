<?php

namespace OU\Logger;

use OU\RequestId;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use Zend\Config\Config;

class LoggerHelperFactoryTest extends TestCase
{
    public function testFactory()
    {
        $requestId = new RequestId('test');
        $config = new Config([
            'environment' => 'test',
            'logger' => [
                'default_name' => 'app',
                'path' => realpath(__DIR__),
                'level' => LogLevel::DEBUG
            ]
        ]);
        $loggerHelper = LoggerHelperFactory::create($requestId, $config);
        $this->assertInstanceOf(LoggerHelper::class, $loggerHelper);
    }
}
