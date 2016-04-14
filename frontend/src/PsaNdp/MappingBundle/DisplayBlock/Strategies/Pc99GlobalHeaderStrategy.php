<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pc99GlobalHeaderStrategy.
 */
class Pc99GlobalHeaderStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pc99.html.smarty';
    }
}
