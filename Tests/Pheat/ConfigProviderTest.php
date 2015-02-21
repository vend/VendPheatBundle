<?php

namespace Vend\PheatBundle\Tests\Pheat;

use Pheat\Manager;
use Vend\PheatBundle\Tests\Test;

class ConfigProviderTest extends Test
{
    public function testStoredFeatures()
    {
        $this->kernel->setConfig('full.yml');
        $this->kernel->boot();

        /**
         * @var Manager $manager
         */
        $manager = $this->kernel->getContainer()->get('pheat.manager');

        $manager->getFeatureSet();

    }
}
