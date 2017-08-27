<?php

namespace OU\Factory;

use DI\Container;
use OU\RequestId;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Zend\Config\Config;

class ContainerFactoryTest extends TestCase
{
    public function testFactory()
    {
        $environment = 'test';
        $configPath = realpath(__DIR__) . '/ContainerFactoryFixture';
        $container = ContainerFactory::factory($environment, $configPath);
        $this->assertInstanceOf(Container::class, $container);
        $this->assertInstanceOf(ContainerInterface::class, $container);
        $this->assertInstanceOf(Config::class, $container->get(Config::class));
        $this->assertInstanceOf(Config::class, $container->get('config'));
        $this->assertEquals('test', $container->get(Config::class)->environment);
        $this->assertEquals('test-id', $container->get(RequestId::class)->__toString());
    }
}
