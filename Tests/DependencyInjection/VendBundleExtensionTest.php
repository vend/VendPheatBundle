<?php

namespace Vend\PheatBundle\Tests\DependencyInjection;

use Vend\PheatBundle\Tests\Test;

class VendBundleExtensionTest extends Test
{
    public function testPhpSimpleConfig()
    {
        $this->loadConfiguration('simple.php');
        $this->container->compile();
    }

    public function testYmlSimpleConfig()
    {
        $this->loadConfiguration('simple.yml');
        $this->container->compile();
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
