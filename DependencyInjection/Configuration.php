<?php

namespace Andrewlynx\Bundle\DependencyInjection;

use Andrewlynx\Bundle\Constant\AnyLogger;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('any_logger');

        $treeBuilder->getRootNode()
            ->children()
                ->enumNode(AnyLogger::FILENAME)
                    ->values([AnyLogger::NAME_DATE, AnyLogger::NAME_EVENT, AnyLogger::NAME_DATE_EVENT])
                    ->defaultValue(AnyLogger::NAME_DATE)
                ->end()
                ->scalarNode(AnyLogger::PERMISSIONS_VIEW)
                ->end()
                ->scalarNode(AnyLogger::PERMISSIONS_REMOVE)
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}