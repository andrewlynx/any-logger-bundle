<?php

namespace Andrewlynx\Bundle\DependencyInjection;

use Andrewlynx\Bundle\Constant\AnyLoggerConstant;
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
                ->enumNode(AnyLoggerConstant::FILENAME)
                    ->values([AnyLoggerConstant::NAME_DATE, AnyLoggerConstant::NAME_EVENT, AnyLoggerConstant::NAME_DATE_EVENT])
                    ->defaultValue(AnyLoggerConstant::NAME_DATE)
                ->end()
                ->integerNode(AnyLoggerConstant::PARSE_JSON_SIZE_LIMIT)
                    ->defaultValue(AnyLoggerConstant::DEFAULT_PARSE_JSON_SIZE_LIMIT)
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}