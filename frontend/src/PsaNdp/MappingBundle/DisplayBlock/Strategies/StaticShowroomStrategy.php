<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

/**
 * Abstract class managing the block override for block in the static area of showroom
 * Class StaticShowroomStrategy.
 */
abstract class StaticShowroomStrategy extends AbstractPsaStrategy
{
    /**
     * @return \OpenOrchestra\ModelInterface\Model\ReadBlockInterface
     */
    protected function overrideBlock()
    {

        /** @var BlockManager $blockManager */
        $blockManager = $this->manager->getBlockManager();
        $block = $blockManager->getShowroomWelcomePageStaticBlock($this->block, $this->getAdminBlockId());
        if ($block === null) { // erreur on trouve pas de bloc sur la page  courante ou au dessus ( erreur config )
            // on fait comme si page mère et on cache le bloc pour évité des erreurs
            $block = $this->block;
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
            'navigation' => $this->request->attributes->get('siteId').'-'.$block->getLangue()->getLangueCode(),
        ];
    }
}
