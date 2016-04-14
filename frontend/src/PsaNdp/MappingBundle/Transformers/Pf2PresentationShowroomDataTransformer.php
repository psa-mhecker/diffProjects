<?php

namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Pf2PresentationShowroom;
use PsaNdp\MappingBundle\Object\Block\Pn15EnteteConfishow;

/**
 * Data transformer for Pf2PresentationShowroom block
 */
class Pf2PresentationShowroomDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    const NDP_CLOSE = 'NDP_CLOSE';

    /**
     * @var Pf2PresentationShowroom
     */
    protected $pf2PresentationShowroom;

    /**
     * @param Pf2PresentationShowroom $pf2PresentationShowroom
     */
    public function __construct(Pf2PresentationShowroom $pf2PresentationShowroom)
    {
        $this->pf2PresentationShowroom = $pf2PresentationShowroom;
    }

    /**
     *  Fetching data slice PresentationShowroom (Pf2)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $this->pf2PresentationShowroom->setIsMobile($isMobile);
        $this->setTranslatations();
        $this->pf2PresentationShowroom->setMediaServer($this->mediaServer);
        if (!empty($dataSource['version'])) {
            if (isset($dataSource['imgSrc'])) {
                $this->pf2PresentationShowroom->setPopinImgSrc($dataSource['imgSrc']);
            }
            $this->pf2PresentationShowroom->getPriceManager()->setTranslator($this->translator, $this->domain, $this->locale);
            $this->pf2PresentationShowroom->getPriceManager()->setVersion($dataSource['version']);
            $this->pf2PresentationShowroom->getPriceManager()->setCheapest($dataSource['version']);
            $this->pf2PresentationShowroom->getPriceManager()->setModelSilhouetteInformation($dataSource['modelSilhouetteInformation']);
        }
        $this->pf2PresentationShowroom->setMultis($dataSource['multis']);
        $this->pf2PresentationShowroom->setDataFromArray($dataSource);
        $this->pf2PresentationShowroom->init();

        return array(
            'slicePF2' => $this->pf2PresentationShowroom,
            'cpt' => 0,
        );
    }
    
    public function setTranslatations()
    {
        $this->pf2PresentationShowroom->setMonthLabel($this->trans('NDP_MONTH_FIRST'));
        $this->pf2PresentationShowroom->setDayLabel($this->trans('NDP_DAY_FIRST'));
        $this->pf2PresentationShowroom->setOrLabel($this->trans('NDP_OR'));
        $this->pf2PresentationShowroom->setPopinMentionLegaleConso($this->trans('NDP_MSG_LEGAL_CONSO'));
        $this->pf2PresentationShowroom->setParamUnites(
                [
                    $this->trans('NDP_UNIT_FUEL'),
                    $this->trans('NDP_UNIT_CO2'),
                    $this->trans('NDP_UNIT_BY_FUEL'),
                    $this->trans('NDP_UNIT_BY_CO2')
                ]
        );

       $this->pf2PresentationShowroom->setTranslate([
            'close' => $this->trans(self::NDP_CLOSE),
            'peugeot' => $this->trans(Pn15EnteteConfishow::NDP_PEUGEOT),
            'new' => $this->trans(Pn15EnteteConfishow::NDP_NEW_CAR),
        ]);
    }
}
