<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pt17ChoixDeLaLangueStrategy.
 */
class Pt17ChoixDeLaLangueStrategy extends NavigationStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return  'PsaNdpMappingBundle:Desktop:pt17.html.smarty';
    }
}
