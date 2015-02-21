<?php

namespace Vend\PheatBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FeatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            #->add('type')
            ->add('status', 'choice', [
                'choices'  => [
                    1 => 'active',
                    0 => 'inactive'
                ],
                'placeholder' => '-',
                'multiple'   => false,
                'expanded'   => true,
                'required'   => false,
                'empty_data' => null
            ])
            #->add('ratio')
            #->add('variants')
            ->add('save', 'submit', ['label' => 'Set']);
    }

    public function getName()
    {
        return 'feature';
    }
}
