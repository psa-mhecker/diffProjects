<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pc78UspMosaiqueStrategy.
 */
class Pc78UspMosaiqueStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return  'PsaNdpMappingBundle:Desktop:pc78.html.smarty';
    }
}
