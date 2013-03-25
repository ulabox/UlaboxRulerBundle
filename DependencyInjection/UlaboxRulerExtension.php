<?php

namespace Ulabox\Bundle\RulerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Ulabox\Bundle\RulerBundle\UlaboxRulerBundle;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class UlaboxRulerExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/container'));

        $this->loadDriver($config['driver'], $config, $loader);

        $container->setParameter('ulabox_ruler.driver', $config['driver']);
        $container->setParameter('ulabox_ruler.engine', $config['engine']);

        $loader->load('services.xml');
    }

    /**
     * Load bundle driver.
     *
     * @param string        $driver
     * @param array         $config
     * @param XmlFileLoader $loader
     *
     * @throws \InvalidArgumentException
     */
    protected function loadDriver($driver, array $config, XmlFileLoader $loader)
    {
        if (!in_array($driver, UlaboxRulerBundle::getSupportedDrivers())) {
            throw new \InvalidArgumentException(sprintf('Driver "%s" is unsupported by UlaboxRulerBundle', $driver));
        }

        $loader->load(sprintf('driver/%s.xml', $driver));
    }
}
