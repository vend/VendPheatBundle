<?php

namespace Vend\PheatBundle\Tests;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Vend\PheatBundle\VendPheatBundle;

/**
 * Test kernel
 */
class AppKernel extends Kernel
{
    /**
     * @var string path
     */
    protected $config = null;

    /**
     * @param string $config Relative path
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * Returns an array of bundles to register.
     *
     * @return \Symfony\Component\HttpKernel\Bundle\BundleInterface[] An array of bundle instances.
     *
     * @api
     */
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),
            new VendPheatBundle()
        ];
    }

    public function getContainerClass()
    {
        return parent::getContainerClass() . md5($this->config);
    }

    public function getCacheDir()
    {
        return __DIR__ . '/../build/cache';
    }

    public function getLogDir()
    {
        return __DIR__ . '/../../build/logs';
    }

    /**
     * Loads the container configuration.
     *
     * @param LoaderInterface $loader A LoaderInterface instance
     * @api
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/Resources/config/default.yml');

        if ($this->config) {
            $loader->load(__DIR__ . '/Resources/config/' . $this->config);
        }
    }
}
