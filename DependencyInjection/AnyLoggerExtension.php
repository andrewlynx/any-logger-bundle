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
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter(
            AnyLogger::getParamName(AnyLoggerConstant::FILENAME),
            $config[AnyLoggerConstant::FILENAME] ?? AnyLoggerConstant::FIELD_EVENT
        );
        $container->setParameter(
            AnyLogger::getParamName(AnyLoggerConstant::PARSE_JSON_SIZE_LIMIT),
            $config[AnyLoggerConstant::PARSE_JSON_SIZE_LIMIT] ?? AnyLoggerConstant::DEFAULT_PARSE_JSON_SIZE_LIMIT
        );
    }
}