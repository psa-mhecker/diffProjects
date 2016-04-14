<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pt20MasterPageStrategy.
 */
class Pt20MasterPageStrategy extends NavigationStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pt20.html.smarty';
    }
}
