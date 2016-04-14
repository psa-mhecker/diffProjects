<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PsaNdp\MappingBundle\Object\Block\Pf8WebstoreVehicleNeuf;
use PsaNdp\MappingBundle\Repository\PsaSitesEtWebservicesPsaRepository;
use PsaNdp\MappingBundle\Repository\Vehicle\PsaModelConfigRepository;
use PsaNdp\MappingBundle\Repository\Vehicle\PsaModelSilhouetteSiteRepository;
use PsaNdp\MappingBundle\Utils\SiteConfiguration;
use PsaNdp\WebserviceConsumerBundle\Webservices\Webstore;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

/**
 * Data source for Pf8WebstoreVehiculeNeuf block
 */
class Pf8WebstoreVehiculeNeufDataSource extends AbstractDataSource
{
    const PF8_DELIMITER = '_';
    const NOUVEAUTE = 1;
    const OFFRE_SPECIALE = 2;
    const SERIE_SPECIALE = 3;

    protected $router;

    protected $siteConfiguration;

    protected $webstore;

    protected $languageCode;

    protected $psaModelConfigRepository;

    protected $psaModelSilhouetteSiteRepository;

    protected $psaSitesEtWebservicesPsaRepository;

    /**
     * @param RouterInterface                    $router
     * @param SiteConfiguration                  $siteConfiguration
     * @param Webstore                           $webstore
     * @param PsaModelConfigRepository           $psaModelConfigRepository
     * @param PsaModelSilhouetteSiteRepository   $psaModelSilhouetteSiteRepository
     * @param PsaSitesEtWebservicesPsaRepository $psaSitesEtWebservicesPsaRepository
     */
    public function __construct(RouterInterface $router, SiteConfiguration $siteConfiguration, Webstore $webstore, PsaModelConfigRepository $psaModelConfigRepository, PsaModelSilhouetteSiteRepository $psaModelSilhouetteSiteRepository, PsaSitesEtWebservicesPsaRepository $psaSitesEtWebservicesPsaRepository)
    {
        $this->router = $router;
        $this->siteConfiguration = $siteConfiguration;
        $this->webstore = $webstore;
        $this->psaModelConfigRepository = $psaModelConfigRepository;
        $this->psaModelSilhouetteSiteRepository = $psaModelSilhouetteSiteRepository;
        $this->psaSitesEtWebservicesPsaRepository = $psaSitesEtWebservicesPsaRepository;
    }

    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input BlockInterface and current url Request
     *
     * @param  ReadBlockInterface $block
     * @param  Request            $request  Current url request displaying th block
     * @param  bool               $isMobile Indicate if is a mobile display
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        $siteId = $request->attributes->get('siteId');
        $languageCode = $request->attributes->get('language');

        $data['block']      = $block;

        $gammeVehicule = $block->getPage()->getVersion()->getGammeVehicule();
        $lcdv4 = $bodyStyle = '';
        if ($gammeVehicule) {
            $lcdv4 = substr($gammeVehicule, 0, 4);
            $bodyStyle = substr($gammeVehicule, 4, 2);
        }

        $blockVehicle = $lcdv4.self::PF8_DELIMITER.$bodyStyle;
        $blockMaxPDV = $block->getZoneAttribut2();
        $blockRayon = $block->getZoneAttribut3();
        $lcdv6 = $lcdv4.$bodyStyle;

        $data['bandeau'] = $this->getBandeau($siteId, $languageCode, $lcdv6);
        $data['parcours'] = $this->getParcoursWebstore($siteId);
        $data['urlJson'] = $this->generateUrlJsonPointDeVente($blockVehicle, $blockMaxPDV, $blockRayon, $siteId, $data['parcours']);
        $data['urlWebstore'] = $this->getUrlWebstore($siteId, $lcdv6);
        $data['mentionsLegales'] = $this->getMentionsLegales($lcdv6);

        return $data;
    }

    /**
     * Return url to fetch the json data
     *
     * @param $blockVehicle
     * @param $blockMaxPDV
     * @param $blockRayon
     * @param $siteId
     * @param $parcours
     * @return string
     */
    private function generateUrlJsonPointDeVente($blockVehicle, $blockMaxPDV, $blockRayon, $siteId, $parcours)
    {
        $route = '';
        switch (intval($parcours)) {
            case Pf8WebstoreVehicleNeuf::POINT_DE_VENTE:
                $route = $this->router->generate(
                    'psa_ndp_api_webstore_vehicle_neuf_list_parcours_point_de_vente',
                    array(
                        'blockVehicle' => $blockVehicle,
                        'blockMaxPDV' => $blockMaxPDV,
                        'blockRayon' => $blockRayon,
                        'siteId' => $siteId,
                    ),
                    true
                );
                break;
            case Pf8WebstoreVehicleNeuf::REGIONAL:
                $route = $this->router->generate(
                    'psa_ndp_api_webstore_vehicle_neuf_list_parcours_regional',
                    array(
                        'blockVehicle' => $blockVehicle,
                        'blockRayon' => $blockRayon,
                        'siteId' => $siteId,
                    ),
                    true
                );
                break;
            case Pf8WebstoreVehicleNeuf::PRODUIT:
                $route = $this->router->generate(
                    'psa_ndp_api_webstore_vehicle_neuf_list_parcours_produit',
                    array(
                        'blockVehicle' => $blockVehicle,
                        'blockRayon' => $blockRayon,
                        'siteId' => $siteId,
                    ),
                    true
                );
                break;
            default:
                break;
        }

        return $route;
    }

    /**
     * @param int $siteId
     * @param string $languageCode
     * @param string $lcdv6
     */
    private function getBandeau($siteId, $languageCode, $lcdv6)
    {
        $languettesCommerciale = $this->psaModelSilhouetteSiteRepository->findOneBySiteIdLanguageCodeLcdv6($siteId, $languageCode, $lcdv6);
        $ordreAffichage = $this->psaModelConfigRepository->findOneBySiteIdAndLanguageCode($siteId, $languageCode)->getStripOrder();
        $bandeau[self::NOUVEAUTE] = $bandeau[self::OFFRE_SPECIALE] = $bandeau[self::SERIE_SPECIALE] = ''; //Le cas ou $nouveaute = $offreSpeciale = $serieSpeciale = false

        if ($languettesCommerciale->getNewCommercialStrip() == true) {
            $bandeau[self::NOUVEAUTE] = 'NDP_NEW';
        }
        if ($languettesCommerciale->getSpecialOfferCommercialStrip() == true) {
            $bandeau[self::OFFRE_SPECIALE] = 'NDP_SPECIAL_OFFER';
        }
        if ($languettesCommerciale->getSpecialSeriesCommercialStrip() == true) {
            $bandeau[self::SERIE_SPECIALE] = 'NDP_SPECIAL_SERIE';
        }

        $ordre = 1; //Par défaut
        if ($ordreAffichage != '') {
            $ordre = explode('#', $ordreAffichage);
            $ordre = $ordre[0];
        }

        return $bandeau[$ordre];
    }

    /**
     * @param string $siteId
     * @param string $lcdv6
     * @return mixed
     */
    private function getUrlWebstore($siteId, $lcdv6)
    {
        $url = $this->psaSitesEtWebservicesPsaRepository->findOneBySiteId($siteId)->getZoneUrlWebMobileWebstoreProduits();

        return str_replace('##LCDV4####GR_BODY_STYLE##', $lcdv6, $url);
    }

    /**
     * @param string $siteId
     * @return int
     */
    private function getParcoursWebstore($siteId)
    {
        return $this->psaSitesEtWebservicesPsaRepository->findOneBySiteId($siteId)->getZoneParcoursWebstore();
    }

    /**
     * @param string $lcdv6
     * @return mixed
     */
    private function getMentionsLegales($lcdv6)
    {
        return $this->webstore
            ->addFilter('ModelCode', substr($lcdv6, 0, 4)) //Exemple 1PIA
            ->addFilter('BodyStyleCode', substr($lcdv6, 4, 2)) //Exemple A5
            ->addFilter('MaxDistance', 0)
            ->addFilter('Latitude', 0)
            ->addFilter('Longitude', 0)
            ->addFilter('MinPrice', 0)
            ->addFilter('MaxPrice', 0)
            ->addFilter('LegalMentionType', 'Vehicles')
            ->getMentionsLegales();
    }
}
