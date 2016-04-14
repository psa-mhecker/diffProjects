<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pc16VerbatimStrategy.
 */
class Pc16VerbatimStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        $templateName = 'PsaNdpMappingBundle:Desktop:pc16.html.smarty';

        return $templateName;
    }
}
