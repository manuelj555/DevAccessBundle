<?php

namespace Manuel\Bundle\DevAccessBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class DevAccessExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('manuel.dev_access.security.roles', $config['roles']);
        $container->setParameter('manuel.dev_access.security.users', $config['users']);

        $container->findDefinition('manuel.dev_access.security.access_config')
            ->replaceArgument(1, $config['sessions_path']);

        $container->findDefinition('manuel.dev_access.security.listener.active_session_listener')
            ->replaceArgument(3, $config['environment']);
    }
}
