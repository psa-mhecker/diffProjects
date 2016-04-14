<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

/**
 * Class Pc52ApvStrategy.
 */
class Pc52ApvStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pc52.html.smarty';
    }

    /**
     * @param PsaPageZoneConfigurableInterface $block
     *
     * @return array
     */
    protected function getAdditionalCachedTags(PsaPageZoneConfigurableInterface $block)
    {
        //default implementation
        return [
            'filter_after_sale_services' => 'filter_after_sale_services',
            'after_sale_services' => 'after_sale_services',
        ];
    }
}
