<?php

namespace OpsCopter\DB\ArtifactsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('copter_db_artifacts');

        $rootNode
            ->children()
                ->arrayNode('aws_client')
                    ->children()
                        ->scalarNode('secret')->isRequired()->end()
                        ->scalarNode('key')->isRequired()->end()
                    ->end()
                ->end()
                ->scalarNode('role_arn')->isRequired()->end()
                ->scalarNode('bucket')->isRequired()->end()
            ->end();

        return $treeBuilder;
    }
}
