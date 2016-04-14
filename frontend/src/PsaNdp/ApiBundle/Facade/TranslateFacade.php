<?php
namespace PsaNdp\ApiBundle\Facade;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class LinkFacade
 */
class TranslateFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $yes;

    /**
     * @Serializer\Type("string")
     */
    public $no;
}
