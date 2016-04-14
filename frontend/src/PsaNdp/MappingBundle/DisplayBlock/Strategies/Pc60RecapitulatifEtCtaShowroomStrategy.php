<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PsaNdp\MappingBundle\Manager\BlockManager;

/**
 * Class Pc60RecapitulatifEtCtaShowroomStrategy.
 */
class Pc60RecapitulatifEtCtaShowroomStrategy extends AbstractPsaStrategy
{
    const PC60_ZONE_ID = 818;
    protected $childBlock;

    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'PsaNdpMappingBundle:Desktop:pc60.html.smarty';
    }

    /**
     * @return ReadBlockInterface
     */
    protected function overrideBlock()
    {
        /** @var BlockManager $blockManager */
        $blockManager = $this->manager->getBlockManager();
        $this->childBlock = $this->block;
        $block = $blockManager->getShowroomWelcomePageStaticBlock($this->block, self::PC60_ZONE_ID);

        if ($block === null) { // erreur on trouve pas de bloc sur la page  courante ou au dessus ( erreur config )
            // on fait comme si page mère et on cache le bloc pour évité des erreurs
            $block = $this->block;
        }

        // cas  page mere
        if ($block->getPageId() === $this->block->getPageId()) {
            // on masque la tranche
            $block->setZoneWeb(false);
            $block->setZoneMobile(false);
        }
        // sinon on utilise la config de la  page mere a la place de la fille,
        // rien a faire niveau du cache celui ci sera supprimer quand on publiera la page mère vu que l'id est celui de la page mere

        return $block;
    }

    /**
     * @return bool
     */
    protected function hasAdminBlock()
    {
        return true;
    }

    /**
     * @param ReadBlockInterface $block
     *
     * @return bool
     */
    protected function isDisplayable(ReadBlockInterface $block)
    {
        $displayable = true;
        if (($this->isMobile && !$this->childBlock->getZoneMobile()) || (!$this->isMobile && !$this->childBlock->getZoneWeb())) {
            $displayable = false;
        }

        return $displayable;
    }
}
