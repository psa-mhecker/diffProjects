<?php
namespace PsaNdp\ApiBundle\Facade\Pf33;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class FinitionConnectServiceResultItemFacade
 * @package PsaNdp\ApiBundle\Facade\Pf33
 */
class FinitionConnectServiceResultItemFacade implements FacadeInterface
{

    /**
     * @Serializer\Type("string")
     */
    public $label;

}
