<?php

namespace Vend\PheatBundle\Pheat\Provider;

use InvalidArgumentException;
use Pheat\ContextInterface;
use Pheat\Feature\FeatureInterface;
use Pheat\Provider\ContextProviderInterface;
use Pheat\Provider\Provider;
use Pheat\Provider\WritableProviderInterface;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Attribute\NamespacedAttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionProvider extends Provider implements WritableProviderInterface, ContextProviderInterface
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

        try {
            $bag = $this->session->getBag('pheat');
        } catch (InvalidArgumentException $e) {
            $bag = new NamespacedAttributeBag('_pheat_feature');
            $bag->setName('pheat');

            $this->session->registerBag($bag);
        }

        $this->bag = $bag;
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
        $all = $this->bag->all();

        return $all;
    }

    /**
     * Inject values into the context
     *
     * The provider is expected to use the ->set() interface on the context object to
     * provide information.
     *
     * @param ContextInterface $context
     * @return mixed
     */
    public function inject(ContextInterface $context)
    {
        $context->set('session_id', sha1($this->session->getId() . __CLASS__));
    }
}
