<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pf16AutresReseauxSociauxStrategy.
 */
class Pf16AutresReseauxSociauxStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return  'PsaNdpMappingBundle:Desktop:pf16.html.smarty';
    }
}
