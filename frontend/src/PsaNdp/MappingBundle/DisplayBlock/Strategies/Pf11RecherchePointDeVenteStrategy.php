<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pf11RecherchePointDeVenteStrategy.
 */
class Pf11RecherchePointDeVenteStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pf11.html.smarty';
    }
}
