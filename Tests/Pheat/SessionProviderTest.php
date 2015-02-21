<?php

namespace Vend\PheatBundle\Pheat;

use Vend\PheatBundle\Pheat\Provider\SessionProvider;
use Vend\PheatBundle\Tests\Test;

class SessionProviderTest extends Test
{
    public function testStoredFeatures()
    {
        $this->loadConfiguration('full.yml');
        $this->container->compile();

        $session = $this->getMockSession();

        $provider = new SessionProvider($session);
        $features = $provider->getFeatures($this->container->get('pheat.context'));
    }
}
