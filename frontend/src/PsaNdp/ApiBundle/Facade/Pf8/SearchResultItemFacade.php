<?php

namespace PsaNdp\ApiBundle\Facade\Pf8;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class SearchResultItemFacade
 * @package PsaNdp\ApiBundle\Facade\Pf8
 */
class SearchResultItemFacade implements FacadeInterface
{
    /**
     * @Serializer\SerializedName("imageSrc")
     * @Serializer\Type("string")
     */
    public $imageSrc;

    /**
     * @Serializer\SerializedName("imageAlt")
     * @Serializer\Type("string")
     */
    public $imageAlt;

    /**
     * @Serializer\Type("boolean")
     */
    public $news;

    /**
     * @Serializer\Type("string")
     */
    public $title;

    /**
     * @Serializer\Type("string")
     */
    public $engine;

    /**
     * @Serializer\Type("string")
     */
    public $painting;

    /**
     * @Serializer\Type("string")
     */
    public $trimming;

    /**
     * @Serializer\Type("string")
     */
    public $consumption;

    /**
     * @Serializer\Type("string")
     */
    public $emission;

    /**
     * @Serializer\Type("string")
     */
    public $urlCtaOffre;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf8\PricesFacade")
     */
    public $prices;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\Pf8\SearchResultDealerVehicleFacade")
     */
    public $dealer;
}
