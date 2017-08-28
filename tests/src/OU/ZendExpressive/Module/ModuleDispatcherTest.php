<?php

namespace OU\ZendExpressive\Module;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Zend\Config\Config;

class ModuleDispatcherTest extends TestCase
{
    protected $apiModule;
    protected $panelModule;
    protected $container;
    protected $config;
    protected $uri;
    protected $request;

    protected function setUp()
    {
        $this->apiModule = $this->getMockBuilder(Module::class)->getMock();
        $this->panelModule = $this->getMockBuilder(Module::class)->getMock();
        $this->container = $this->getMockBuilder(ContainerInterface::class)->getMock();
        $this->container->expects($this->any())
            ->method('get')
            ->willReturnCallback(function ($className) {
                if ($className == 'Project\Module\Api\ApiModule') {
                    return $this->apiModule;
                } elseif ($className == 'Project\Module\Panel\PanelModule') {
                    return $this->panelModule;
                }
            });
        $this->config = new Config([
            'modules' => [
                '/api' => 'Project\Module\Api\ApiModule',
                '/panel' => 'Project\Module\Panel\PanelModule'
            ]
        ]);
        $this->uri = $this->getMockBuilder(UriInterface::class)->getMock();
        $this->request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $this->request->expects($this->any())
            ->method('getUri')
            ->willReturn($this->uri);
    }

    public function testRegisterModule()
    {
        $this->apiModule->expects($this->any())
            ->method('register')
            ->willReturnCallback(function () {
                $this->assertTrue(true);
            });
        $this->panelModule->expects($this->any())
            ->method('register')
            ->willReturnCallback(function () {
                $this->assertTrue(true);
            });

        $this->uri->expects($this->any())
            ->method('getPath')
            ->willReturn('/test');
        $dispatcher = new ModuleDispatcher($this->container, $this->config);
        $dispatcher->dispatch($this->request);
    }

    public function testDispatchFirstModule()
    {
        $this->apiModule->expects($this->any())
            ->method('run')
            ->willReturnCallback(function () {
                $this->assertTrue(true);
            });
        $this->panelModule->expects($this->any())
            ->method('run')
            ->willReturnCallback(function () {
                throw new \Exception();
            });
        $this->uri->expects($this->any())
            ->method('getPath')
            ->willReturn('/api');
        $dispatcher = new ModuleDispatcher($this->container, $this->config);
        $dispatcher->dispatch($this->request);
    }
}
