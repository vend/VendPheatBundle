<?php

namespace Vend\PheatBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $root = $builder->root('pheat');

        $root
            ->children()
                ->append($this->addFeaturesNode())
                ->append($this->addManagerNode())
                ->append($this->addContextNode())
                ->append($this->addProvidersNode())
            ->end()
        ;

        return $builder;
    }

    /**
     * @return ArrayNodeDefinition
     */
    protected function addContextNode()
    {
        $builder = new TreeBuilder();

        $node = $builder->root('context');
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('class')
                    ->defaultValue('Vend\\PheatBundle\\Pheat\\Context')
                ->end()
                ->scalarNode('service')
                    ->defaultValue('pheat.context.default')
                ->end()
            ->end();

        return $node;
    }

    /**
     * @return ArrayNodeDefinition
     */
    protected function addManagerNode()
    {
        $builder = new TreeBuilder();

        $node = $builder->root('manager');
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('class')
                    ->defaultValue('Pheat\\Manager')
                ->end()
            ->end();

        return $node;
    }

    /**
     * @return ArrayNodeDefinition
     */
    protected function addProvidersNode()
    {
        $builder = new TreeBuilder();

        $node = $builder->root('providers');

        $node
            ->defaultValue(['session' => true, 'config' => true])
            ->treatNullLike(['session' => true, 'config' => true])
            ->treatTrueLike(['session' => true, 'config' => true])
            ->treatFalseLike(['session' => false, 'config' => false])
            ->prototype('scalar')
            ->end();

        return $node;
    }

    /**
     * @return ArrayNodeDefinition
     */
    protected function addFeaturesNode()
    {
        $builder = new TreeBuilder();

        $node = $builder->root('features');

        $node
            ->treatTrueLike(['enabled' => true])
            ->treatFalseLike(['enabled' => false])
            ->treatNullLike(['enabled' => null])
            ->prototype('array')
                ->beforeNormalization()
                    ->ifTrue(function ($v) { return is_double($v); })
                    ->then(function ($v) { return ['enabled' => true, 'ratio' => $v]; })
                ->end()
                ->beforeNormalization()
                    ->ifTrue(function ($v) { return is_bool($v); })
                    ->then(function ($v) { return ['enabled' => $v]; })
                ->end()
                ->beforeNormalization()
                    ->ifTrue(function ($v) { return is_string($v) && preg_match('/[0-9]*%/', $v); })
                    ->then(function ($v) { return ['enabled' => true, 'ratio' => $v / 100]; })
                ->end()
                ->children()
                    ->booleanNode('enabled')->end()
                    ->floatNode('ratio')->end()
                    ->scalarNode('vary')->end()
                    ->arrayNode('variants')
                        ->prototype('scalar')
                        ->beforeNormalization()
                            ->ifTrue(function ($v) { return is_string($v) && preg_match('/[0-9]*%/', $v); })
                            ->then(function ($v) { return $v / 100; })
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $node;
    }
}
