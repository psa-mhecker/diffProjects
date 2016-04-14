<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pc68Contenu1Article2Ou3VisuelsStrategy.
 */
class Pc68Contenu1Article2Ou3VisuelsStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pc68.html.smarty';
    }

    /**
     *
     */
    protected function initStrategy()
    {
        $this->isPopin = true;
    }
}
