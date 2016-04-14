<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

class Pf25FilterCarSelectorStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        $templateName = 'PsaNdpMappingBundle:Desktop:pf25.html.smarty';

        return $templateName;
    }
}
