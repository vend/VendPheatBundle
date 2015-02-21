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

        $session = $this->getMockSession();

        $provider = new SessionProvider($session);
        $features = $provider->getFeatures($this->kernel->getContainer()->get('pheat.context'));
    }
}
