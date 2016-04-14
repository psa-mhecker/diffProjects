<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pc72ColonnesStrategy.
 */
class Pc72ColonnesStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pc7.html.smarty';
    }

    /**
     *
     */
    protected function initStrategy()
    {
        $this->isPopin = true;
    }
}
