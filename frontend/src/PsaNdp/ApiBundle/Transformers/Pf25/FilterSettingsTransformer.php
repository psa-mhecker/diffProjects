<?php

namespace PsaNdp\ApiBundle\Transformers\Pf25;

use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\ApiBundle\Facade\Pf25\FilterSettingsFacade;
use PsaNdp\MappingBundle\Entity\PsaCarSelectorFilter;

class FilterSettingsTransformer extends AbstractTransformer {

    /**
     * @param mixed $filterSettings
     *
     * @return FilterSettingsFacade
     */
    public function transform($filterSettings)
    {
        $filterSettingsFacade = new FilterSettingsFacade();
        $filterSettingsFacade->exteriorHeightPace = $filterSettings->heightGauge;
        $filterSettingsFacade->exteriorLengthPace = $filterSettings->lengthGauge;
        $filterSettingsFacade->mixedConsumptionPace = $filterSettings->consoGauge;
        $filterSettingsFacade->monthlyPricePace = $filterSettings->priceGaugeMonthly;
        $filterSettingsFacade->cashPricePace = $filterSettings->priceGauge;

        $filterSettingsFacade->co2CategoriesLabels = array(
            'A' => $filterSettings->classALabel,
            'B' => $filterSettings->classBLabel,
            'C' => $filterSettings->classCLabel,
            'D' => $filterSettings->classDLabel,
            'E' => $filterSettings->classELabel,
            'F' => $filterSettings->classFLabel,
            'G' => $filterSettings->classGLabel,
        );

        return $filterSettingsFacade;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pf25_filter_settings';
    }
}
