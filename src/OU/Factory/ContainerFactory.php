<?php

namespace OU\Factory;

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Zend\Config\Config;

class ContainerFactory
{
    /**
     * @param $environment
     * @param $configPath
     * @return ContainerInterface
     */
    public static function factory($environment, $configPath)
    {
        $configs = include($configPath . '/env/' . $environment . '.php');
        $configs['environment'] = $environment;
        $builder = new ContainerBuilder();
        if (isset($configs['php_di']) && isset($configs['php_di']['definitions'])) {
            $builder->addDefinitions($configs['php_di']['definitions']);
        }
        $container = $builder->build();
        $config = new Config($configs, true);
        $container->set(Config::class, $config);
        $container->set('config', $config);
        return $container;
    }
}
