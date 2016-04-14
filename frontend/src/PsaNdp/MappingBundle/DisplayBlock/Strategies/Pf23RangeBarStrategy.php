<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pf23RangeBarStrategy.
 */
class Pf23RangeBarStrategy extends NavigationStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return  'PsaNdpMappingBundle:Desktop:pf23.html.smarty';
    }
}
