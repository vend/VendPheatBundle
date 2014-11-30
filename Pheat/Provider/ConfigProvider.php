<?php

namespace Vend\PheatBundle\Pheat\Provider;

use Pheat\ContextInterface;
use Pheat\Feature\Feature;
use Pheat\Feature\FeatureInterface;
use Pheat\Provider\ProviderInterface;

class ConfigProvider implements ProviderInterface
{
    protected $configuration;

    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getName()
    {
        return 'config';
    }

    /**
     * @param ContextInterface $context
     * @return FeatureInterface[]
     */
    public function getFeatures(ContextInterface $context)
    {
        $features = [];

        foreach ($this->configuration as $name => $feature) {
            if (!empty($feature['variants'])) {

            } elseif (!empty($feature['ratio'])) {

            } else {
                $features[] = new Feature($name, $feature['enabled'], $this);
            }
        }

        return $features;
    }
}
