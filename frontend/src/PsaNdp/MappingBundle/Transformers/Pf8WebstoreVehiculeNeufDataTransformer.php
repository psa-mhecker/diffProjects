<?php

namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\AbstractObject;
use PsaNdp\MappingBundle\Object\Block\Pf8WebstoreVehicleNeuf;

/**
 * Data transformer for Pf8WebstoreVehicleNeuf block
 */
class Pf8WebstoreVehiculeNeufDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{

    /**
     * @var Pf8WebstoreVehicleNeuf
     */
    protected $pf8WebstoreVehicleNeuf;

    /**
     * @param Pf8WebstoreVehicleNeuf $pf8WebstoreVehicleNeuf
     */
    public function __construct(Pf8WebstoreVehicleNeuf $pf8WebstoreVehicleNeuf)
    {
        $this->pf8WebstoreVehicleNeuf = $pf8WebstoreVehicleNeuf;
    }

    /**
     *  Fetching data slice Webstore Vehicule Neuf (pf8)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $pf8 = $this->pf8WebstoreVehicleNeuf->setDataFromArray($dataSource);
        $translate = array(
            'noresult' => $this->trans('NDP_NO_RESULT'),
            'errorload' => $this->trans('NDP_AJAX_LOADING_ISSUE'),
            'searchTxt' => $this->trans(AbstractObject::NDP_OK),
            'hasNav_or_txt' => $this->trans('NDP_OR'),
            'cas1_title' => $this->trans('NDP_FIND_DEALER'),
            'cas1_btnAroundMe' => $this->trans('NDP_AROUND_ME'),
            'cas1_searchType_label1' => $this->trans('NDP_PF11_BY_CITY_OR_POSTAL_CODE'),
            'cas1_searchType_placeholder1' => $this->trans('NDP_INDICATE_CITY_OR_POSTAL_CODE'),
            'cas1_searchType_label2' => $this->trans('NDP_PF11_BY_POINT_OF_SALE_NAME'),
            'cas1_searchType_placeholder2' => $this->trans('NDP_CHOOSE_DEALER_NAME'),
            'hasNav_btnAroundMe' => $this->trans('NDP_AROUND_ME'),
            'hasNav_pdvInput_label' => $this->trans('NDP_YOUR_DEALER'),
            'distType' => $this->trans('NDP_KM'),
            'news' => $this->trans($dataSource['bandeau']),
            'profit' => $this->trans('NDP_ENJOY_THIS_OFFER'),
            'ctaList' => $pf8->getCtaList(),
            'phone' => $this->trans('NDP_TEL'),
            'trimming' => $this->trans('NDP_LINING'),
            'consumption' => $this->trans('NDP_CONSUMPTION2'),
            'emission' => $this->trans('NDP_EMISSION'),
            '_or' => $this->trans('NDP_EITHER'),
            'currency' => $this->trans('NDP_EURO'),
            'save' => $this->trans('NDP_SAVING'),
            'price_advice' => $this->trans('NDP_RECOMMENDED_PRICE'),
            'legal_notices' => $dataSource['mentionsLegales'],
        );
        $pf8->initializeParcours($dataSource['parcours'], $translate);
        $pf8->initializeCta($dataSource['urlWebstore'], $this->trans('NDP_SEE_AVAILABLE_STOCKS'));
        $pf8->setTranslate($translate);

        return array('slicePf8' => $this->pf8WebstoreVehicleNeuf);
    }
}
