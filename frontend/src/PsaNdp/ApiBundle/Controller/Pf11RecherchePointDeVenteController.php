<?php

namespace PsaNdp\ApiBundle\Controller;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use OpenOrchestra\BaseApiBundle\Controller\Annotation as Api;
use Symfony\Component\HttpFoundation\Request;
use PSA\MigrationBundle\Entity\Page\PsaPageAreaBlockReference;
use PsaNdp\WebserviceConsumerBundle\Webservices\AnnuairePointDeVente;
use PsaNdp\MappingBundle\Transformers\Pf11RecherchePointDeVenteDataTransformer;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class Pf11RecherchePointDeVenteController.
 *
 * @Config\Route("search-pointofsale")
 */
class Pf11RecherchePointDeVenteController extends Controller
{
    /**
     * @var array
     */
    private $searchConfig;

    /**
     * @var array
     */
    private $filters = [];

    /**
     * @var array
     */
    private $ignoredKeys = [
        'city',
    ];
    /**
     * @var bool
     */
    protected $apv;

    /**
     * @param Request $request
     * @param string  $langueCode
     * @param int     $siteId
     * @param $countryCode
     * @param $filterMode
     * @param $resultsMode
     * @param $maxResults
     * @param $radius
     * @param $minResultsPointOfSale
     * @param $minResultsNewVehicleDistributor
     * @param $enableSearchByName
     *
     * @return FacadeInterface
     * @Config\Route("/{langueCode}/{siteId}/{countryCode}/{filterMode}/{resultsMode}/{maxResults}/{radius}/{minResultsPointOfSale}/{minResultsNewVehicleDistributor}/{enableSearchByName}", requirements={"siteId"="\d+"}, name="psa_ndp_api_search_sales_points_list")
     * @Config\Method({"GET"})
     *
     * @Api\Serialize()
     *
     * @throw RuntimeException
     */
    public function showAction(
        Request $request,
        $langueCode,
        $siteId,
        $countryCode,
        $filterMode,
        $resultsMode,
        $maxResults,
        $radius,
        $minResultsPointOfSale,
        $minResultsNewVehicleDistributor,
        $enableSearchByName
    ) {

        /* @var PsaPageAreaBlockReference $areaBlock */
        $result = new JsonResponse(['error' => 1], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);

        $this->searchConfig['Culture'] = $langueCode;
        $this->searchConfig['Country'] = $countryCode;
        $this->searchConfig['ResultMax'] = $maxResults;

            //RG_Fo_PF11_20
       if ($filterMode == Pf11RecherchePointDeVenteDataTransformer::FILTER_BY_RADIUS) {
           $this->searchConfig['SearchType'] = AnnuairePointDeVente::SEARCH_TYPE_STANDARD;
       }

        if ($filterMode == Pf11RecherchePointDeVenteDataTransformer::FILTER_BY_PDV_PDN) {
            $this->searchConfig['MinPDV'] = $minResultsPointOfSale;
            $this->searchConfig['MinDVN'] = $minResultsNewVehicleDistributor;
            $this->searchConfig['SearchType'] = AnnuairePointDeVente::SEARCH_TYPE_SPIRAL;
        }

        $this->searchConfig['RMax'] = $radius;

        $this->apv = ($resultsMode === Pf11RecherchePointDeVenteDataTransformer::MODE_SEARCH_PDV);
        if ($this->apv) {
            // parcours APV
           //Attente Jira client https://jira-projets.mpsa.com/VTIS/browse/NDP-400
           // $this->searchConfig['isInPromo']= 1
        }

        $enableSearchByName = (bool) $enableSearchByName;
       //recherche par Dealor Name il faut initialiser Long et Lat en vide sinon le WS retourne une erreur
       if ($request->query->get('query') && $enableSearchByName) {
           $this->searchConfig['Name'] = $request->query->get('query');
           $this->searchConfig['Longitude'] = '';
           $this->searchConfig['Latitude'] = '';
       }

        $this->searchConfig['Details'] = 'max';

        try {
            $pdvs = $this->getPointsDeVente($request->query->all());
        } catch (\Exception $e) {
            $pdvs = [];
        }

        if (!empty($pdvs)) {

            //get services by site & language

            $services = $this->getServices($siteId, $langueCode);

            //fetch promotions
            //Deactivate edalers lookup for current sprint(30/11-11/12)
            if (true == false) {
                foreach ($pdvs['DealersFull'] as $key => $pdv) {
                    $pdv['promotions'] = $this->get('edealer')->getFavoriteOffers(
                        $pdv['SiteGeo'],
                        $langueCode.'-'.$countryCode
                    );
                    $pdvs['DealersFull'][$key] = $pdv;
                }
            }
            $result = array();
            if ($pdvs) {
                $result = $this->container
                    ->get('open_orchestra_api.transformer_manager')
                    ->get('dealer_collection')
                    ->setRouter($this->get('router'))
                    ->setFilters($this->filters)
                    ->setStart($request->query->get('departure'))
                    ->setLangueCode($langueCode)
                    ->setDomain($siteId)
                    ->setLocale($langueCode)
                    ->setSiteId($siteId)
                    ->setMinimumDVN($request->get('minResultsNewVehicleDistributor'))
                    ->setOverriddenServices($services)
                    ->transform($pdvs);
            }
        }

        return $result;
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    private function getPointsDeVente(array $parameters)
    {
        $pdvs = [];
        /* @var AnnuairePointDeVente $service */
        try {
            $params = $this->parseParameters($parameters);

            $service = $this->get('annuaire_pdv');
            foreach ($params as $param => $value) {
                $service->addParameter($param, $value);
            }
            foreach ($this->searchConfig as $param => $value) {
                $service->addParameter($param, $value);
            }

            $pdvs = $service->getDealerList();
        } catch (\Exception $e) {
            /* don't know  what to do */
            die($e->getMessage());
        }

        return $pdvs;
    }

    private function parseParameters(array $parameters)
    {
        $params = [];
        foreach ($parameters as $key => $value) {
            switch ($key) {
                case 'departure':
                    list($params['Latitude'], $params['Longitude']) = explode(',', $value);
                    break;
                case 'filter':
                    $params['Criterias'] = '';
                    $temp = [];
                    if (!$this->apv) {
                        foreach ($value as $criteria => $etat) {
                            if ($etat && !in_array($criteria, $this->ignoredKeys)) {
                                $temp[] = $criteria;
                                $this->filters[] = $criteria;
                            }
                        }
                    }
                    $params['Criterias'] = implode(',', $temp);
                    if (empty($params['Criterias'])) {
                        unset($params['Criterias']);
                    }

                    break;
                default:
            }
        }

        return $params;
    }

    /**
     * @param $siteId
     * @param $languageCode
     *
     * @return array
     */
    private function getServices($siteId, $languageCode)
    {
        $services = array();
        $dealerLocatorServices = $this->get('psa_ndp_dealer_service_repository');
        $languageId = $this->get('psa_ndp_language_repository')->getIdByCode($languageCode);

        if ($siteId && $languageId) {
            $servicesRaw = $dealerLocatorServices->findBy(
                array(
                    'site' => $siteId,
                    'langue' => $languageId,
                    'serviceActive' => true,
                ),
                array('serviceOrder' => 'ASC')
            );

            if (!empty($servicesRaw)) {
                foreach ($servicesRaw as $service) {
                    $services[$service->getServiceCode()] = $service;
                }
            }
        }

        return $services;
    }
}
