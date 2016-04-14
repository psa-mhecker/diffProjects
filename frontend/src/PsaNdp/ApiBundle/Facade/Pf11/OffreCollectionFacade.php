<?php
namespace PsaNdp\ApiBundle\Facade\Pf11;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class OffreCollectionFacade
 * @package PsaNdp\ApiBundle\Facade\Pf11
 */
class OffreCollectionFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $title;

    /**
     * @Serializer\SerializedName("offresList")
     * @Serializer\Type("array<PsaNdp\ApiBundle\Facade\Pf11\OffreFacade>")
     */
    public $offresList;
}
