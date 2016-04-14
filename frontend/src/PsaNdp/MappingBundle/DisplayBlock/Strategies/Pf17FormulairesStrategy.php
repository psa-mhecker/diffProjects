<?php

namespace PsaNdp\MappingBundle\DisplayBlock\Strategies;

use PSA\MigrationBundle\Entity\Content\PsaContent;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;

/**
 * Class Pf17FormulairesStrategy.
 */
class Pf17FormulairesStrategy extends AbstractPsaStrategy
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return  'PsaNdpMappingBundle:Desktop:pf17.html.smarty';
    }

    /**
     * Checks whether the block configuration enable it to be displayed or not.
     *
     * @param ReadBlockInterface $block
     *
     * @return bool
     */
    protected function isDisplayable(ReadBlockInterface $block)
    {
        $displayable = true;
        if (($this->isMobile && !$block->getZoneMobile()) || (!$this->isMobile && !$block->getZoneWeb())) {
            $displayable = false;
        }
        /** @var PsaContent $content */
        $content = $block->getContent();
        //$content->getLangue();
        if ($content) {
            $currentVersion = $content->getCurrentVersion();
            $displayable = (!$this->isMobile && $currentVersion->getContentCode() !== null) ||
                ($this->isMobile && $currentVersion->getContentTitle13() !== null);
        }

        return $displayable;
    }
}
