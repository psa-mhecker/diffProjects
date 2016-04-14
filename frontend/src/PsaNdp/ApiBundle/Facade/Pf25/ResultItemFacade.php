<?php


namespace PsaNdp\ApiBundle\Facade\Pf25;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

class ResultItemFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $id;

    /**
     * @Serializer\Type("string")
     */
    public $commercialLabel;

    /**
     * @Serializer\SerializedName("cssClass")
     * @Serializer\Type("string")
     */
    public $cssClass;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\StickerFacade")
     */
    public $sticker;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\VehicleFacade")
     */
    public $vehicle;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\VehicleCriteriaFacade")
     */
    public $criteria;

    /**
     * @Serializer\Type("string")
     */
    public $title;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\PriceFacade")
     */
    public $price;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\LinkFacade")
     */
    public $discover;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\LinkFacade")
     */
    public $compare;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\LinkFacade")
     */
    public $storeLocator;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf25\FinancementFacade")
     */
    public $financement;

}
