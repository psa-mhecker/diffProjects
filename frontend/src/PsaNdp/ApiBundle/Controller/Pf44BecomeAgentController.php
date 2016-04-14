<?php

namespace PsaNdp\ApiBundle\Controller;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use PsaNdp\MappingBundle\Repository\PsaBecomeAgentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use OpenOrchestra\BaseApiBundle\Controller\Annotation as Api;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Pf44BecomeAgentController
 *
 * @Config\Route("become-agent")
 */
class Pf44BecomeAgentController extends Controller
{
    const SALE = 421119;
    const AGENT = 421122;

    /**
     * @param int     $siteId
     * @param Request $request
     *
     * @Config\Route("/{siteId}/{rayon}", requirements={"siteId" = "\d+"}, name="psa_ndp_api_become_agent_list")
     * @Config\Method({"GET"})
     *
     * @Api\Serialize()
     *
     * @throw RuntimeException
     *
     * @return FacadeInterface
     */
    public function showAction($siteId, $rayon, Request $request)
    {
        $agentRepository = $this->container->get('psa_ndp_become_agent_repository');
        $siteRepository = $this->container->get('psa_ndp_site_repository');
        $site = $siteRepository->find($siteId);
        $departure = $request->get('departure');
        $coordonee = explode(',', $departure);
        $filter = $request->get('filter');
        if (!empty($filter)) {
            $agents = $this->getAgents($filter, $agentRepository, $site, $coordonee, $rayon);
        } else {
            $agents = $agentRepository->getDistance($coordonee[0], $coordonee[1], $rayon, $site->getId());
        }

        return $this->container->get('open_orchestra_api.transformer_manager')->get('become_agent_collection')->transform($agents);
    }

    /**
     * @param array                    $filter
     * @param PsaBecomeAgentRepository $agentRepository
     * @param PsaSite                  $site
     *
     * @return mixed
     */
    public function getAgents(array $filter, PsaBecomeAgentRepository $agentRepository, PsaSite $site, $coordonee, $rayon)
    {
        $agent = '0';
        $sale = '0';
        $lat = $coordonee[0];
        $long = $coordonee[1];

        if (isset($filter['agent'])) {
            $agent = $filter['agent'];
        }
        if (isset($filter['sale'])) {
            $sale = $filter['sale'];
        }

        if ('0' === $agent && '0' === $sale) {
            $agents = $agentRepository->getDistance($lat, $long, $rayon, $site->getId());
        } else {
            if ('1' === $agent && '1' === $sale) {
                $agents = $agentRepository->getDistance($lat, $long, $rayon, $site->getId(), array(self::AGENT, self::SALE));
            } else {
                if ('1' === $sale) {
                    $linkId = self::SALE;
                }
                if ('1' === $agent) {
                    $linkId = self::AGENT;
                }
                $agents = $agentRepository->getDistance($lat, $long, $rayon, $site->getId(), array($linkId));
            }
        }

        return $agents;
    }
}
