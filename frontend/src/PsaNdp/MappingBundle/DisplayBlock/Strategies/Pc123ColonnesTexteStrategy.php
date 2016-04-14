<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pc123ColonnesTexteStrategy.
 */
class Pc123ColonnesTexteStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pc12.html.smarty';
    }

    protected function initStrategy()
    {
        $this->isPopin = true;
    }
}
