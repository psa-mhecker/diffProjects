<?php

namespace PsaNdp\MappingBundle\Sources;


use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;


/**
 * Data source for Pf44DevenirAgent block
 */
class Pf44DevenirAgentDataSource extends AbstractDataSource
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input BlockInterface and current url Request
     *
     * @param ReadBlockInterface $block
     * @param Request $request Current url request displaying th block
     * @param bool $isMobile Indicate if is a mobile display
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        $siteId = $request->attributes->get('siteId');

        /* @var $block PsaPageZoneConfigurableInterface */
        $data['block'] = $block;

        $data['urlJson'] = $this->generateUrlJson($siteId, $block);

        return $data;
    }

    /**
     * Return url to fetch the json data
     *
     * @param $siteId
     *
     * @return string
     */
    private function generateUrlJson($siteId, $block){

        return $this->router->generate('psa_ndp_api_become_agent_list', ['siteId'=>$siteId, 'rayon' => $block->getZoneLabel2()], true);
    }
}
