<?php

namespace OU\ZendExpressive\Module;

use Psr\Container\ContainerInterface;
use Zend\Config\Config;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\ServerRequestFactory;

class ModuleDispatcher
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param ContainerInterface $container
     * @param Config $config
     */
    public function __construct(ContainerInterface $container, Config $config)
    {
        $this->container = $container;
        $this->config = $config;
    }

    /**
     * @param ServerRequest|null $request
     */
    public function dispatch(ServerRequest $request = null)
    {
        if ($request == null) {
            $request = ServerRequestFactory::fromGlobals();
        }
        foreach ($this->config->modules->toArray() as $pattern => $moduleClass) {
            if (preg_match('#^/?' . $pattern . '/?#', $request->getUri()->getPath(), $matches)) {
                $this->container->get($moduleClass)->run();
                break;
            }
        }
    }
}
