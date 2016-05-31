<?php

/*
 * This file is part of the FOSRestBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\AdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('cmf_admin', 'array');

        $rootNode
            ->children()
                ->scalarNode('default_layout_template')
                    ->defaultValue('CmfAdminBundle::layout.html.twig')
                ->end()
                ->arrayNode('menu')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->variableNode('items')
                            ->defaultValue([])
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
