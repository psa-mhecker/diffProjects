<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

/**
 * Class Pt21NavigationStrategy.
 */
class Pt21NavigationStrategy extends NavigationStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pt21.html.smarty';
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
