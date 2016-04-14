<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pf27CarPickerStrategy
 * @package PsaNdp\MappingBundle\DisplayBlock\Strategies
 */
class Pf27CarPickerStrategy extends AbstractPsaStrategy
{
    /**
     *
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pf27.html.smarty';
    }
}
