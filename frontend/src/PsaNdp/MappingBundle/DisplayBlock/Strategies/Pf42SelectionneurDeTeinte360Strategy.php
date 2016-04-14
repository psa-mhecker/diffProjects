<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pf42SelectionneurDeTeinte360Strategy.
 */
class Pf42SelectionneurDeTeinte360Strategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pf42.html.smarty';
    }
}
