<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Page\PsaPage;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Repository\PsaPageRepository;
use PsaNdp\MappingBundle\Entity\Vehicle\PsaModelSilhouetteSite;
use PsaNdp\MappingBundle\Repository\Vehicle\PsaModelConfigRepository;
use PsaNdp\MappingBundle\Repository\Vehicle\PsaModelSilhouetteSiteRepository;
use PsaNdp\MappingBundle\Utils\SiteConfiguration;
use PsaNdp\WebserviceConsumerBundle\Webservices\ConfigurationEngineSelect;
use PsaNdp\WebserviceConsumerBundle\Webservices\RangeManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Data source for Pf27CarPicker block
 */
class Pf27CarPickerDataSource extends AbstractDataSource
{
    /**
     * @var RangeManager
     */
    protected $rangeManager;

    /**
     * @var PsaModelConfigRepository
     */
    protected $modelConfig;

    /**
     * @var SiteConfiguration
     */
    protected $siteConfiguration;

    /**
     * @var PsaModelSilhouetteSiteRepository
     */
    protected $modelSilhouetteSiteRepository;

    /**
     * @var ConfigurationEngineSelect
     */
    protected $configurationEngineSelect;

    /**
     * @var PsaPageRepository
     */
    protected $pageRepository;


    public function __construct(
        RangeManager $rangeManager,
        PsaModelConfigRepository $modelConfig,
        SiteConfiguration $siteConfiguration,
        PsaModelSilhouetteSiteRepository $modelSilhouetteSiteRepository,
        ConfigurationEngineSelect $configurationEngineSelect,
        PsaPageRepository $pageRepository
    )
    {
        $this->rangeManager = $rangeManager;
        $this->modelConfig = $modelConfig;
        $this->siteConfiguration = $siteConfiguration;
        $this->modelSilhouetteSiteRepository = $modelSilhouetteSiteRepository;
        $this->configurationEngineSelect = $configurationEngineSelect;
        $this->pageRepository = $pageRepository;
    }

    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input BlockInterface and current url Request
     *
     * @param ReadBlockInterface $block
     * @param Request        $request  Current url request displaying th block
     * @param bool           $isMobile Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        $data['block'] = $block;
        /** @var  PsaPageZoneConfigurableInterface $block */
        $siteId = $block->getPage()->getSiteId();
        $countryCode = $this->siteConfiguration->getSite()->getCountryCode();
        $languageCode = $block->getPage()->getLangue()->getLangueCode();

        $vehiclesToShow = array();
        foreach ($block->getMultis() as $multi)
        {
            $vehiclesToShow[] = $multi->getPageZoneMultiValue();
            $data['ctaList'][$multi->getPageZoneMultiValue()] = $multi->getCtaReferences();
        }

        $this->siteConfiguration->setSiteId($siteId);
        $this->siteConfiguration->loadConfiguration();
        $vehicleModelConfig = $this->modelConfig->findOneBySiteIdAndLanguageCode($siteId, $languageCode);

        $models = $this->modelSilhouetteSiteRepository->findBySiteIdAndLanguageCode($siteId, $languageCode);

        //filter only configured models
        $filtredModels = array_values(array_filter($models, function(PsaModelSilhouetteSite $model) use($vehiclesToShow){
            return (in_array(
                sprintf('%s-%s', $model->getLcdv6(), $model->getGroupingCode()),
                $vehiclesToShow
            ));
        }));
        $modelOrdered = [];
        foreach ($vehiclesToShow as $value) {
            /** @var PsaModelSilhouetteSite $filtredModel */
            foreach ($filtredModels as $filtredModel) {
                if (sprintf('%s-%s', $filtredModel->getLcdv6(), $filtredModel->getGroupingCode()) === $value) {
                    $modelOrdered[] = $filtredModel;
                }
            }
        }
        $stripOrder = $vehicleModelConfig->getStripOrder();
        $countModels = count($modelOrdered);

        for ($counter = 0; $counter < $countModels; $counter++) {
            /** @var PsaModelSilhouetteSite $model */
            $model = $modelOrdered[$counter];
            $lcdv6 = $model->getLcdv6();
            $grBodyStyle = $model->getGroupingCode();
            $model->setStripsOrder($stripOrder);
            
            $cheapestVersion = $this->rangeManager
                ->getCheapestByLcdv6AndGrBodyStyle(
                    $lcdv6,
                    $grBodyStyle,
                    $countryCode,
                    $languageCode
                );

            /**
             * @var PsaPage $showroomPage
             */
            $showroomPage = $this->pageRepository->findShowroomWelcomePageUsingIds(
                sprintf('%s-%s', $cheapestVersion['LCDV6'], $cheapestVersion['GrBodyStyle']['Code']),
                $siteId,
                $block->getLangueId()
            );

            if($showroomPage !== null){
                $model->url = $showroomPage->getUrl();
            }

            if (!empty($cheapestVersion)) {
                $model->cheapestVersion = $cheapestVersion;
                $model->cheapestVersionId = $cheapestVersion['LCDV16'];
            }

        }

        $data['models'] = $modelOrdered;

        return $data;
    }
}
