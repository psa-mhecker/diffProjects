<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pc73MegaBanniereDynamiqueStrategy.
 */
class Pc73MegaBanniereDynamiqueStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pc73.html.smarty';
    }
}
