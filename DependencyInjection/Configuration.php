<?php

/*
 * (c) EDSI-Tech Sarl - All rights reserved.
 * This file cannot be copied and/or distributed without express permission of EDSI-Tech Sarl and all its content remains the property of EDSI-Tech Sarl.
 */

namespace EdsiTech\GandiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('edsi_tech_gandi');

        $rootNode
            ->children()
                ->scalarNode('server_url')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('api_key')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('default_nameservers')
                    ->prototype('scalar')
                        ->end()
                    ->defaultValue(array())
                    ->end()
                ->arrayNode('default_handles')
                    ->children()
                        ->scalarNode('bill')->end()
                        ->scalarNode('tech')->end()
                        ->scalarNode('admin')->end()
                        ->scalarNode('owner')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
        
    }
}