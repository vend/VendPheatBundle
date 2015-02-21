<?php

namespace Vend\PheatBundle\Tests;

use Exception;
use Symfony\Component\HttpFoundation\Session\Session;

abstract class Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AppKernel
     */
    protected $kernel;

    /**
     * Creates a configuration container
     */
    protected function setUp()
    {
        parent::setUp();

        $this->kernel = new AppKernel('test', true);
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->kernel->shutdown();
        $this->kernel = null;
    }

    /**
     * @return string
     */
    protected function getDefaultConfiguration()
    {
        return 'basics.yml';
    }
}
