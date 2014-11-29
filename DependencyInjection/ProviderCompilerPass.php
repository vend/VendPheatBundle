<?php

namespace Vend\PheatBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ProviderCompilerPass implements CompilerPassInterface
{
    const DEFAULT_PRIORITY = 10;

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('pheat.manager')) {
            return;
        }

        $definition = $container->getDefinition('pheat.manager');

        $providers = [];

        foreach ($container->findTaggedServiceIds('pheat.provider') as $id => $tags) {
            foreach ($tags as $attributes) {
                $providers[$id] = isset($attributes['priority']) ? $attributes['priority'] : self::DEFAULT_PRIORITY;
            }
        }

        asort($providers, SORT_DESC | SORT_NUMERIC);

        foreach ($providers as $id => $priority) {
            $definition->addMethodCall(
                'addProvider',
                [new Reference($id)]
            );
        }
    }
}
