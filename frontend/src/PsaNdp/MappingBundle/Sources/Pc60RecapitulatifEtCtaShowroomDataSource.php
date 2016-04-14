<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PsaNdp\MappingBundle\Services\ShareServices\ShareObjectService;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Pc60RecapitulatifEtCtaShowroomDataSource
 */
class Pc60RecapitulatifEtCtaShowroomDataSource extends AbstractDataSource
{
    /**
     * La tranche s'affichera en FO sur toutes les pages d'un showroom sauf sur la Welcome page (page mère)
     *
     * @param ReadBlockInterface $block
     * @param Request $request
     * @param bool $isMobile
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        $data['block'] = $block;
        $data['links'] = $block->getCtaReferencesByType('CTA_LIENS');
        $data['cta'] = $block->getCtaReferencesByType('CTA_DESKTOP');

        return $data;
    }
}
