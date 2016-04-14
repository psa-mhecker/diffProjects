<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pt22MyPeugeotStrategy.
 */
class Pt22MyPeugeotStrategy extends AbstractPsaStrategy
{
    const PT22_MY_PEUGEOT = '832';

    protected $adminBlockId = self::PT22_MY_PEUGEOT;
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pt22.html.smarty';
    }
}
