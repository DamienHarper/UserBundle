<?php

namespace DH\UserBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DHUserExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $container->setParameter('dh_userbundle.user_class', isset($config['user_class']) ? $config['user_class'] : null);
        $container->setParameter('dh_userbundle.password_reset.email_from', isset($config['password_reset']['email_from']) ? $config['password_reset']['email_from'] : null);
        $container->setParameter('dh_userbundle.password_reset.token_ttl', isset($config['password_reset']['token_ttl']) ? $config['password_reset']['token_ttl'] : null);
    }
}
