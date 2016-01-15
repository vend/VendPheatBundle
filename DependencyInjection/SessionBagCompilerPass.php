<?php

namespace Vend\PheatBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SessionBagCompilerPass implements CompilerPassInterface
{
    const DEFAULT_PRIORITY = 10;

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('pheat.provider.session')) {
            return;
        }

        $container->getDefinition('session')->addMethodCall('registerBag', array(new Reference('pheat.session_cache_bag')));
    }
}
