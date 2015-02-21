<?php

namespace Vend\PheatBundle\Form\DataTransformer;

use Pheat\Feature\Factory;
use Pheat\Feature\FeatureInterface;
use Pheat\Manager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Vend\PheatBundle\Entity\FormFeature;

class FormFeatureTransformer implements DataTransformerInterface
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @param Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param FeatureInterface $value
     * @return FormFeature
     */
    public function transform($value)
    {
        if (!$value instanceof FeatureInterface) {
            throw new TransformationFailedException('Expected FeatureInterface');
        }

        $view = new FormFeature();
        $view->provider = $value->getProvider()->getName();
        $view->name = $value->getName();
        $view->status = $value->getStatus();

        $configuration = $value->getConfiguration();

        if (!empty($configuration['ratio']) && is_scalar($configuration['ratio'])) {
            $view->ratio = $configuration['ratio'];
        }

        return $view;
    }

    /**
     * @param FormFeature $value
     * @return \Pheat\Feature\FeatureInterface
     */
    public function reverseTransform($value)
    {
        if (!$value instanceof FormFeature) {
            throw new TransformationFailedException('Invalid form feature');
        }

        $provider = $this->manager->getProvider($value->provider);

        if (!$provider) {
            throw new TransformationFailedException('Invalid provider name');
        }

        $factory = new Factory();

        $feature = $factory->singleFragment($value->name, [
            'enabled' => $value->status,
            'ratio'   => $value->ratio,
            'variants' => $value->variants
        ], $provider);

        if (!$feature) {
            throw new TransformationFailedException('Invalid configuration fragment');
        }

        return $feature;
    }
}
