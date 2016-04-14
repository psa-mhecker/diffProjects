<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

/**
 * Class Pn14NavigationConfishowStrategy.
 */
class Pn14NavigationConfishowStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pn14.html.smarty';
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
            'navigation' => $this->request->attributes->get('siteId').'-'.$block->getLangue()->getLangueCode(),
        ];
    }
}
