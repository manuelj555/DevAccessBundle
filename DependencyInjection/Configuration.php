<?php

namespace Manuel\Bundle\DevAccessBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dev_access');

        $rootNode
            ->children()
                ->arrayNode('roles')
                    ->cannotBeEmpty()
                    ->defaultValue(['ROLE_SUPER_ADMIN'])
                    ->prototype('scalar')->end()
                ->end()
            ->arrayNode('users')
                ->prototype('scalar')->end()
            ->end()
            ->scalarNode('sessions_path')->defaultValue('%kernel.cache_dir%/../')->end()
            ->end();


        return $treeBuilder;
    }
}
