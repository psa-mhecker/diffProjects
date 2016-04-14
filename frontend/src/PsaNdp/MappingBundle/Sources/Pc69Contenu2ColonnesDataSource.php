<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\HttpFoundation\Request;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMulti;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

/**
 * Data source for Pc69Contenu2Colonnes block
 */
class Pc69Contenu2ColonnesDataSource extends AbstractDataSource
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
        $data['column14']['content'] = $block->getMultisByType(PsaPageZoneMulti::PAGE_ZONE_MULTI_TYPE_CONTENT_COLUMN_1_4)->first();
        $data['column14']['ctaList'] = $block->getCtaReferencesByType(PsaPageZoneMulti::PAGE_ZONE_MULTI_TYPE_CONTENT_COLUMN_1_4_CTA);
        $data['column34']['content'] = $block->getMultisByType(PsaPageZoneMulti::PAGE_ZONE_MULTI_TYPE_CONTENT_COLUMN_3_4)->first();
        $data['column34']['ctaList'] = $block->getCtaReferencesByType(PsaPageZoneMulti::PAGE_ZONE_MULTI_TYPE_CONTENT_COLUMN_3_4_CTA);

        return $data;
    }
}
