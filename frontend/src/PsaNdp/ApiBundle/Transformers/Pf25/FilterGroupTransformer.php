<?php

namespace PsaNdp\ApiBundle\Transformers\Pf25;

use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\ApiBundle\Facade\Pf25\CarSelectorFilterInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class FilterGroupTransformer
 * @package PsaNdp\ApiBundle\Transformers\Pf25
 */
class FilterGroupTransformer extends AbstractTransformer
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @var array
     */
    private $valuesMap = array(
        "NDP_FILTER_CLASS" => array(
            'fieldName' => 'Co2Class',
            'type' => CarSelectorFilterInterface::MULTI_FILTER
        ),
        "NDP_FILTER_CONSO" => array(
            'fieldName' => 'MixedConsumption',
            'type' => CarSelectorFilterInterface::EDGED_FILTER
        ),
        "NDP_FILTER_ENERGY" => array(
            'fieldName' => 'Energy',
            'fieldValue' => 'array',
            'type' => CarSelectorFilterInterface::MULTI_FILTER
        ),
        "NDP_FILTER_HEIGHT" => array(
            'fieldName' => 'ExteriorHeight',
            'type' => CarSelectorFilterInterface::EDGED_FILTER
        ),
        "NDP_FILTER_LENGTH" => array(
            'fieldName' => 'ExteriorLength',
            'type' => CarSelectorFilterInterface::EDGED_FILTER,
        ),
        "NDP_FILTER_SEAT_NB" => array(
            'fieldName' => 'NumSittedPlaces',
            'type' => CarSelectorFilterInterface::MULTI_FILTER,
        ),
        "NDP_FILTER_PRICE" => array(
            'fieldName' => 'Price',
            'fieldValue' => 'basePrice',
            'type' => CarSelectorFilterInterface::EDGED_FILTER
        ),
        'NDP_FILTER_GEARBOX_TYPE' => array(
            'fieldName' => 'GrTransmissionType',
            'fieldValue' => 'id',
            'type' => CarSelectorFilterInterface::MULTI_FILTER
        ),
        "NDP_FILTER_VOLUME" => array(
            'fieldName' => '',
            'type' => CarSelectorFilterInterface::MULTI_FILTER
        ),
    );

    /**
     * @param mixed $mixed
     *
     * @return array
     */
    public function transform($mixed)
    {
        $filters = array();
        if ( ! empty($mixed->cheapestVersion)) {

            $filters['vehicleCategory'] = array(
                'value' =>"A définir",
                'type' => CarSelectorFilterInterface::MULTI_FILTER,
                'translation' => $this->translator->trans(
                    'NDP_VEHICLE_CATEGORY',
                    array(),
                    $mixed->getSite()->getId(),
                    $mixed->getLangue()->getLangueCode()
                )
            );

            if ($mixed->financement->isEnabled()) {

                $monthlyPrice = $mixed->financement->getFinancementDetailsUnit('PMTASSO');

                if ( ! empty($monthlyPrice)) {
                    $filters['monthlyPrice'] = array(
                        'value' => $monthlyPrice['value'],
                        'type' => CarSelectorFilterInterface::EDGED_FILTER,
                        'translation' => $this->translator->trans(
                            'NDP_MONTHLY_PRICE',
                            array(),
                            $mixed->getSite()->getId(),
                            $mixed->getLangue()->getLangueCode()
                        )
                    );
                }
            }

            foreach ($mixed->activeFilters as $filterKey) {

                $activeFilter = $this->valuesMap[$filterKey];

                if ( ! empty($activeFilter['fieldName'])) {

                    $filterName = lcfirst($activeFilter['fieldName']);

                    if ( ! empty($activeFilter['fieldValue']) && $activeFilter['fieldValue'] != 'array') {

                        //$filters[$filterName]['value'] = $mixed->cheapestVersion->{$activeFilter['fieldName']}->{$activeFilter['fieldValue']};

                    } elseif ( ! empty($mixed->cheapestVersion->{$activeFilter['fieldName']})) {

                        //$filters[$filterName]['value'] = $mixed->cheapestVersion->{$activeFilter['fieldName']};
                    }

                    $filters[$filterName]['type'] = $activeFilter['type'];
                    $filters[$filterName]['translation'] = $this->translator->trans(
                        $filterKey,
                        array(),
                        $mixed->getSite()->getId(),
                        $mixed->getLangue()->getLangueCode()
                    );
                }
            }
        }

        return $filters;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'pf25_filter_group';
    }
}
