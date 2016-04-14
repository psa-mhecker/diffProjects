<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

/**
 * Class Pt3JeVeuxStrategy.
 */
class Pt3JeVeuxStrategy extends AbstractPsaStrategy
{
    const PT3_ADMIN_BLOCK = 833;

    protected $adminBlockId = self::PT3_ADMIN_BLOCK;

    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pt3.html.smarty';
    }
}
