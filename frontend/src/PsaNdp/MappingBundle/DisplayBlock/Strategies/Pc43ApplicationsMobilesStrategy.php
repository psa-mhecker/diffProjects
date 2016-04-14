<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pc43ApplicationsMobilesStrategy.
 */
class Pc43ApplicationsMobilesStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pc43.html.smarty';
    }
}
