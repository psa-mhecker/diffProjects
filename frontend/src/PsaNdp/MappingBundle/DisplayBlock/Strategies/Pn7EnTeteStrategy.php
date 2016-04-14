<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

/**
 * Class Pn7EnTeteStrategy.
 */
class Pn7EnTeteStrategy extends NavigationStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pn7.html.smarty';
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
