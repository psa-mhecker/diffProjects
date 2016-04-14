<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pc9Contenu1Article1VisuelStrategy.
 */
class Pc9Contenu1Article1VisuelStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pc9.html.smarty';
    }

    /**
     *
     */
    protected function initStrategy()
    {
        $this->isPopin = true;
    }
}
