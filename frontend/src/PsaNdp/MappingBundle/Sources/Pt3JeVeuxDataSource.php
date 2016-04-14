<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

use Symfony\Component\HttpFoundation\Request;
use PSA\MigrationBundle\Entity\Zone\PsaZone;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMulti;
use PsaNdp\MappingBundle\Manager\BlockManager;

/**
 * Data source for Pt3JeVeux block
 */
class Pt3JeVeuxDataSource extends AbstractDataSource
{
    /**
     * @var BlockManager
     */
    private $blockManager;


    /**
     * @param BlockManager         $blockManager
     */
    public function __construct(BlockManager $blockManager) {
        $this->blockManager = $blockManager;
    }


    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input
     * BlockInterface and current url Request
     *
     * @param BlockInterface $block
     * @param Request        $request  Current url request displaying th block
     * @param bool           $isMobile Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        /* @var $block PsaPageZoneConfigurableInterface */

        $data['block'] = $block;
        $data['cols']['col1'] = $block->getMultisByType(PsaPageZoneMulti::PAGE_ZONE_MULTI_TYPE_IWANT_COL1);
        $data['cols']['col2'] = $block->getMultisByType(PsaPageZoneMulti::PAGE_ZONE_MULTI_TYPE_IWANT_COL2);
        $data['cols']['col3'] = $block->getMultisByType(PsaPageZoneMulti::PAGE_ZONE_MULTI_TYPE_IWANT_COL3);
        $data['col4']['media'] = $block->getMultisByType(PsaPageZoneMulti::PAGE_ZONE_MULTI_TYPE_IWANT_COL4);
        $data['col4']['quickAccess'] = $this->blockManager->findOneByPageIdAndZoneId(
            $block->getLangueId(),
            $block->getPage()->getParentId(),
            PsaZone::PT20_ADMIN
        );

        return $data;
    }
}
