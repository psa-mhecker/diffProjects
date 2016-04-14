<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Object\Vehicle;
use PsaNdp\MappingBundle\Services\ShareServices\ShareObjectService;
use PsaNdp\MappingBundle\Utils\PageUtils;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Pn15EnteteConfishowDataSource.
 */
class Pn15EnteteConfishowDataSource extends AbstractDataSource
{
    /**
     * @var PageUtils
     */
    private $pageUtils;

    /**
     * @var ShareObjectService
     */
    private $share;

    /**
     * @param PageUtils          $pageUtils
     * @param ShareObjectService $share
     */
    public function __construct(PageUtils $pageUtils, ShareObjectService $share)
    {
        $this->pageUtils = $pageUtils;
        $this->share = $share;
    }

    /**
     * @param ReadBlockInterface $block
     * @param Request            $request
     * @param bool               $isMobile
     *
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        $data = [];
        /* @var $block PsaPageZoneConfigurableInterface */
        $data['block'] = $block;
        $data['title'] = $this->getRealBlock()->getPage()->getCurrentVersion()->getPageTitle();
        $data['breadcrumb'] = $this->pageUtils->getBreadcumb($this->getRealBlock()->getPage());
        $vehicle = $this->share->getVehicle();

        if ($vehicle instanceof Vehicle) {
            $information = $vehicle->getModelSilhouetteInformation();
            $data['model'] = $vehicle->getModelName();
            $data['silhouette'] = $vehicle->getLabelGrBodyStyle();
            if (!empty($information)) {
                $data['isNew'] = $information->getNewCommercialStrip();
            }
        }
        $data['myPeugeot'] = $this->share->getMyPeugeot();

        return $data;
    }
}
