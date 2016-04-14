<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use Symfony\Component\HttpFoundation\Request;
use PSA\MigrationBundle\Repository\PsaPageZoneMultiRepository;

/**
 * Data source for Pn2Onglet block
 */
class Pn2OngletDataSource extends AbstractDataSource
{
    /**
     * @param PsaPageZoneMultiRepository $psaNdpPageZoneMultiRepository
     */
    public function __construct(PsaPageZoneMultiRepository $psaNdpPageZoneMultiRepository)
    {
        $this->psaNdpPageZoneMultiRepository = $psaNdpPageZoneMultiRepository;
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
        /* @var $block PsaPageZoneConfigurableInterface */
        $data['multi'] = $block->getMultis();

        return $data;
    }
}
