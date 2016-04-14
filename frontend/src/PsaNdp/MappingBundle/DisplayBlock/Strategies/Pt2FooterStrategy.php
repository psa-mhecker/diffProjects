<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Zone\PsaZone;

/**
 * Class Pt2FooterStrategy.
 */
class Pt2FooterStrategy extends AbstractPsaStrategy
{
    protected $adminBlockId = PsaZone::PT2_BESOINAIDE;

    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return  'PsaNdpMappingBundle:Desktop:pt2.html.smarty';
    }

    /**
     * @param ReadBlockInterface $block
     *
     * @return bool
     */
    protected function isDisplayable(ReadBlockInterface $block)
    {
        return true;
    }
}
