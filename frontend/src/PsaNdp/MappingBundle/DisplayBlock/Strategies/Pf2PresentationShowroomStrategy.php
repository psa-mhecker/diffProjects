<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

/**
 * Class Pf2PresentationShowroomStrategy.
 */
class Pf2PresentationShowroomStrategy extends StaticShowroomStrategy
{
    const PF2_ZONE_ID = 830;

    protected $adminBlockId = self::PF2_ZONE_ID;
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pf2.html.smarty';
    }

    protected function overrideBlock()
    {
        $block = parent::overrideBlock();
        // case  page mère on affiche la tranche sinon on ne l'affiche pas en mobile
        if ($this->isMobile && $block->getPageId() !== $this->block->getPageId()) {
            // on masque la tranche
            $block->setZoneMobile(false);
        }

        return $block;
    }

    /**
     * @param PsaPageZoneConfigurableInterface $block
     *
     * @return array
     */
    protected function getAdditionalCachedTags(PsaPageZoneConfigurableInterface $block)
    {
        $lcdv6 = $block->getPage()->getVersion()->getGammeVehiculeLcvd6();

        return [
            'lcdv6' => $lcdv6,
        ];
    }
}
