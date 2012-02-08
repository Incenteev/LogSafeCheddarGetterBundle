<?php

/*
 * This file is part of the LogsafeCheddarGetterBundle package.
 *
 * (c) LogSafe <http://logsafe.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Logsafe\CheddarGetterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('logsafe_cheddar_getter');

        $httpAdapterTypes = array('curl', 'service');

        $rootNode
            ->children()
                ->scalarNode('url')->defaultValue('https://cheddargetter.com')->end()
                ->scalarNode('username')->isRequired()->end()
                ->scalarNode('password')->isRequired()->end()
                ->scalarNode('product_code')->defaultNull()->end()
                ->scalarNode('product_id')->defaultNull()->end()
                ->arrayNode('http_adapter')
                    ->addDefaultsIfNotSet()
                    ->beforeNormalization()
                        ->ifString()
                        ->then(function($v) {return array('type' => $v);})
                    ->end()
                    ->children()
                        ->scalarNode('type')
                            ->cannotBeEmpty()
                            ->defaultValue('curl')
                            ->validate()
                                ->ifNotInArray($httpAdapterTypes)
                                ->thenInvalid('This %s type is not supported. It should be one of '.json_encode($httpAdapterTypes))
                            ->end()
                        ->end()
                        ->scalarNode('id')->end()
                    ->end()
                    ->validate()
                        ->ifTrue(function($v) {return 'service' === $v['type'] && empty($v['id']);})
                        ->thenInvalid('The id is mandatory for the "service" type.')
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
