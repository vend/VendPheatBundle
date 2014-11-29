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
        $this->data['features'] = $this->manager->getFeatureSet();
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
        return array_reduce($this->data['features']->getAll(), function ($carry, $item) {
            /**
             * @var $item FeatureInterface
             */
            return $carry + ($item->getStatus() ? 1 : 0);
        }, 0);
    }

    public function getTotalCount()
    {
        return count($this->data['features']);
    }


}
