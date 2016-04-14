<?php

namespace PsaNdp\ApiBundle\Facade;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class TableHeadFacade
 */
class TableHeadFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $title;
}
