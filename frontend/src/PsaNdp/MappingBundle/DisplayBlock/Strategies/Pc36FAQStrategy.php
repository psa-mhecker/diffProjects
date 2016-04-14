<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pc36FAQStrategy.
 */
class Pc36FAQStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return  'PsaNdpMappingBundle:Desktop:pc36.html.smarty';
    }
}
