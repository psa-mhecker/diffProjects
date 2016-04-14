<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class PC95InterestedByStrategy.
 */
class PC95InterestedByStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pc95.html.smarty';
    }
}
