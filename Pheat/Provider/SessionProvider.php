<?php

namespace Vend\PheatBundle\Pheat\Provider;

use Pheat\ContextInterface;
use Pheat\Feature\FeatureInterface;
use Pheat\Provider\ProviderInterface;

class SessionProvider implements ProviderInterface
{
    public function getName()
    {
        return 'session';
    }

    /**
     * @param ContextInterface $context
     * @return FeatureInterface[]
     */
    public function getFeatures(ContextInterface $context)
    {
        return [];
    }
}
