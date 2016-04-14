<?php
namespace PsaNdp\ApiBundle\Facade;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class Pf17FormFacade
 */
class Pf17FormFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $culture;

    /**
     * @Serializer\Type("string")
     */
    public $langue;

    /**
     * @Serializer\Type("string")
     */
    public $country;

    /**
     * @Serializer\Type("string")
     */
    public $instanceid;

    /**
     * @Serializer\Type("string")
     */
    public $idSiteGeo;

    /**
     * @Serializer\Type("array")
     */
    public $lcdv16;

}
