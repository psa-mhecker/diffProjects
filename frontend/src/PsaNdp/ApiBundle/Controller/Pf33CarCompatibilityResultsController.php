<?php

namespace PsaNdp\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use OpenOrchestra\BaseApiBundle\Controller\Annotation as Api;
use Symfony\Component\HttpFoundation\Request;
use PsaNdp\MappingBundle\Entity\PsaServiceConnectFinitionGrouping;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;

/**
 * Class Pf33CarCompatibilityResultsController
 * @package PsaNdp\ApiBundle\Controller
 * @Config\Route("car_compatibility")
 */
class Pf33CarCompatibilityResultsController extends Controller
{
    /**
     * @param int    $siteId
     * @param string $languageCode
     * @param int    $detailed
     * @param string $connectServiceIds
     * @param int    $full
     *
     * @Config\Route("/{siteId}/{languageCode}/{detailed}/{connectServiceIds}/{full}", name="psa_ndp_api_car_compatibility_result")
     * @Config\Method({"GET"})
     *
     * @Api\Serialize()
     *
     * @throw RuntimeException
     *
     * @return FacadeInterface
     */
    public function indexAction($siteId, $languageCode, $detailed , $connectServiceIds, $full)
    {

        // récupération de la langue et du site
        $langueRepository = $this->container->get('psa_ndp_language_repository');
        $langue = $langueRepository->findOneBy(array('langueCode' => $languageCode));
        $siteRepository = $this->container->get('psa_ndp_site_repository');
        $site = $siteRepository->findOneById($siteId);

        // recherche des modèles et des services associé , sauf si
        $data['models'] = $this->getModels(explode('-',$connectServiceIds),  $site, $langue );
        // on met en forme pour le json
        $result = $this->container
            ->get('open_orchestra_api.transformer_manager')
            ->get('pf33_result_collection')
            ->setDetailed($detailed)
            ->setFull($full)
            ->transform($data);
        return $result;
    }

    /**
     * @param array       $connectServiceIds
     * @param PsaSite     $site
     * @param PsaLanguage $language
     *
     * @return array
     */
    protected function getModels(array $connectServiceIds, PsaSite $site, PsaLanguage $language )
    {
        $return = [];
        // récupération des services par modèles
        $serviceConnectFinitionGroupings = $this->container->get(
            'psa_ndp_service_connect_finition_grouping_repository'
        )->findFinitionByConnectServiceIdsAndSiteAndLanguage($connectServiceIds, $site->getSiteId(), $language->getLangueId());

        /** @var PsaServiceConnectFinitionGrouping $scfg */
        foreach ($serviceConnectFinitionGroupings as $scfg) {
            $lcdv4 = $scfg->getLcdv4();
            $serviceId = $scfg->getConnectedService()->getId();
            // on groupe les services par lcdv4
            if (!isset($return[$lcdv4])) {
                $modelSite = $this->get('psa_ndp_model_site_repository')->findOneByModelLanguageAndSite($lcdv4, $site, $language);
                $return[$lcdv4]['model'] = $modelSite;
                // recherche des finitions pour le modele
                $return[$lcdv4]['finitions'] = $this->get('configuration_engine_select')->getModelByLCDV4($lcdv4);
                $return[$lcdv4]['scfgs'] = [];
            }
            // on groupe les services par services et finitions
            if (!isset($return[$lcdv4]['scfgs'][$serviceId])) {
                $return[$lcdv4]['scfgs'][$serviceId] = ['list' => [], 'service' => $scfg->getConnectedService()];
            }
            $return[$lcdv4]['scfgs'][$serviceId]['list'][$scfg->getFinitionGrouping()] = $scfg;

        }

        return $return;
    }
}
