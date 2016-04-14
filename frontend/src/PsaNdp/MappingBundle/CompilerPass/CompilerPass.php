<?php
namespace PsaNdp\MappingBundle\CompilerPass;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;


class CompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('translator.default');
        $definition->addMethodCall('setConfigCacheFactory', [new Reference('psa_ndp_mapping.config.cache.factory')]);
    }
}

