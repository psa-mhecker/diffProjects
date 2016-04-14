<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Utils\AnchorUtils;
use Symfony\Component\HttpFoundation\Request;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMulti;

/**
 * Data source for Pn13Ancres block
 */
class Pn13AncresDataSource extends AbstractDataSource
{
    /**
     * @var AnchorUtils
     */
    protected $anchorUtils;

    /**
     * @param AnchorUtils $anchorUtils
     */
    public function __construct(AnchorUtils $anchorUtils )
    {
        $this->anchorUtils = $anchorUtils;
    }

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
        $pageZoneMultiList = $block->getMultisByType(PsaPageZoneMulti::PAGE_ZONE_MULTI_TYPE_ANCHOR);
        $data['anchors'] = [];
        $data['block'] = $block;

        foreach ($pageZoneMultiList as $pageZoneMulti) {
            /* @var $pageZoneMulti PsaPageZoneMulti */
            // Create Anchor Data
            $newAnchor = [
                'text' => $pageZoneMulti->getPageZoneMultiTitre(),
                'id' => $this->anchorUtils->formatAnchorId($pageZoneMulti->getPageZoneMultiValue())
            ];

            $data['anchors'][] = $newAnchor;
        }

        return $data;
    }

}
