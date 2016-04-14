<?php

namespace PsaNdp\MappingBundle\Sources;

use Doctrine\Common\Collections\ArrayCollection;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\HttpFoundation\Request;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMulti;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

/**
 * Data source for Pc39SlideshowOffre block
 */
class Pc39SlideshowOffreDataSource extends AbstractDataSource
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

        if (!$isMobile) {
            $data['slides'] = new ArrayCollection();
            $visuelType = $block->getZoneTool();
            $data['slides3'] = false;

            if ($visuelType === PsaPageZoneMulti::PAGE_ZONE_MULTI_TYPE_SLIDESHOW_CINEMASCOPE_WEB) {
                $data['slides3'] = false;
                $data['slides'] = $block->getMultisByType(PsaPageZoneMulti::PAGE_ZONE_MULTI_TYPE_SLIDESHOW_CINEMASCOPE_WEB);
            }
            if ($visuelType === PsaPageZoneMulti::PAGE_ZONE_MULTI_TYPE_SLIDESHOW_VISUELS_3_WEB) {
                $data['slides3'] = true;
                $data['slides'] = $block->getMultisByType(PsaPageZoneMulti::PAGE_ZONE_MULTI_TYPE_SLIDESHOW_VISUELS_3_WEB);
            }
        }

        if ($isMobile) {
            $data['slides'] = $block->getMultisByType(PsaPageZoneMulti::PAGE_ZONE_MULTI_TYPE_SLIDESHOW_CINEMASCOPE_MOBILE);
        }

        return $data;
    }
}
