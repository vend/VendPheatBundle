<?php

namespace Vend\PheatBundle\DataCollector;

use Pheat\Feature\FeatureInterface;
use Pheat\Manager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector as BaseDataCollector;

class PheatDataCollector extends BaseDataCollector
{
    /**
     * @var Manager
     */
    private $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['context'] = $this->manager->getContext();

        $this->data['features'] = [];
        $this->data['feature_providers'] = [];

        foreach ($this->manager->getFeatureSet()->getAllCanonical() as $name => $feature) {
            $this->data['features'][$name]          = $feature->getStatus();
            $this->data['feature_providers'][$name] = $feature->getProvider()->getName();
        }
    }

    public function getName()
    {
        return 'pheat';
    }

    public function getResolvedCount()
    {
        return 0;
    }

    public function getActiveCount()
    {
        return count(array_filter($this->data['features']));
    }

    public function getTotalCount()
    {
        return count($this->data['features']);
    }
}
