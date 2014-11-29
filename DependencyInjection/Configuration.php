<?php

namespace Vend\PheatBundle\DependencyInjection;


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
                ->arrayNode('manager')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')
                            ->isRequired()
                            ->defaultValue('Pheat\\Manager')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('context')
                    ->children()
                        ->scalarNode('class')
                            ->isRequired()
                            ->defaultValue('Vend\\PheatBundle\\Pheat\\Context')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('providers')
                    ->prototype('scalar')
                    ->end()
                ->end()
            ->end()
        ;

        return $builder;
    }

    protected function addFeaturesNode()
    {
        $builder = new TreeBuilder();

        $node = $builder->root('features');

        $node
            ->treatTrueLike(array('enabled' => true))
            ->treatFalseLike(array('enabled' => false))
            ->treatNullLike(array('enabled' => null))
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
                    ->booleanNode('enabled')
                        ->isRequired()
                    ->end()
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
