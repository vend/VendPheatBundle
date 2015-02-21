<?php

namespace Vend\PheatBundle\Form\Type;

use Pheat\Feature\Feature;
use Pheat\Manager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Vend\PheatBundle\Entity\FormFeature;
use Vend\PheatBundle\Form\DataTransformer\FormFeatureTransformer;

class FeatureType extends AbstractType
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var string
     */
    protected $feature;

    /**
     * @var string
     */
    protected $provider;

    /**
     * @param string $provider
     * @param string $feature
     * @param Manager $manager
     */
    public function __construct($provider, $feature, Manager $manager)
    {
        $this->provider = $provider;
        $this->feature  = $feature;
        $this->manager  = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'hidden')
            ->add('provider', 'hidden')
            ->add('status', 'choice', [
                'choices'  => [
                    true => 'active',
                    false => 'inactive'
                ],
                'placeholder' => '-',
                'multiple'   => false,
                'required'   => false,
                'empty_data' => null
            ])
            ->add('ratio', 'percent', [
                'required' => false
            ])
            ->add('vary', 'text', [
                'required' => false
            ])
            ->add('variants', 'collection', [
                'type' => 'text',
                'options' => [
                    'required' => false
                ]
            ]);

        $builder->addViewTransformer(new FormFeatureTransformer($this->manager));
    }

    public function getName()
    {
        return 'feature_' . $this->provider . '_' . $this->feature;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => FormFeature::class,
            ]);
    }
}
