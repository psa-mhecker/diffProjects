<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pf8WebstoreVehiculeNeufStrategy.
 */
class Pf8WebstoreVehiculeNeufStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return  'PsaNdpMappingBundle:Desktop:pf8.html.smarty';
    }
}
