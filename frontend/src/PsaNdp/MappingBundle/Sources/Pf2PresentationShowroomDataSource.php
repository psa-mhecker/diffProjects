<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMulti;
use PsaNdp\MappingBundle\Object\Vehicle;
use PsaNdp\MappingBundle\Services\ShareServices\ShareObjectService;
use Symfony\Component\HttpFoundation\Request;

/**
 * Data source.
 */
class Pf2PresentationShowroomDataSource extends AbstractDataSource
{
     /**
     * @var array
     */
    protected $data;

    /**
     * @var ShareObjectService
     */
    protected $share;

    /**
     * Pf2PresentationShowroomDataSource constructor.
     *
     * @param ShareObjectService $share
     */
    public function __construct(ShareObjectService $share)
    {
        $this->share = $share;
    }

    /**
     * @param ReadBlockInterface $block
     * @param Request            $request  Current url request displaying th block
     * @param bool               $isMobile Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {

        /* @var PsaPageZoneConfigurableInterface $block */
        $this->data = [];
        $this->data['block'] = $block;
        $this->data['multis'] = $block->getMultisByType(PsaPageZoneMulti::PAGE_ZONE_MULTI_VALUE_SLIDE_IMAGE);
        try {

            $vehicle = $this->share->getVehicle();

            if ($vehicle instanceof Vehicle) {
                $information = $vehicle->getModelSilhouetteInformation();
                $this->data['model'] = $vehicle->getModelName();
                $this->data['silhouette'] = $vehicle->getLabelGrBodyStyle();
                if ($information) {
                    $this->data['isNew'] = $information->getNewCommercialStrip();
                }
                $this->data['modelSilhouetteInformation'] = $information;
                $this->data['version'] = $vehicle->getVersion();
            }
        } catch (\Exception $e) {
        }
        $this->updateMaxAge();

        return $this->data;
    }

    protected function updateMaxAge()
    {
        $block = $this->getBlock();
        if ($block->getZoneAttribut2()) {
            $startDay  = $block->getZoneDate();
            $startHour = $block->getZoneTitre3();
            $startHour = explode(':', $startHour);
            $startDay->setTime($startHour[0], (isset($startHour[1]) ? $startHour[1] : 00));

            $now = new \Datetime('now');
            if ($now <= $startDay) {
                $diff = $startDay->diff($now);
                $seconds = ($diff->y * 365 * 24 * 60 * 60) +
                    ($diff->m * 30 * 24 * 60 * 60) +
                    ($diff->d * 24 * 60 * 60) +
                    ($diff->h * 60 * 60) +
                    ($diff->i * 60) +
                    $diff->s;

                $block->setMaxAge($seconds);
            }
        }
    }
}
