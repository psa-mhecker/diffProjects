<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Services\ShareServices\ShareObjectService;
use Symfony\Component\HttpFoundation\Request;

/**
 * Data source for Pc19Slideshow block.
 */
class Pc19SlideshowDataSource extends AbstractDataSource
{
    /**
     * @var ShareObjectService
     */
    private $share;

    /**
     * @param ShareObjectService $share
     */
    public function __construct(ShareObjectService $share)
    {
        $this->share = $share;
    }

    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input BlockInterface and current url Request.
     *
     * @param ReadBlockInterface $block
     * @param Request            $request  Current url request displaying th block
     * @param bool               $isMobile Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        /* @var PsaPageZoneConfigurableInterface $block */
        $data['block'] = $block;
        $data['myPeugeot'] = $this->share->getMyPeugeot();

        return $data;
    }
}
