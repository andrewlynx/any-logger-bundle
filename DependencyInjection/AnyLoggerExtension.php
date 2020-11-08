<?php

namespace Andrewlynx\Bundle\DependencyInjection;

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

        $container->setParameter('andrewlynx.any_logger.filename', $config['filename'] ?? null);
        $container->setParameter('andrewlynx.any_logger.view_permissions', $config['view_permissions'] ?? null);
        $container->setParameter('andrewlynx.any_logger.remove_permissions', $config['remove_permissions'] ?? null);
    }
}