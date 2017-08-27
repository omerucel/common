<?php

namespace OU\ZendExpressive\Module;

use Psr\Container\ContainerInterface;

abstract class ModuleAbstract implements Module
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
