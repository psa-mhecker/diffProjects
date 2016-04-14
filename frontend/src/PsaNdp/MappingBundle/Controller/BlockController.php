<?php

namespace PsaNdp\MappingBundle\Controller;

use PSA\MigrationBundle\Entity\Page\PsaPage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use OpenOrchestra\DisplayBundle\Exception\DisplayBlockStrategyNotFoundException;
use \Exception;

/**
 * Class BlockController
 */
class BlockController extends Controller
{
    /**
     * @var PsaPage;
     */
    private $node;

    /**
     * @param $siteId
     * @param $nodeId
     * @param $language
     *
     * @return PsaPage
     */
    public function getNode($siteId, $nodeId, $language)
    {
        if(!isset($this->node)) {
           $this->node = $this->get('open_orchestra_model.repository.node')
            ->findOnePublishedByNodeIdAndLanguageAndSiteIdInLastVersion($nodeId, $language, $siteId);
            $shareObject = $this->get('psa_ndp.services.share_object');
            $shareObject->setNode($this->node);
            $shareObject->setIsMobile($this->isMobile());
        }

        return $this->node;
    }

    /**
     * @param PsaPage $node
     * @return BlockController
     */
    public function setNode($node)
    {
        $this->node = $node;

        return $this;
    }

    /**
     * Display the response linked to a block
     *
     * @param Request $request
     * @param int     $siteId
     * @param int     $nodeId
     * @param string  $blockId
     * @param string  $language
     *
     * @Config\Route("/block/{siteId}/{nodeId}/{blockId}/{language}", name="open_orchestra_front_block")
     * @Config\Method({"GET"})
     *
     * @return Response
     * @throws Exception
     *
     * @todo: log error of rendering block
     */
    public function showAction(Request $request, $siteId, $nodeId, $blockId, $language)
    {
        $output  = '';
        $node = $this->getNode($siteId, $nodeId, $language);

        $moduleParameters = $request->attributes->get('module_parameters');

        if ($node && !empty($moduleParameters['prefix'])){
            $node->setPreview(true);
        }
        try {
            if ($node && (null !== ($block = $node->getBlock($blockId)))) {
                /** @var Response $response */
                $response = $this->get('open_orchestra_display.display_block_manager')
                    ->show($block);

                return $response;
            }
        } catch(DisplayBlockStrategyNotFoundException $e) // if strategy missing
        {
            if($this->container->get( 'kernel' )->isDebug()) { // display error information in debug env
                $output = $e->getMessage();
            }
        } catch(Exception $e) { // catch all other error and show them only on debug env else silence error

            if($this->container->get( 'kernel' )->isDebug()) {
               throw $e;
            }
        }

        return new Response($output);
    }

    /**
     * @return bool
     */
    public function isMobile()
    {
        return $this->container->get('open_orchestra_display.display_block_manager')->getDeviceUtils()->isMobile();
    }
}
