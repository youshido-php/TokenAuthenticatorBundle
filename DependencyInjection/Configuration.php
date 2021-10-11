<?php

namespace Youshido\TokenAuthenticationBundle\DependencyInjection;

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
        
		$treeBuilder = new TreeBuilder('token_authentication');
        $rootNode    = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('user_model')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('token_field')
                    ->defaultValue('accessToken')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('login_field')
                    ->defaultValue('email')
                    ->cannotBeEmpty()
                ->end()
                ->enumNode('platform')
                    ->values(['orm', 'odm'])
                    ->defaultValue('orm')
                ->end()
                ->enumNode('odm_version')
                    ->values([1, 2])
                    ->defaultValue(1)
                ->end()
                ->integerNode('token_lifetime')
                    ->defaultValue('864000') //10 days
                ->end()
                ->arrayNode('error_codes')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                        ->children()
                            ->integerNode('invalid_token')->defaultValue(401)->end()
                        ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
