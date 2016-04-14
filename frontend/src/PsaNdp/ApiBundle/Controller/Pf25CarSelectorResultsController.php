<?php

namespace PsaNdp\ApiBundle\Controller;

use PSA\MigrationBundle\Entity\Page\PsaPage;
use PsaNdp\MappingBundle\Entity\Vehicle\PsaModelConfig;
use PsaNdp\MappingBundle\Utils\SiteConfiguration;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use OpenOrchestra\BaseApiBundle\Controller\Annotation as Api;

/**
 * Class Pf25CarSelectorResultsController
 * @package PsaNdp\ApiBundle\Controller
 * @Config\Route("car_selector")
 */
class Pf25CarSelectorResultsController extends Controller
{
    private $block;
    private $localVehicleCategoryRepository;

    /**
     * @Config\Route("/{siteId}/{languageCode}/{blockId}/{pageVersion}", name="psa_ndp_api_car_selector")
     * @Config\Method({"GET"})
     *
     * @Api\Serialize()
     *
     * @throw RuntimeException
     *
     * @param int $siteId
     * @param string $languageCode
     * @param string $blockId
     * @param int $pageVersion
     *
     * @return FacadeInterface
     */
    public function indexAction($siteId, $languageCode, $blockId, $pageVersion)
    {
        $rangeManagerService = $this->container->get('range_manager');

        $carSelectorResultsService = $this->container->get('psa_ndp_api.pf25_car_selector_results');
        $vehicleModelConfigService = $this->get('psa_ndp.repository.vehicle.model_config');
        $this->get('psa_ndp_mapping.manager.price_manager')->setDomain($siteId)->setLocale($languageCode);
        /**
         * @var SiteConfiguration $siteConfiguration
         */
        $siteConfiguration = $this->container->get('site_configuration');

        //fetch the calling block
        $this->block = $this->get('psa_ndp_page_area_block_reference_repository')->findOneBy(
            ['permanentId' => $blockId, 'pageVersionNumber' => $pageVersion]
        )->getBlock();


        $vehiclesToShow = explode('#',$this->block->getZoneParameters());

        $siteConfiguration->setSiteId($siteId);
        $siteConfiguration->loadConfiguration();

        $models = $this->container->get(
            'psa_ndp.repository.vehicle.model_silhouette_site'
        )->findBySiteIdAndLanguageCode($siteId, $languageCode);



        //filter only configured models
        $filtredModels = array_values(array_filter($models, function($model) use($vehiclesToShow){
            return (in_array(
                sprintf('%s-%s',$model->getLcdv6(),$model->getGroupingCode()),
                $vehiclesToShow
            ));
        }));

        $modelOrdered = array();
        foreach ($vehiclesToShow as $value) {
            foreach ($filtredModels as $filtredModel) {
                if (sprintf('%s-%s', $filtredModel->getLcdv6(), $filtredModel->getGroupingCode()) === $value) {
                    $modelOrdered[] = $filtredModel;
                }
            }
        }

        /**
         * @var  PsaModelConfig $vehicleModelConfig
         */
        $vehicleModelConfig = $vehicleModelConfigService->findOneBySiteIdAndLanguageCode($siteId, $languageCode);

        $countModels = count($modelOrdered);
        $results = array();
        $stripOrder = $vehicleModelConfig->getStripOrder();


        $results['active_filters'] = $this->activeFilters();
        $results['country_code'] = $siteConfiguration->getSite()->getCountryCode();
        $results['language_code'] = $this->block->getPage()->getLangue()->getLangueCode();
        $results['culture'] = strtolower(sprintf('%s-%s',$results['language_code'],$results['country_code']));
        $results['strip_order'] = strtolower(sprintf('%s-%s',$results['language_code'],$results['country_code']));

        // teste si le webservice est activer en BO todo voir si il n'y pas d'erreur dans les transformers
//        $engineSelect = $this->container->get('configuration_engine_select');
        $pageRepositoryService =$this->container->get('open_orchestra_model.repository.node');

        if ($rangeManagerService->getWebserviceStatus($siteId, $rangeManagerService->getName())) {
            for ($counter = 0; $counter < $countModels; $counter++) {
                $lcdv6 = $modelOrdered[$counter]->getLcdv6();
                $grBodyStyle = $modelOrdered[$counter]->getGroupingCode();
                $cheapestVersion = $rangeManagerService
                    ->getCheapestByLcdv6AndGrBodyStyle(
                        $lcdv6,
                        $grBodyStyle,
                        $results['country_code'],
                        $languageCode
                    );


                /**
                 * @var PsaPage $showroomPage
                 */
                $showroomPage = $pageRepositoryService->findShowroomWelcomePageUsingIds(
                    sprintf('%s-%s',$cheapestVersion['LCDV6'],$cheapestVersion['GrBodyStyle']['Code']),
                    $siteId,
                    $this->block->getLangueId()
                );

                if($showroomPage !== null){
                    $modelOrdered[$counter]->discover = $showroomPage->getUrl();
                }

                if (!empty($cheapestVersion)) {
                    $modelOrdered[$counter]->cheapestVersion = $cheapestVersion;
                    $modelOrdered[$counter]->cheapestVersionId = $cheapestVersion['LCDV16'];
                }

                if(!empty($stripOrder)){
                    $modelOrdered[$counter]->setStripsOrder($stripOrder);
                }

            }
        }

        $results['models'] = $modelOrdered;

        $results['showrooms'] = $carSelectorResultsService->getShowrooms();

        return $this->container->get('open_orchestra_api.transformer_manager')
            ->get('pf25_result_collection')
            ->transform($results);

    }


    /**
     * fetch active filters from admin panel
     * ordered as they should
     *
     * @return array
     */
    private function activeFilters()
    {
        $activeFiltersRawString = $this->block->getZoneLabel();
        $activeFilters = explode('#', $activeFiltersRawString);

        return $activeFilters;
    }

    /**
     * Fetch active vehicle categories
     * for the current couple site/language
     *
     * @return array
     */
    private function activeVehicleCategories()
    {
        $activeVehicleCategories = null;
        $vehicleCategoriesIdsRawString = $this->block->getZoneParameters();
        $vehicleCategoriesIds = explode('#', $vehicleCategoriesIdsRawString);

        if ( ! empty($vehicleCategoriesIds)) {
            $activeVehicleCategories = $this->localVehicleCategoryRepository->findByIdsSiteAndLanguageCode(
                $vehicleCategoriesIds,
                $this->block->getPage()->getSite()->getId(),
                $this->block->getPage()->getLangue()->getLangueCode()
            );
        }

        return $activeVehicleCategories;
    }
}
