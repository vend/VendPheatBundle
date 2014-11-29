<?php

namespace Vend\PheatBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ProviderCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('pheat.manager')) {
            return;
        }

        $definition = $container->getDefinition('pheat.manager');

        $providers = $container->findTaggedServiceIds('pheat.provider');

        foreach ($providers as $id => $tags) {
            $definition->addMethodCall(
                'addProvider',
                [new Reference($id)]
            );
        }
    }
}
