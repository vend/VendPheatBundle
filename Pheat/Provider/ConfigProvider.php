<?php

namespace Vend\PheatBundle\Pheat\Provider;

use Pheat\ContextInterface;
use Pheat\Feature\Feature;
use Pheat\Feature\FeatureInterface;
use Pheat\Provider\Provider;
use Pheat\Provider\ProviderInterface;

class ConfigProvider extends Provider
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
     * Gets the stored configuration for all the features for this provider
     *
     * @return array
     */
    protected function getConfiguration()
    {
        return $this->configuration;
    }
}
