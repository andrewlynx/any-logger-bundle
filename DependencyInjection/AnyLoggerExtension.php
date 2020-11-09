<?php

namespace Andrewlynx\Bundle\DependencyInjection;

use Andrewlynx\Bundle\Constant\AnyLoggerConstant;
use Andrewlynx\Bundle\AnyLogger\AnyLogger;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

class AnyLoggerExtension extends Extension
{
    /**
     * @inheritDoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter(
            AnyLogger::getParamName(AnyLoggerConstant::FILENAME),
            $config[AnyLoggerConstant::FILENAME] ?? null
        );
        $container->setParameter(
            AnyLogger::getParamName(AnyLoggerConstant::PERMISSIONS_VIEW),
            $config[AnyLoggerConstant::PERMISSIONS_VIEW] ?? null
        );
        $container->setParameter(
            AnyLogger::getParamName(AnyLoggerConstant::PERMISSIONS_REMOVE),
            $config[AnyLoggerConstant::PERMISSIONS_REMOVE] ?? null
        );
    }
}