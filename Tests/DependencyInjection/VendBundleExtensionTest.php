<?php

namespace Vend\PheatBundle\Tests\DependencyInjection;

use PHPUnit_Framework_TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;
use Vend\PheatBundle\DependencyInjection\VendPheatExtension;

class VendBundleExtensionTest extends PHPUnit_Framework_TestCase
{
    protected $fixtures = [
        'features' => [
            0 => [
                'features' => [
                    'on_only_in_dev'     => false,
                    'on_only_in_main'    => true,
                    'on_only_in_prod'    => false,
                    'on_only_in_test'    => false,
                    'ratio_feature'      => 0.1000000000000000055511151231257827021181583404541015625,
                    'ratio_feature2'     => '50%',
                    'ratio_feature_full' => [
                        'enabled' => true,
                        'vary'    => 'username',
                        'ratio'   => 0.1000000000000000055511151231257827021181583404541015625,
                    ],
                    'variant_feature'    => [
                        'enabled'  => true,
                        'vary'     => 'username',
                        'variants' => [
                            'variant_one'   => 30,
                            'variant_two'   => 30,
                            'variant_three' => 'default',
                            'variant_four'  => 10,
                        ],
                    ],
                ],
                'manager'   => [
                    'class' => 'Pheat\\Manager',
                ],
                'providers' => [
                    'session' => true,
                    'config'  => true,
                ],
            ],
        ]
    ];

    protected $invalid = [
        'features-int' => [
            0 => [
                'features' => 12466
            ]
        ]
    ];

    /**
     * @return VendPheatExtension
     */
    protected function getExtension()
    {
        return new VendPheatExtension();
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockBuilder|\Symfony\Component\DependencyInjection\ContainerBuilder
     */
    protected function getMockContainerBuilder()
    {
        $mock = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')
            ->setMethods(null)
            ->getMock();

        return $mock;
    }

    public function testFeaturesConfig()
    {
        $extension = $this->getExtension();
        $container = $this->getMockContainerBuilder();

        $extension->load($this->fixtures['features'], $container);
    }

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidTypeException
     */
    public function testFeaturesInvalidType()
    {
        $extension = $this->getExtension();
        $container = $this->getMockContainerBuilder();

        $extension->load($this->invalid['features-int'], $container);
    }
}
