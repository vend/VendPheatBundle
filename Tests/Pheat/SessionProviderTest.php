<?php

namespace Vend\PheatBundle\Pheat;

use Vend\PheatBundle\Pheat\Provider\SessionProvider;
use Vend\PheatBundle\Tests\Test;

class SessionProviderTest extends Test
{
    public function testStoredFeatures()
    {
        $this->kernel->setConfig('full.yml');
        $this->kernel->boot();

        $provider = new SessionProvider($this->kernel->getContainer()->get('session'));
        $features = $provider->getFeatures($this->kernel->getContainer()->get('pheat.context'));
    }
}
