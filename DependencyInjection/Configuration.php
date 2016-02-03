<?php

namespace Youshido\TokenAuthenticatorBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('token_authenticator');

        $rootNode
            ->children()
                ->scalarNode('user_model')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('login_field')
                    ->defaultValue('email')
                    ->cannotBeEmpty()
                ->end()
                ->integerNode('token_lifetime')
                    ->defaultValue('864000') //10 days
                    ->cannotBeEmpty()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
