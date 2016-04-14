<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pc51ColonneStrategy.
 */
class Pc51ColonneStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pc5.html.smarty';
    }

    /**
     *
     */
    protected function initStrategy()
    {
        $this->isPopin = true;
    }
}
