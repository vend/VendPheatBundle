<?php

namespace Vend\PheatBundle\Tests\DependencyInjection;

use Pheat\Provider\ProviderInterface;
use Vend\PheatBundle\Tests\Test;

class VendBundleExtensionTest extends Test
{
    public function testPhpSimpleConfig()
    {
        $this->loadConfiguration('simple.php');
        $this->container->compile();

        $manager = $this->container->get('pheat.manager');
        $this->assertInstanceOf(ProviderInterface::class, $manager->getProvider('session'));
    }

    public function testYmlSimpleConfig()
    {
        $this->loadConfiguration('simple.yml');
        $this->container->compile();

        $manager = $this->container->get('pheat.manager');
        $this->assertNull($manager->getProvider('session'));
    }

    public function testYmlFullConfig()
    {
        $this->loadConfiguration('full.yml');
        $this->container->compile();
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidTypeException
     */
    public function testPhpInvalidInt()
    {
        $this->loadConfiguration('invalid-int.php');
        $this->container->compile();
    }
}
