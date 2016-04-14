<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

/**
 * Class Pn15EnteteConfishowStrategy.
 */
class Pn15EnteteConfishowStrategy extends StaticShowroomStrategy
{
    const PN15_ZONE_ID = 823;

    protected $adminBlockId = self::PN15_ZONE_ID;
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return  'PsaNdpMappingBundle:Desktop:pn15.html.smarty';
    }


    /**
     * {@inheritdoc}
     *
     * @param PsaPageZoneConfigurableInterface $block
     *
     * @return array
     */
    protected function getAdditionalCachedTags(PsaPageZoneConfigurableInterface $block)
    {
        $tags = parent::getAdditionalCachedTags($block);

        return array_merge($tags,['general' => 'general']);
    }

}
