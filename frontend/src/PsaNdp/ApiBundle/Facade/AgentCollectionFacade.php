<?php

namespace PsaNdp\ApiBundle\Facade;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class AgentCollectionFacade
 */
class AgentCollectionFacade implements FacadeInterface
{
    /**
     * @Serializer\SerializedName("listDealer")
     * @Serializer\Type("array<PsaNdp\ApiBundle\Facade\AgentFacade>")
     */
    protected $agents = array();

    /**
     * @param FacadeInterface $agent
     */
    public function addAgent(FacadeInterface $agent)
    {
        $this->agents[] = $agent;
    }
}
