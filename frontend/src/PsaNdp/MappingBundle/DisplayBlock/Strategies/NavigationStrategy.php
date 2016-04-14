<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

/**
 * Class NavigationStrategy.
 */
abstract class NavigationStrategy extends AbstractPsaStrategy
{
    /**
     * {@inheritdoc}
     *
     * @param PsaPageZoneConfigurableInterface $block
     *
     * @return array
     */
    protected function getAdditionalCachedTags(PsaPageZoneConfigurableInterface $block)
    {
        //default implementation
        return ['navigation' => $this->request->attributes->get('siteId').'-'.$block->getLangue()->getLangueCode()];
    }
}
