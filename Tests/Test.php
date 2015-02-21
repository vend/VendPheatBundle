<?php

namespace Vend\PheatBundle\Tests;

use PHPUnit_Framework_TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\ClosureLoader;
use Symfony\Component\DependencyInjection\Loader\IniFileLoader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Vend\PheatBundle\DependencyInjection\VendPheatExtension;

abstract class Test extends PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    protected $container;

    /**
     * Creates a configuration container
     */
    protected function setUp()
    {
        parent::setUp();

        $this->container = new ContainerBuilder();
        $this->container->registerExtension(new VendPheatExtension());
        $this->container->addDefinitions(['session' => new Definition('\stdClass')]);
    }

    protected function tearDown()
    {
        $this->container = null;
    }

    /**
     * __DIR__ resolves at compile time; relative to where it occurs in source file
     *
     * @return array<string>
     */
    protected function getFixtureDirs()
    {
        return [__DIR__ . '/Fixtures/'];
    }

    /**
     * Gets a configuration loader
     *
     * @return DelegatingLoader
     */
    protected function getConfigurationLoader()
    {
        $locator = new FileLocator($this->getFixtureDirs());

        $resolver = new LoaderResolver([
            new XmlFileLoader($this->container, $locator),
            new YamlFileLoader($this->container, $locator),
            new IniFileLoader($this->container, $locator),
            new PhpFileLoader($this->container, $locator),
            new ClosureLoader($this->container),
        ]);

        return new DelegatingLoader($resolver);
    }

    /**
     * Loads a configuration file from the fixture dir
     *
     * @param string $resource
     * @throws \Symfony\Component\Config\Exception\FileLoaderLoadException
     */
    protected function loadConfiguration($resource)
    {
        $loader = $this->getConfigurationLoader($this->container);
        $loader->load($resource);
    }
}
