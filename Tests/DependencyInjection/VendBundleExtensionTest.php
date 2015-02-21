<?php

namespace Vend\PheatBundle\Tests\DependencyInjection;

use Pheat\Provider\ProviderInterface;
use Vend\PheatBundle\Tests\Test;

class VendBundleExtensionTest extends Test
{
    public function testPhpSimpleConfig()
    {
        $this->kernel->setConfig('simple.php');
        $this->kernel->boot();

        $manager = $this->kernel->getContainer()->get('pheat.manager');
        $this->assertInstanceOf(ProviderInterface::class, $manager->getProvider('session'));
    }

    public function testYmlSimpleConfig()
    {
        $this->kernel->setConfig('simple.yml');
        $this->kernel->boot();

        $manager = $this->kernel->getContainer()->get('pheat.manager');

        $session = $manager->getProvider('session');
        $this->assertNull($session);
    }

    public function testYmlFullConfig()
    {
        $this->kernel->setConfig('full.yml');
        $this->kernel->boot();

        $container = $this->kernel->getContainer();
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidTypeException
     */
    public function testPhpInvalidInt()
    {
        $this->kernel->setConfig('invalid-int.php');
        $this->kernel->boot();

        $container = $this->kernel->getContainer();
    }
}
