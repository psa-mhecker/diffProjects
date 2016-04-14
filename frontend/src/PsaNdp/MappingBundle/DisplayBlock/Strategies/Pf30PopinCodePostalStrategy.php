<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pf30PopinCodePostalStrategy.
 */
class Pf30PopinCodePostalStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pf30.html.smarty';
    }
}
