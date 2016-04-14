<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;

/**
 * Class Pc77DimensionVehiculeStrategy.
 */
class Pc77DimensionVehiculeStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pc77.html.smarty';
    }

    /**
     * Checks whether the block configuration enable it to be displayed or not.
     *
     * @param ReadBlockInterface $block
     *
     * @return bool
     */
    protected function isDisplayable(ReadBlockInterface $block)
    {
        return $block->getZoneMobile() || $block->getZoneWeb();
    }
}
