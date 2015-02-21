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
     * @var AttributeBag
     */
    protected $bag;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;

        $this->bag = new AttributeBag('_pheat');
        $this->session->registerBag($this->bag);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'session';
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
        $this->bag->set($feature->getName(), $feature->getConfiguration());
        $this->session->save();
    }

    /**
     * Gets the stored configuration for all the features for this provider
     *
     * @return array
     */
    protected function getConfiguration()
    {
        return $this->bag->all();
    }
}
