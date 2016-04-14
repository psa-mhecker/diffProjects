<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class PC85ReevoStrategy.
 */
class PC85ReevoStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:PC85.html.smarty';
    }
}
