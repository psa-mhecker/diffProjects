<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pc47PointDeVenteUniqueStrategy.
 */
class Pc47PointDeVenteUniqueStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pc47.html.smarty';
    }
}
