<?php

namespace Itkg\Migration\Configurator;

use Itkg\Migration\Configurator\Slice\SliceConfiguratorFactory;
use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\Transaction\PsaShowroomEntityFactory;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use Itkg\Migration\XML\EntityParser\ShowroomXMLSlicePc23MurMediaParser;
use PSA\MigrationBundle\Entity\Page\PsaPageMultiZone;
use PSA\MigrationBundle\Entity\Page\PsaPageZone;

/**
 * Class configuratorService
 *
 * @package Itkg\Migration\XML\EntityParser
 */
class ConfiguratorService
{
    const DEFAULT_DIMENSION_PAGE = 'Dimension';


    /** @var array */
    protected $parameters;
    /** @var array */
    protected $parametersDefaultPages = [];
    /** @var array */
    protected $parametersWelcomePage = [];
    /** @var array */
    protected $parametersShowroomPage = [];
    /** @var array */
    protected $parametersMasterPage = [];
    /** @var array */
    protected $parametersShowroomPagePC60 = [];
    /** @var PsaShowroomEntityFactory */
    protected $entityFactory;
    /** @var SliceConfiguratorFactory */
    protected $configuratorFactory;

    /**
     * @param PsaShowroomEntityFactory $entityFactory
     * @param SliceConfiguratorFactory $configuratorFactory
     * @param array $parameters
     */
    public function __construct(PsaShowroomEntityFactory $entityFactory, SliceConfiguratorFactory $configuratorFactory, array $parameters)
    {
        $this->entityFactory = $entityFactory;
        $this->configuratorFactory = $configuratorFactory;
        $this->parameters = $parameters;

        if (isset($this->parameters["defaultPages"])) {
            $this->parametersDefaultPages = $this->parameters["defaultPages"];
        }
        if (isset($this->parameters["welcomePage"])) {
            $this->parametersWelcomePage = $this->parameters["welcomePage"];
        }
        if (isset($this->parameters["showroomPage"])) {
            $this->parametersShowroomPage = $this->parameters["showroomPage"];
        }
        if (isset($this->parameters["showroomPagePC60"])) {
            $this->parametersShowroomPagePC60 = $this->parameters["showroomPagePC60"];
        }
        if (isset($this->parameters["masterPage"])) {
            $this->parametersMasterPage = $this->parameters["masterPage"];
        }
    }

    /**
     * @param PsaPageShowroomMetadata $welcomePage
     * @param string $rootUrlKey
     * @param ShowroomUrlManager $urlManager
     * @param AddReportingMessageInterface $reporting
     *
     * @return array List of Default PsaPageShowroomMetadata pages created
     */
    public function createDefaultSubPages(
        PsaPageShowroomMetadata $welcomePage,
        $rootUrlKey,
        ShowroomUrlManager $urlManager,
        AddReportingMessageInterface $reporting
    )
    {
        $result = [];

        // Create for showroom not techno rubric
        if ($urlManager->getUrlType() !== ShowroomUrlManager::URL_TYPE_TECHNO_PUBLISHED &&
            $urlManager->getUrlType() !== ShowroomUrlManager::URL_TYPE_CONCEPT_PUBLISHED &&
            count($this->parametersDefaultPages) > 0)
        {
            // Create a new page
            $result = $this->createSubPages($welcomePage, $this->parametersDefaultPages, $rootUrlKey, $urlManager, $reporting);

            $reporting->addInfoMessage(
                sprintf(
                    "%d default pages with gabarit 'Master Page' has been created with some Sub Pages. You will need to move manually other sub pages imported into appropriate default pages created.",
                    count($this->parametersDefaultPages), $urlManager->getXmlUrl()
                )
            );
        }

        return $result;
    }

    /**
     * @param PsaPageShowroomMetadata $page
     * @param ShowroomUrlManager $urlManager
     * @param AddReportingMessageInterface $reporting
     */
    public function configurePageAndSubPages(
        PsaPageShowroomMetadata $page,
        ShowroomUrlManager $urlManager,
        AddReportingMessageInterface $reporting
    )
    {
        // Configure for showroom not techno rubric
        if ($urlManager->getUrlType() !== ShowroomUrlManager::URL_TYPE_TECHNO_PUBLISHED) {
            // Configure welcome page's slices except for Concept car showroom
            if ($page->getPageType() === PsaPageShowroomMetadata::PAGE_TYPE_HOMEPAGE &&
                $urlManager->getUrlType() !== ShowroomUrlManager::URL_TYPE_CONCEPT_PUBLISHED)
            {
                $this->createPageSlices($page, $this->parametersWelcomePage["slices"], $urlManager, $reporting);
            }
            $this->configureGabaritPage($page, $urlManager, $reporting);

            foreach($page->getSubPages() as $subPage) {
                $this->configurePageAndSubPages($subPage, $urlManager, $reporting);
            }
        }
    }

    /**
     * @param PsaPageShowroomMetadata $page
     * @param array $subPageParameters
     * @param string $rootUrlKey
     * @param ShowroomUrlManager $urlManager
     * @param AddReportingMessageInterface $reporting
     *
     * @return array List of PsaPageShowroomMetadata pages created
     */
    private function createSubPages(
        PsaPageShowroomMetadata $page,
        array $subPageParameters,
        $rootUrlKey,
        ShowroomUrlManager $urlManager,
        AddReportingMessageInterface $reporting
    )
    {
        $result = [];

        foreach($subPageParameters as $pageId => $pageParameters) {
            $pageName = $pageParameters["title"];
            $subPage = $this->entityFactory->createDraftPageWithMetadata(
                $urlManager->getSite(),
                $urlManager->getLanguage(),
                $urlManager->getUser(),
                $this->getGabarit($pageParameters["gabarit"]),
                PsaPageShowroomMetadata::PAGE_TYPE_SUB_PAGE
            );
            $subPage->setPageUrlKey($urlManager->slug($pageName));
            $draftVersion = $subPage->getPage()->getDraftVersion();
            $draftVersion->setPageTitleBo($pageName);
            $draftVersion->setPageTitle($pageName);
            $draftVersion->setPageClearUrl($urlManager->generateSubPagePath('menu-' . $pageName, $rootUrlKey, ''));

            $page->addSubPage($subPage);
            $result[$pageId] = $subPage;

            if (isset($pageParameters["subPages"])) {
                $subSubPageResult = $this->createSubPages($subPage, $pageParameters["subPages"], $rootUrlKey, $urlManager, $reporting);
                $result = array_merge($result, $subSubPageResult);
            }
            // Add pc83 only for showroom not concept car
            if ($urlManager->getUrlType() !== ShowroomUrlManager::URL_TYPE_CONCEPT_PUBLISHED &&
                isset($pageParameters["slices"]))
            {
                $this->createPageSlices($subPage, $pageParameters["slices"], $urlManager, $reporting);
            }
        }

        return $result;
    }


    /**
     * @param ShowroomUrlManager $urlManager
     * @param PsaPageShowroomMetadata $page
     * @param AddReportingMessageInterface $reporting
     */
    private function configureGabaritPage(
        PsaPageShowroomMetadata $page,
        ShowroomUrlManager $urlManager,
        AddReportingMessageInterface $reporting
    )
    {
        switch ($page->getPage()->getDraftVersion()->getTemplateId())
        {
            case PsaShowroomEntityFactory::TEMPLATE_PAGE_ID_GABARIT_SHOWROOM_G27:
                $this->configureShowroomPage($page, $urlManager, $reporting);
                break;
            case PsaShowroomEntityFactory::TEMPLATE_PAGE_ID_GABARIT_MASTER_PAGE_G02:
                $this->configureMasterPage($page, $urlManager, $reporting);
                break;
        }
    }

    /**
     * @param ShowroomUrlManager $urlManager
     * @param PsaPageShowroomMetadata $page
     * @param AddReportingMessageInterface $reporting
     */
    private function configureShowroomPage(
        PsaPageShowroomMetadata $page,
        ShowroomUrlManager $urlManager,
        AddReportingMessageInterface $reporting
    )
    {
        if ($page->getPage()->getDraftVersion()->getTemplateId() === PsaShowroomEntityFactory::TEMPLATE_PAGE_ID_GABARIT_SHOWROOM_G27) {
            // To configure for all showroom pages
            $this->createPageSlices($page, $this->parametersShowroomPage["slices"], $urlManager, $reporting, true);
            
        }
    }


    /**
     * @param ShowroomUrlManager $urlManager
     * @param PsaPageShowroomMetadata $page
     * @param AddReportingMessageInterface $reporting
     */
    private function configureMasterPage(
        PsaPageShowroomMetadata $page,
        ShowroomUrlManager $urlManager,
        AddReportingMessageInterface $reporting
    )
    {
        if ($page->getPage()->getDraftVersion()->getTemplateId() === PsaShowroomEntityFactory::TEMPLATE_PAGE_ID_GABARIT_MASTER_PAGE_G02) {
            // To configure for all showroom pages
            $this->createPageSlices($page, $this->parametersMasterPage["slices"], $urlManager, $reporting);

            //Todo check evolution specs if need to configure new slice
        }
    }

    /**
     * @param PsaPageShowroomMetadata $page
     * @param array $sliceParameters
     * @param ShowroomUrlManager $urlManager
     * @param AddReportingMessageInterface $reporting
     * @param bool $checkExisting
     */
    public function createPageSlices(
        PsaPageShowroomMetadata $page,
        array $sliceParameters,
        ShowroomUrlManager $urlManager,
        AddReportingMessageInterface $reporting,
        $checkExisting = false
    )
    {
        $pageVersion = $page->getPage()->getDraftVersion();

        foreach ($sliceParameters as $class) {
            $addBlock = true;
            // Configure a new block
            $configurator = $this->configuratorFactory->createSliceConfigurator($class);
            $block = $configurator->create($urlManager, $page, $reporting);

            // Add only if block is not already existing and configured
            if ($checkExisting) {
                if ($block instanceof PsaPageMultiZone) {
                    $zoneId = $this->entityFactory->getBlockIdMapper()->getDynamicBlockZoneId($configurator->getName());
                    $addBlock = !$page->containDynamicSlice($zoneId);
                } else if ($block instanceof PsaPageZone) {
                    $zoneTemplateId = $this->entityFactory->getBlockIdMapper()->getStaticBlockZoneTemplateIdForPageVersion(
                        $page->getPage()->getDraftVersion(),
                        $configurator->getName()
                    );
                    $addBlock = !$page->containStaticSlice($zoneTemplateId);
                }
            }
            if ($addBlock) {
                if ($block instanceof PsaPageMultiZone) {
                    $pageVersion->addDynamicPageBlock($block);
                } else if ($block instanceof PsaPageZone) {
                    $pageVersion->addBlock($block);
                }
            }
        }

    }



    /**
     * @param $type
     * @return int|null
     */
    private function getGabarit($type)
    {
        switch ($type) {
            case "MASTER_PAGE":
                return PsaShowroomEntityFactory::TEMPLATE_PAGE_ID_GABARIT_MASTER_PAGE_G02;
            case "SHOWROOM":
                return PsaShowroomEntityFactory::TEMPLATE_PAGE_ID_GABARIT_SHOWROOM_G27;

        }

        return null;
    }
}
