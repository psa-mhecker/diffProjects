<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pt15PlanDuSiteStrategy.
 */
class Pt15PlanDuSiteStrategy extends NavigationStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pt15.html.smarty';
    }
}
