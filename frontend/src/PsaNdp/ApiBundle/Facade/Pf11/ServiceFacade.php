<?php
namespace PsaNdp\ApiBundle\Facade\Pf11;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class ServiceFacade
 * @package PsaNdp\ApiBundle\Facade\Pf11
 *
 *
 */
class ServiceFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $code;
    /**
     * @Serializer\Type("string")
     */
    public $typeName;

    /**
     * @Serializer\Type("string")
     */
    public $type;

    /**
     * @Serializer\Type("string")
     */
    public $name;

    /**
     * @Serializer\Type("string")
     */
    public $tel;

    /**
     * @Serializer\Type("string")
     */
    public $fax;

    /**
     * @Serializer\Type("string")
     */
    public $typePicto;

    /**
     * @Serializer\Type("string")
     */
    public $typePictoAlt;

    /**
     * @Serializer\Type("string")
     */
    public $icon;

    /**
     * @Serializer\Type("string")
     */
    public $mail;

    /**
     * @var int
     * @Serializer\Exclude
     */
    public $order;
}
