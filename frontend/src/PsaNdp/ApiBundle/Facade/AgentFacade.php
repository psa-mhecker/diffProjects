<?php

namespace PsaNdp\ApiBundle\Facade;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class AgentFacade
 */
class AgentFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $name;

    /**
     * @Serializer\Type("string")
     */
    public $type;

    /**
     * @Serializer\Type("boolean")
     */
    public $dealerSale;

    /**
     * @Serializer\Type("boolean")
     */
    public $dealerAgent;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\AddressFacade")
     */
    public $adress;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\ContentFacade")
     */
    public $contenu;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\ContactFacade")
     */
    public $contact;

    /**
     * @Serializer\Type("string")
     */
    public $id;
}
