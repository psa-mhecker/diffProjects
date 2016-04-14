<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\HttpFoundation\Request;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMulti;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

/**
 * Data source for Pc77DimensionVehicule block
 */
class Pc77DimensionVehiculeDataSource extends AbstractDataSource
{
    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input BlockInterface and current url Request
     *
     * @param  ReadBlockInterface $block
     * @param  Request            $request  Current url request displaying th block
     * @param  bool               $isMobile Indicate if is a mobile display
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        /** @var PsaPageZoneConfigurableInterface $block */
        $data = [];
        $data['block'] = $block;
        $title = $block->getZoneTitre();
        if(!empty($title)) {
            $data['title'] = $title;
        }
        $subtitle = $block->getZoneTitre2();
        if(!empty($subtitle)) {
            $data['subtitle'] = $subtitle;
        }
        $legalNotice = $block->getZoneTexte();
        if(!empty($legalNotice)) {
            $data['legalNotice'] = $legalNotice;
        }

        return $data;
    }
}
