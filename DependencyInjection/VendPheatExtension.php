<?php

namespace Vend\PheatBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class VendPheatExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $config = $this->processConfiguration(new Configuration(), $configs);

        $container->setParameter('pheat.manager.class', $config['manager']['class']);
        $container->setParameter('pheat.context.class', $config['context']['class']);

        if ($config['providers']['session']) {
            $loader->load('session.xml');
        }

        if ($config['providers']['config']) {
            $loader->load('config.xml');
        }

        $loader->load('services.xml');
    }

    public function getAlias()
    {
        return 'pheat';
    }

    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return new Configuration($config, $container);
    }

    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/schema';
    }
}
