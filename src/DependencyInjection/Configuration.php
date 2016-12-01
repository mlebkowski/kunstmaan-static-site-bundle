<?php

namespace Nassau\KunstmaanStaticSiteBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('kunstmaan_static_site');

        $rootNode->children()->scalarNode('url_prefix')->defaultValue('');

        /** @var ArrayNodeDefinition $files */
        $files = $rootNode->children()->arrayNode('files')->prototype('array');
        $files->beforeNormalization()->ifNull()->then(function () {
            return ['exclude' => '*'];
        });

        $files->children()->scalarNode('directory')->isRequired();
        $files->children()->scalarNode('target')->defaultValue('/');
        foreach (['include', 'exclude'] as $node) {
            $node = $files->children()->arrayNode($node);
            $node->beforeNormalization()->ifString()->then(function ($v) { return [$v]; });
            $node->prototype('scalar');
        }

        /** @var ArrayNodeDefinition $routes */
        $routes = $rootNode->children()->arrayNode('routes')->prototype('array');
        $routes->beforeNormalization()->ifNull()->then(function () {
            return ['route' => '', 'generator' => 'disabled', 'defaults' => []];
        });

        $routes->children()->scalarNode('route')->isRequired();
        $routes->children()->scalarNode('generator')->defaultValue("");
        $routes->children()->arrayNode('defaults')->prototype('scalar');

        return $treeBuilder;
    }
}
