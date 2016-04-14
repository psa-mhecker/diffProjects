<?php

namespace PsaNdp\ApiBundle\Transformers;

use Doctrine\Common\Collections\ArrayCollection;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\ApiBundle\Facade\AgentCollectionFacade;

/**
 * Class BecomeAgentTransformer
 */
class BecomeAgentCollectionTransformer extends AbstractTransformer
{
    /**
     * @param ArrayCollection $mixed
     *
     * @return FacadeInterface
     */
    public function transform($mixed)
    {
        $agents = new AgentCollectionFacade();

        foreach ($mixed as $agent) {
            $agents->addAgent($this->getTransformer('become_agent')->transform($agent));
        }

        return $agents;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'become_agent_collection';
    }
}
