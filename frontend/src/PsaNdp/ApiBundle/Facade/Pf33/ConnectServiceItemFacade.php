<?php
namespace PsaNdp\ApiBundle\Facade\Pf33;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class ConnectServiceItemFacade
 * @package PsaNdp\ApiBundle\Facade\Pf33
 */
class ConnectServiceItemFacade implements FacadeInterface
{

    /**
     * @Serializer\Type("string")
     */
    public $title;

    /**
     * @Serializer\Type("string")
     */
    public $subtitle;
    /**
     * @Serializer\Type("boolean")
     */
    public $empty;

    /**
     * @Serializer\SerializedName("result")
     * @Serializer\Type("array<PsaNdp\ApiBundle\Facade\Pf33\FinitionConnectServiceResultItemFacade>")
     */
    public $listFinitionConnectServiceResult;

    /**
     * @param FinitionConnectServiceResultItemFacade $finitionConnectServiceResultItem
     */
    public function addFinitionConnectServiceResultItem(FinitionConnectServiceResultItemFacade $finitionConnectServiceResultItem)
    {
        $this->listFinitionConnectServiceResult[] = $finitionConnectServiceResultItem;
    }

}
