<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pc8Contenu2ColonnesTexteStrategy.
 */
class Pc8Contenu2ColonnesTexteStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pc8.html.smarty';
    }
}
