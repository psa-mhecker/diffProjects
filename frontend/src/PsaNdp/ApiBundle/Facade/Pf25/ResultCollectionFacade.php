<?php

namespace PsaNdp\ApiBundle\Facade\Pf25;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

class ResultCollectionFacade implements FacadeInterface
{

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\FilterValuesFacade")
     */
    public $filters;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\FilterSettingsFacade")
     */
    public $filterSettings;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\VehicleCategoryCollectionFacade")
     */
    public $vehicleCategories;

    /**
     * @Serializer\Type("array<PsaNdp\ApiBundle\Facade\Pf25\ResultItemFacade>")
     */
    protected $results;

    /**
     * @param ResultItemFacade $resultItem
     */
    public function addResultItem(ResultItemFacade $resultItem)
    {
        $this->results[] = $resultItem;
    }
}
