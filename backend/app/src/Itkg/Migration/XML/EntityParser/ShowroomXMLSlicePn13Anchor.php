<?php


namespace Itkg\Migration\XML\EntityParser;

use DOMXPath;
use DOMElement;
use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\Transaction\PsaShowroomEntityFactory;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use Itkg\Migration\XML\XPathQueryHelper;
use Itkg\Utils\PsaDatabaseBlockIdMapper;
use PSA\MigrationBundle\Entity\Page\PsaPageMultiZone;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneMulti;
use PsaNdp\MappingBundle\Utils\SiteConfiguration;
use PsaNdp\WebserviceConsumerBundle\Webservices\ConfigurationEngineSelect;
use Doctrine\Common\Collections\Collection;

/**
 * Class ShowroomXMLPageParser
 * @package Itkg\Migration\XML\EntityParser
 */
class ShowroomXMLSlicePn13Anchor extends AbstractShowroomXMLSliceParser
{
    const RESPONSIVE_TYPE_REVEAL = 'reveal';
    const TITLE_TAG_WIDGET_COUNT = '{widget_count}';
    const TITLE_TAG_MODEL = '{model}';
    const TITLE_TAG_BODY = '{body}';

    /** @var ConfigurationEngineSelect */
    protected $wsConfigEngine;
    /** @var PsaDatabaseBlockIdMapper */
    protected $blockIdMapper;
    /** @var array */
    protected $zoneIdsToIgnore;
    /** @var SiteConfiguration */
    protected $siteConfiguration;

    /** @var PsaPageMultiZone */
    private $uspBlock;
    /** @var ShowroomUrlManager */
    private $urlManager;
    /** @var PsaPageShowroomMetadata */
    private $currentPage;
    /** @var DOMXPath */
    private $rootXPath;
    /** @var AddReportingMessageInterface */
    private $reporting;


    /**
     * @param PsaShowroomEntityFactory          $entityFactory
     * @param XPathQueryHelper                  $xPathQuery
     * @param ShowroomXMLEntityParserFactory    $xmlEntityParserFactory
     * @param string|null                       $nodeType
     * @param ConfigurationEngineSelect         $wsConfigEngine
     * @param PsaDatabaseBlockIdMapper          $blockIdMapper
     * @param SiteConfiguration                 $siteConfiguration
     */
    public function __construct(
        PsaShowroomEntityFactory $entityFactory,
        XPathQueryHelper $xPathQuery,
        ShowroomXMLEntityParserFactory $xmlEntityParserFactory,
        $nodeType,
        ConfigurationEngineSelect $wsConfigEngine,
        PsaDatabaseBlockIdMapper $blockIdMapper,
        SiteConfiguration $siteConfiguration
    )
    {
        parent::__construct($entityFactory, $xPathQuery, $xmlEntityParserFactory, $nodeType);
        $this->wsConfigEngine = $wsConfigEngine;
        $this->blockIdMapper = $blockIdMapper;
        $this->siteConfiguration = $siteConfiguration;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'PN13';
    }

    /**
     * @param ShowroomUrlManager            $urlManager showroom urlManager
     * @param PsaPageShowroomMetadata       $currentPage
     * @param DOMXPath                      $rootXPath xml root using xpath
     * @param DOMElement                    $sliceNode
     * @param AddReportingMessageInterface  $reporting
     *
     * @return PsaPageMultiZone
     */
    public function parse(ShowroomUrlManager $urlManager, PsaPageShowroomMetadata $currentPage, DOMXPath $rootXPath, DOMElement $sliceNode = null, AddReportingMessageInterface $reporting)
    {
        $block = $this->entityFactory->createDynamicBlockForSliceId(
            $currentPage->getPage(),
            $currentPage->getDynamicBlocksZoneOrder(),
            $this->getName()
        );
        $this->uspBlock = $block;
        $this->urlManager = $urlManager;
        $this->currentPage = $currentPage;
        $this->rootXPath = $rootXPath;
        $this->reporting = $reporting;

        // Note: The list of anchor and the title should be filled as post treatment,
        //       It need all the dynamic blocks list for the page to be parsed and created first
        $currentPage->addCallableEvents(PsaPageShowroomMetadata::CALLABLE_SLICE_POST_SAVING, $this, 'postSavingCallableEvent');

        return $block;
    }


    /**
     * @return array
     */
    public function postSavingCallableEvent()
    {
        $this->fillBlock(
            $this->uspBlock,
            $this->urlManager,
            $this->currentPage,
            $this->rootXPath,
            $this->reporting
        );

        return ['block' => $this->uspBlock];
    }

    /**
     * @param PsaPageMultiZone              $block
     * @param ShowroomUrlManager            $urlManager
     * @param PsaPageShowroomMetadata       $currentPage
     * @param DOMXPath                      $rootXPath
     * @param AddReportingMessageInterface  $reporting
     */
    private function fillBlock(PsaPageMultiZone $block, ShowroomUrlManager $urlManager, PsaPageShowroomMetadata $currentPage, DOMXPath $rootXPath, AddReportingMessageInterface $reporting)
    {
        $page = $currentPage->getPage();
        $pageDraftVersion = $page->getDraftVersion();
        $zoneOrder = $block->getZoneOrder();

        // Parse dynamic blocks to create anchor
        // Don't use blocks created by the configurator and the PN13 itself
        $zoneIdsToIgnore = $this->getZoneIdsToIgnore();
        $uspBlocks = $pageDraftVersion->getDynamicPageBlocks()->filter(
            function (PsaPageMultiZone $block) use ($zoneIdsToIgnore) {

                return !in_array($block->getZoneId(), $zoneIdsToIgnore);
            }
        );

        foreach ($uspBlocks as $index => $anchorBlock) {
            /** @var PsaPageMultiZone $anchorBlock */
            $blockMulti = $this->entityFactory->createDynamicBlockMulti($page, $zoneOrder,  $index + 1, PsaPageZoneMulti::PAGE_ZONE_MULTI_TYPE_ANCHOR);
            $blockMulti->setPageZoneMultiTitre($anchorBlock->getZoneTitre());
            $blockMulti->setPageZoneMultiValue($this->createAnchorId($anchorBlock));

            $block->addMulti($blockMulti);
        }

        // Fill text
        $block->setZoneTitre($this->createTitle($urlManager, $rootXPath, $uspBlocks, $reporting));
    }


    /**
     * Generate block id for From view psa_page_areas_blocks, permanent id for dynamic block
     * concat_ws('.',`pv`.`PAGE_ID`,`pv`.`LANGUE_ID`,`pv`.`TEMPLATE_PAGE_ID`,`a`.`AREA_ID`,`pmz`.`ZONE_ID`,`pmz`.`UID`) AS `PERMANENT_ID`
     * ex : 3969.1.378.150.825.56123af134523
     *
     * @param PsaPageMultiZone $anchorBlock
     *
     * @return string
     */
    private function createAnchorId(PsaPageMultiZone $anchorBlock)
    {
        $pageVersion = $anchorBlock->getPageVersion();
        $anchorId = implode(('.'), [
            $pageVersion->getPageId(),
            $pageVersion->getLangueId(),
            $pageVersion->getTemplateId(),
            $anchorBlock->getAreaId(),
            $anchorBlock->getZoneId(),
            $anchorBlock->getUid()
        ]);

        return $anchorId;
    }

    /**
     * @param ShowroomUrlManager            $urlManager
     * @param DOMXPath                      $rootXPath
     * @param Collection                    $uspBlocks
     * @param AddReportingMessageInterface  $reporting
     *
     * @return string
     */
    private function createTitle(ShowroomUrlManager $urlManager, DOMXPath $rootXPath, Collection $uspBlocks, AddReportingMessageInterface $reporting)
    {
        $showroomResponsiveHtmlXmlNode = $this->xPathQuery->queryFirstDOMElement('showroom_responsive_html', $rootXPath);
        $responsiveType = $this->xPathQuery->queryFirstDOMElementNodeValue('./responsive_type', $rootXPath, $showroomResponsiveHtmlXmlNode);
        if ($responsiveType === self::RESPONSIVE_TYPE_REVEAL) {
            $title = $this->xPathQuery->queryFirstDOMElementNodeValue('./translation10', $rootXPath, $showroomResponsiveHtmlXmlNode);
        } else {
            $title = $this->xPathQuery->queryFirstDOMElementNodeValue('./translation1', $rootXPath, $showroomResponsiveHtmlXmlNode);
            $trans2 = $this->xPathQuery->queryFirstDOMElementNodeValue('./translation2', $rootXPath, $showroomResponsiveHtmlXmlNode);
            if ($trans2 !== '') {
                $title = $title . ' ' . $trans2;
            }
        }
        $title = $this->strReplaceWidgetCountTag($title, $uspBlocks);
        $title = $this->strReplaceBodyModelTags($title, $urlManager, $rootXPath, $showroomResponsiveHtmlXmlNode, $reporting);

        return $title;
    }

    /**
     * @param $title
     * @param Collection $uspBlocks
     *
     * @return mixed
     */
    private function strReplaceWidgetCountTag($title, Collection $uspBlocks)
    {
        $nbAnchorBlock = $uspBlocks->count();

        // Replace widget count tags
        return str_replace(self::TITLE_TAG_WIDGET_COUNT, $nbAnchorBlock, $title);
    }

    /**
     * @param $title
     * @param ShowroomUrlManager            $urlManager
     * @param DOMXPath                      $rootXPath
     * @param DOMElement                    $showroomResponsiveHtmlXmlNode
     * @param AddReportingMessageInterface  $reporting
     *
     * @return mixed
     */
    private function strReplaceBodyModelTags($title, ShowroomUrlManager $urlManager, DOMXPath $rootXPath, DOMElement $showroomResponsiveHtmlXmlNode, AddReportingMessageInterface $reporting)
    {
        // Replace body / model count tags
        $lcdv = $this->xPathQuery->queryFirstDOMElementNodeValue('./model_code', $rootXPath, $showroomResponsiveHtmlXmlNode) .
            $this->xPathQuery->queryFirstDOMElementNodeValue('./body_code', $rootXPath, $showroomResponsiveHtmlXmlNode);

        $model = $this->getModelFromWs($lcdv, $urlManager->getLanguage()->getLangueCode(), $reporting);
        if (strpos($title, self::TITLE_TAG_MODEL) !== false) {
            $title = str_replace(self::TITLE_TAG_MODEL, $model, $title);
            $title = str_replace(self::TITLE_TAG_BODY, '', $title);
        } else if (strpos($title, self::TITLE_TAG_BODY) !== false) {
            $title = str_replace(self::TITLE_TAG_BODY, $model, $title);
        }

        return strip_tags($title);
    }

    /**
     * @param string                       $lcdv         lcdv 4 or 6
     * @param string                       $languageCode Language code
     * @param AddReportingMessageInterface $reporting
     *
     * @return string
     */
    private function getModelFromWs($lcdv, $languageCode, AddReportingMessageInterface $reporting)
    {
        $versions = [];
        try {
            $this->wsConfigEngine->setDefaultContext($this->siteConfiguration, null);
            $this->wsConfigEngine->addContext('Country', strtoupper($languageCode));
            $this->wsConfigEngine->addContext('LanguageID', $languageCode);

            switch (strlen($lcdv)) {
                case 4:
                    $versions = $this->wsConfigEngine->getVersionsByLCDV4($lcdv);
                    break;
                case 6:
                    $versions = $this->wsConfigEngine->getVersionsByLCDV6($lcdv);
                    break;
            }
        } catch (\Exception $e) {
            $reporting->addWarningMessage("Error while trying to call webservice Configuration Engine. XML value {body} and {model} tags was not replace in the PN13 title.");
        }
        // Get first model value by default
        $model = count($versions) > 0 ? $versions[0]->Model->label : '';

        return $model;
    }

    /**
     * Return list of blocks that should be ignored for the PN13 configuration:
     *  - Dynamics blocks created by the configurator
     *  - The PN13 itself
     *
     * @return array
     */
    private function getZoneIdsToIgnore()
    {
        if ($this->zoneIdsToIgnore === null) {
            $this->zoneIdsToIgnore =[
                $this->blockIdMapper->getDynamicBlockZoneId($this->getName()), // PN13 itself
                $this->blockIdMapper->getDynamicBlockZoneId('PC40'),
                $this->blockIdMapper->getDynamicBlockZoneId('PC79'),
                $this->blockIdMapper->getDynamicBlockZoneId('PF11'),
                $this->blockIdMapper->getDynamicBlockZoneId('PF27'),
                $this->blockIdMapper->getDynamicBlockZoneId('PC60')
            ];
        }

        return $this->zoneIdsToIgnore;
    }

}
