<?php

namespace PsaNdp\ApiBundle\Facade\Pf25;


use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;


class FilterSettingsFacade implements FacadeInterface {

    /**
     * @Serializer\Type("integer")
     */
    public $mixedConsumptionPace;

    /**
     * @Serializer\Type("integer")
     */
    public $exteriorLengthPace;

    /**
     * @Serializer\Type("integer")
     */
    public $exteriorHeightPace;


    /**
     * @Serializer\Type("integer")
     */
    public $cashPricePace;

    /**
     * @Serializer\Type("integer")
     */
    public $monthlyPricePace;

    /**
     * @Serializer\Type("array<string>")
     */
    public $co2CategoriesLabels;
}
