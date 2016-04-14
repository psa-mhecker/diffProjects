<?php

namespace PsaNdp\MappingBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PsaNdpMappingExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('services_data_source.yml');
        $loader->load('services_data_transformer.yml');
        $loader->load('services_display.yml');
        $loader->load('service_translation.yml');
        $loader->load('socialnetwork.yml');
        $loader->load('object.yml');
        $loader->load('manager.yml');
        $loader->load('utils.yml');
        $loader->load('datalayer.yml');
        $loader->load('listener.yml');
        $loader->load('repository.yml');

        $container->setAlias('psa.templating', 'templating.engine.twig');

    }
}
