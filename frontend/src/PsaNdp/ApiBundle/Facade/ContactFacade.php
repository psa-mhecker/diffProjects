<?php

namespace PsaNdp\ApiBundle\Facade;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class ContactFacade
 */
class ContactFacade implements FacadeInterface
{
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
    public $mail;

    /**
     * @Serializer\Type("string")
     */
    public $website;

    /**
     * @Serializer\Type("string")
     */
    public $vcf;
}
