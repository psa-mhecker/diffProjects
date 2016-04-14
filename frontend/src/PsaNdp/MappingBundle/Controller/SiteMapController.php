<?php

namespace PsaNdp\MappingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class
 * @package PsaNdp\MappingBundle\Controller
 *
 */
class SiteMapController extends Controller
{
    /**
     * @param Request $request
     *
     * @Config\Route("/sitemap.xml", name="psa_ndp_show_site_map", defaults={"_format": "xml"})
     * @Config\Method({"GET"})
     *
     * @return Response
     */
    public function showAction(Request $request)
    {
        $siteRepository = $this->get('open_orchestra_model.repository.site');
        $site = $siteRepository->findByAliasDomain($request->getHost());

        $langueCode = $request->get('lg') ? $request->get('lg') : $site[0]->getLangues()[0]->getLangueCode();

        $siteMapFile = $this->container->getParameter('sitemap.default.path').$site[0]->getSiteId()."/".$langueCode."/sitemap.xml";

        if (file_exists($siteMapFile)){
            return new Response(file_get_contents($siteMapFile),200,array('Content-Type'=>'application/xml'));
        }
        else{
            return $this->render(
                'PsaNdpMappingBundle::siteMap.html.twig',
                array(
                    'siteMapPages' => ''
                )
            );
        }

    }

}
