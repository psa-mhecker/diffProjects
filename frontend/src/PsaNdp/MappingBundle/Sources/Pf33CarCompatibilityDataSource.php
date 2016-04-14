<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Repository\PsaServiceConnectFinitionGroupingRepository;
use PsaNdp\MappingBundle\Entity\PsaServiceConnectFinitionGrouping;
use PsaNdp\MappingBundle\Repository\PsaModelSiteRepository;
use PsaNdp\WebserviceConsumerBundle\Webservices\ConfigurationEngineSelect;
use Symfony\Component\HttpFoundation\Request;
use PsaNdp\MappingBundle\Object\Block\Pf33CarCompatibility;
use PsaNdp\MappingBundle\Utils\ModelSiteUtils;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use Symfony\Component\Routing\RouterInterface;

/**
 * Data source
 */
class Pf33CarCompatibilityDataSource extends AbstractDataSource
{

    /**
     * @var PsaPageZoneConfigurableInterface
     */
    private $block;

    /**
     * @var array
     */
    private $data;

    /**
     * @var bool
     */
    private $isMobile;

    /**
     * @var PsaModelSiteRepository
     */
    private $modelSiteRepository;

    /**
     * @var PsaServiceConnectFinitionGroupingRepository
     */
    private $serviceConnectFinitionGroupingRepository;

    /**
     * @var ModelSiteUtils
     */
    protected $modelSiteUtils;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var ConfigurationEngineSelect
     */
    protected $configurationEngineSelect;


    /**
     * @param PsaServiceConnectFinitionGroupingRepository $serviceConnectFinitionGroupingRepository
     * @param ModelSiteUtils                              $modelSiteUtils
     * @param PsaModelSiteRepository                      $modelSiteRepository
     * @param RouterInterface                             $router
     * @param ConfigurationEngineSelect                   $configurationEngineSelect
     */
    public function __construct(
        PsaServiceConnectFinitionGroupingRepository $serviceConnectFinitionGroupingRepository,
        ModelSiteUtils $modelSiteUtils,
        PsaModelSiteRepository $modelSiteRepository,
        RouterInterface $router,
        ConfigurationEngineSelect $configurationEngineSelect
    )
    {
        $this->serviceConnectFinitionGroupingRepository = $serviceConnectFinitionGroupingRepository;
        $this->modelSiteRepository = $modelSiteRepository;
        $this->modelSiteUtils = $modelSiteUtils;
        $this->router = $router;
        $this->configurationEngineSelect = $configurationEngineSelect;
    }

    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input BlockInterface and current url Request
     *
     * @param ReadBlockInterface $block
     * @param Request $request Current url request displaying th block
     * @param bool $isMobile Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {

        $this->modelSiteUtils->setTranslator($this->translator, $this->domain, $this->locale);
        /** @var PsaPageZoneConfigurableInterface block */
        $this->block = $block;
        $this->isMobile = $isMobile;
        $site = $this->block->getPage()->getSite();
        $language = $this->block->getLangue();


        $this->data = ['title' => $this->block->getZoneTitre(),
            'subtitle' => $this->block->getZoneTitre2(),
        ];
        $this->getCarousel($site, $language);
        $this->data['block'] = $this->block;


        return $this->data;
    }

    /**
     * @param PsaSite $site
     * @param PsaLanguage $language
     *
     * @throws \Exception
     */
    private function getCarousel(PsaSite $site, PsaLanguage $language)
    {
        $carousel = null;
        // on récupềre les modeles qui ont
        if (Pf33CarCompatibility::CONNECTED_SERVICES == $this->block->getZoneAttribut2()) {
            $serviceIds = [$this->block->getZoneCriteriaId()];
        }

        if (Pf33CarCompatibility::BENEFICE == $this->block->getZoneAttribut2()) {
            $serviceIds = explode('#', $this->block->getZoneParameters());
        }
        $this->data['urlJson'] = $this->getUrlJson($site->getSiteId(), $language->getLangueCode(), $serviceIds);

        $results = $this->serviceConnectFinitionGroupingRepository->findFinitionByConnectServiceIdsAndSiteAndLanguage($serviceIds, $site->getSiteId(), $language->getLangueId());

        $carousel = [];
        foreach ($results as $result) {
            $lcdv4 = $result->getLcdv4();
            if (!isset($carousel[$lcdv4])) {
                $carousel[$lcdv4] = [];
            }
            $carousel[$lcdv4][] = $result;
        }
        foreach ($carousel as $model => $servicesGrouping) {

            $modelSite = $this->modelSiteRepository->findOneByModelLanguageAndSite($model, $site, $language);


            if ($modelSite) {
                /** @var PsaServiceConnectFinitionGrouping $first */
                $first = current($servicesGrouping);

                $modelInfos = $this->modelSiteUtils->generateModelData($modelSite, $this->isMobile);
                if ($this->isMobile) {
                    $modelInfos['list'] = $this->getFinitionsForServices($model, $servicesGrouping);
                    $modelInfos['description'] = $first->getConnectfinition()->getLegalNotice();
                }
                $this->data['carousel'][$model] = $modelInfos;
            }
        }

    }

    protected function getFinitionsForServices($lcdv4, array $servicesGrouping)
    {
        // comme il y a qu'un seul service en mode mobile on as pas besoin de grouper par service
        $finitions = $this->configurationEngineSelect->getModelByLCDV4($lcdv4);
        $used = [];
        /** @var PsaServiceConnectFinitionGrouping $scfg */
        foreach ($servicesGrouping as $scfg) {
            if (isset($finitions[$scfg->getFinitionGrouping()]) && !isset($used[$scfg->getFinitionGrouping()])) {
                $used[$scfg->getFinitionGrouping()] = array('title' => $finitions[$scfg->getFinitionGrouping()]['FINISHING_LABEL']);
            }
        }

        return $used;
    }

    /**
     * @param string $siteId
     * @param string $languageCode
     *
     * @return string
     */
    protected function getUrlJson($siteId, $languageCode, $serviceIds)
    {
        // route /{siteId}/{languageCode}/{connectServiceId}

        $params = [
            'siteId' => $siteId,
            'languageCode' => $languageCode,
            'detailed' => $this->block->getZoneAttribut(), // mode detaillé ou pas
            'connectServiceIds' => implode('-', $serviceIds),
            'full' => (int)(Pf33CarCompatibility::BENEFICE == $this->block->getZoneAttribut2())
        ];


        return $this->router->generate('psa_ndp_api_car_compatibility_result', $params);
    }


}
