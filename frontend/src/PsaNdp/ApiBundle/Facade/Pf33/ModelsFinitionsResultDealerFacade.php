<?php
namespace PsaNdp\ApiBundle\Facade\Pf33;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class ModelsFinitionsResultDealerFacade
 * @package PsaNdp\ApiBundle\Facade\Pf33
 */
class ModelsFinitionsResultDealerFacade implements FacadeInterface
{

    /**
     * @Serializer\Type("string")
     */
    public $id;

    /**
     * @Serializer\Type("boolean")
     */
    public $full;

    /**
     * @Serializer\Type("boolean")
     */
    public $light;

    /**
     * @Serializer\Type("boolean")
     */
    public $legend;

    /**
     * @Serializer\SerializedName("tabhaut")
     * @Serializer\Type("array<PsaNdp\ApiBundle\Facade\Pf33\FinitionsResultItemFacade>")
     */
    public $listFinitions;

    /**
     * @Serializer\SerializedName("line")
     * @Serializer\Type("array<PsaNdp\ApiBundle\Facade\Pf33\ConnectServiceItemFacade>")
     */
    public $listConnectService;

    /**
     * @param FinitionsResultItemFacade $finitionItem
     */
    public function addFinitionItem(FinitionsResultItemFacade $finitionItem)
    {
        $this->listFinitions[] = $finitionItem;
    }

    /**
     * @param ConnectServiceItemFacade $connectService
     */
    public function addConnectServiceItem(ConnectServiceItemFacade $connectService)
    {
        $this->listConnectService[] = $connectService;
    }
}
