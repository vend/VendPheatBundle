<?php

namespace Vend\PheatBundle\Pheat\Provider;

use Pheat\ContextInterface;
use Pheat\Feature\FeatureInterface;
use Pheat\Provider\Provider;
use Pheat\Provider\ProviderInterface;
use Pheat\Provider\WritableProviderInterface;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionProvider extends Provider implements ProviderInterface, WritableProviderInterface
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->bag = new AttributeBag('_pheat');
        $session->registerBag($this->bag);
    }

    /**
     * @return string
     */
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
        return $this->fromConfig($this->bag->all());
    }

    /**
     * Set the feature to the enclosed status, under the given context
     *
     * @param ContextInterface $context
     * @param FeatureInterface $feature
     * @return void
     */
    public function setFeature(ContextInterface $context, FeatureInterface $feature)
    {
    }
}
