<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class PT23ModuleQualificationStrategy.
 */
class PT23ModuleQualificationStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:PT23.html.smarty';
    }
}
