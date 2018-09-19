<?php

namespace DH\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
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
        $rootNode = $treeBuilder->root('dh_userbundle');

        $rootNode
            ->children()
                ->scalarNode('user_class')
                    ->info('User entity class (FQDN)')
                    ->defaultValue('App\Entity\User')
                ->end()
                ->arrayNode('password_reset')
                    ->children()
                        ->scalarNode('email_from')
                            ->info('Sender of password reset emails')
                            ->example('John Doe <jdoe@domain.tld>')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('token_ttl')
                            ->info('Token TTL (seconds)')
                            ->defaultValue(7200)
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
