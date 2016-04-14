<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pc63LienBoussoleStrategy.
 */
class Pc63LienBoussoleStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pc63.html.smarty';
    }
}
