<?php

namespace PheatBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Vend\PheatBundle\Tests\Test;

class ManagementControllerTest extends Test
{
    /**
     * @param array $server Server parameters
     * @return Client
     */
    protected function getClient(array $server = [])
    {
        $client = $this->kernel->getContainer()->get('test.client');
        $client->setServerParameters($server);

        return $client;
    }

    public function testIndex()
    {
        $this->kernel->boot();

        $client = $this->getClient();

        $crawler = $client->request('GET', '/features/');

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Feature Name")')->count()
        );
    }
}
