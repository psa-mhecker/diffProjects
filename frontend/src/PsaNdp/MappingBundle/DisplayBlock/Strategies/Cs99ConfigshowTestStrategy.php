<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Cs99ConfigshowTestStrategy.
 */
class Cs99ConfigshowTestStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:cs99.html.smarty';
    }
}
