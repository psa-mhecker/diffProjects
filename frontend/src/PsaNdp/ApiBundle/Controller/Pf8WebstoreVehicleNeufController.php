<?php

namespace PsaNdp\ApiBundle\Controller;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use PsaNdp\MappingBundle\Utils\SiteConfiguration;
use PsaNdp\WebserviceConsumerBundle\Webservices\Webstore;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use OpenOrchestra\BaseApiBundle\Controller\Annotation as Api;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Pf8WebstoreVehicleNeufController
 * @package PsaNdp\ApiBundle\Controller
 *
 * @Config\Route("webstore")
 */
class Pf8WebstoreVehicleNeufController extends Controller
{
    const PF8_DELIMITER = '_';

    /**
     * @param Request $request
     *
     * @Config\Route("/pointdevente/{blockVehicle}/{blockMaxPDV}/{blockRayon}/{siteId}", name="psa_ndp_api_webstore_vehicle_neuf_list_parcours_point_de_vente")
     * @Config\Method({"GET"})
     *
     * @Api\Serialize()
     *
     * @throw RuntimeException
     *
     * @return FacadeInterface
     */
    public function parcoursPointDeVente(Request $request)
    {
        //Exemple url : http://fr.psa-ndp.com/api/webstore/pointdevente/1PIA_A5/6/20/2?searchBy=48.862586_2.350493&idDealer=0000000786
        $recherche = $request->get('searchBy'); //TODO Le nom du variable est en attente d'isobar
        $idDealer = $request->get('idDealer'); //TODO Le nom du variable est en attente d'isobar

        $vehicle = $request->get('blockVehicle');
        $languageCode = $this->initSiteConfiguration($request);
        $searchBy = explode(self::PF8_DELIMITER, $recherche);
        $blockVehicle = explode(self::PF8_DELIMITER, $vehicle);
        $blockMaxPDV = $request->get('blockMaxPDV');
        $blockRayon = $request->get('blockRayon');

        /** @var Webstore $serviceWebstore */
        $serviceWebstore = $this->container->get('webstore');

        $searchResults = $serviceWebstore->addContext('LanguageCode', $languageCode)
            ->addFilter('ModelCode', $blockVehicle[0]) //Exemple 1PIA
            ->addFilter('BodyStyleCode', $blockVehicle[1]) //Exemple A5
            ->addFilter('Latitude', $searchBy[0])
            ->addFilter('Longitude', $searchBy[1])
            ->addFilter('DealerIdSiteGeo', ($idDealer != '0' ? $idDealer : ''))
            ->addFilter('GetStoreDetailUrl', 1)
            ->addPaging('CurrentPageNumber', 1)
            ->addPaging('NumberElementByPage', $blockMaxPDV) //Le nombre maximum de dealers à afficher
            ->getInfoPointDeVente($blockRayon, $this->getDecimalPlaces());

        return $this->container->get('open_orchestra_api.transformer_manager')
            ->get('pf8_search_result_collection')
            ->setDomain($request->get('siteId'))
            ->setLocale($request->getLocale())
            ->transform($searchResults);
    }

    /**
     * @param Request $request
     *
     * @Config\Route("/regional/{blockVehicle}/{blockRayon}/{siteId}", name="psa_ndp_api_webstore_vehicle_neuf_list_parcours_regional")
     * @Config\Method({"GET"})
     *
     * @Api\Serialize()
     *
     * @throw RuntimeException
     *
     * @return FacadeInterface
     */
    public function parcoursRegional(Request $request)
    {
        //Exemple url : http://fr.psa-ndp.com/api/webstore/regional/1PIA_A5/20/2?searchBy=48.862586_2.350493
        $recherche = $request->get('searchBy'); //TODO Le nom du variable est en attente d'isobar

        $vehicle = $request->get('blockVehicle');
        $languageCode = $this->initSiteConfiguration($request);
        $searchBy = explode(self::PF8_DELIMITER, $recherche);
        $blockVehicle = explode(self::PF8_DELIMITER, $vehicle);
        $blockRayon = $request->get('blockRayon');

        /** @var Webstore $serviceWebstore */
        $serviceWebstore = $this->container->get('webstore');

        $searchResults = $serviceWebstore->addContext('LanguageCode', $languageCode)
            ->addFilter('ModelCode', $blockVehicle[0]) //Exemple 1PIA
            ->addFilter('BodyStyleCode', $blockVehicle[1]) //Exemple A5
            ->addFilter('Latitude', $searchBy[0])
            ->addFilter('Longitude', $searchBy[1])
            ->addFilter('DealerIdSiteGeo', '')
            ->addFilter('GetStoreDetailUrl', 1)
            ->addPaging('CurrentPageNumber', 1)
            ->addPaging('NumberElementByPage', $blockRayon) //Le nombre maximum des résultats à afficher
            ->getInfoVehicules($this->getDecimalPlaces());

        return $this->container->get('open_orchestra_api.transformer_manager')
            ->get('pf8_search_result_vehicles')
            ->setDomain($request->get('siteId'))
            ->setLocale($request->getLocale())
            ->transform($searchResults);

    }

    /**
     * @param Request $request
     *
     * @Config\Route("/produit/{blockVehicle}/{blockRayon}/{siteId}", name="psa_ndp_api_webstore_vehicle_neuf_list_parcours_produit")
     * @Config\Method({"GET"})
     *
     * @Api\Serialize()
     *
     * @throw RuntimeException
     *
     * @return FacadeInterface
     */
    public function parcoursProduit(Request $request)
    {
        //Exemple url : http://fr.psa-ndp.com/api/webstore/produit/1PIA_A5/20/2
        $vehicle = $request->get('blockVehicle');
        $languageCode = $this->initSiteConfiguration($request);
        $blockVehicle = explode(self::PF8_DELIMITER, $vehicle);
        $blockRayon = $request->get('blockRayon');

        /** @var Webstore $serviceWebstore */
        $serviceWebstore = $this->container->get('webstore');

        $searchResults = $serviceWebstore->addContext('LanguageCode', $languageCode)
            ->addFilter('ModelCode', $blockVehicle[0]) //Exemple 1PIA
            ->addFilter('BodyStyleCode', $blockVehicle[1]) //Exemple A5
            ->addFilter('Latitude', -1)
            ->addFilter('Longitude', -1)
            ->addFilter('DealerIdSiteGeo', '')
            ->addFilter('GetStoreDetailUrl', 1)
            ->addFilter('GroupByVersion', 1)
            ->addPaging('CurrentPageNumber', 1)
            ->addPaging('NumberElementByPage', $blockRayon) //Le nombre maximum des résultats à afficher
            ->getInfoVehicules($this->getDecimalPlaces());

        return $this->container->get('open_orchestra_api.transformer_manager')
            ->get('pf8_search_result_vehicles')
            ->setDomain($request->get('siteId'))
            ->setLocale($request->getLocale())
            ->transform($searchResults);
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function initSiteConfiguration(Request $request)
    {
        /** @var SiteConfiguration $siteConfiguration */
        $siteConfiguration = $this->container->get('site_configuration');
        $siteId = $request->get('siteId');
        $langCode = $request->getLocale();

        $siteConfiguration->setSiteId($siteId)->loadConfiguration();
        $site = $siteConfiguration->getSite();

        return $langCode.'-'.$site->getCountryCode(); // e.g. 'fr-FR'
    }

    /**
     * @return mixed
     */
    protected function getDecimalPlaces()
    {
        /** @var SiteConfiguration $siteConfiguration */
        $siteConfiguration = $this->container->get('site_configuration');

        return intval($siteConfiguration->getNationalParameter('DISTANCE_NB_DECIMAL'));
    }
}
