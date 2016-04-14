<?php

namespace PsaNdp\MappingBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use PsaNdp\MappingBundle\CompilerPass\CompilerPass;

/**
 * Starting class
 */
class PsaNdpMappingBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new CompilerPass());
    }
}
