<?php


namespace Itkg\Migration\XML\EntityParser;

use DOMXPath;
use DOMElement;
use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Transaction\PsaBlockShowroomMetadata;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use Itkg\Transaction\PsaEntityFactory;
use Itkg\Utils\Exception\BlockIdSliceMappingNotFoundException;
use PSA\MigrationBundle\Entity\Page\PsaPageMultiZone;
use PSA\MigrationBundle\Entity\Page\PsaPageZone;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;

/**
 * Class ShowroomXMLPageParser
 * @package Itkg\Migration\XML\EntityParser
 */
class ShowroomXMLPageParser extends AbstractShowroomXMLEntityParser
{
    const XML_HOMEPAGE_TITLE = "01_homepage";
    const XML_SUBPAGES_ROOT_TITLE = "02_main_topics";
    const PAGE_ROOT_ID = 1;

    /** @var PsaPageShowroomMetadata */
    protected $parent = null;

    /**
     * @param ShowroomUrlManager            $urlManager showroom urlManager
     * @param PsaPageShowroomMetadata|null  $currentPage
     * @param DOMXPath                      $rootXPath xml root using xpath
     * @param DOMElement                    $pageNode
     * @param AddReportingMessageInterface  $reporting
     *
     * @return PsaPageShowroomMetadata
     *
     * @throws BlockIdSliceMappingNotFoundException
     * @throws \Exception
     */
    public function parse(
        ShowroomUrlManager $urlManager,
        PsaPageShowroomMetadata $currentPage = null,
        DOMXPath $rootXPath,
        DOMElement $pageNode,
        AddReportingMessageInterface $reporting
    )
    {
        $isHomepage = $pageNode->getAttribute('title') === self::XML_HOMEPAGE_TITLE;

        // Create a new page
        $pageWithMetadata = $this->createNewPage($urlManager, $rootXPath, $pageNode, $isHomepage, $this->parent);
        $pageWithMetadata->setXmlId($this->xPathQuery->getXmlId($pageNode));

        // Get slices data
        $this->fillPageSlices($urlManager, $pageWithMetadata, $rootXPath, $pageNode, $isHomepage, $reporting);

        return $pageWithMetadata;
    }


    /**
     * @param ShowroomUrlManager            $urlManager
     * @param PsaPageShowroomMetadata       $currentPage
     * @param DOMXPath                      $rootXPath
     * @param DOMElement                    $pageNode
     * @param boolean                       $isHomepage
     * @param AddReportingMessageInterface  $reporting
     */
    private function fillPageSlices(
        ShowroomUrlManager $urlManager,
        PsaPageShowroomMetadata $currentPage,
        DOMXPath $rootXPath,
        DOMElement $pageNode,
        $isHomepage,
        AddReportingMessageInterface $reporting
    )
    {
        // "Special" slices before parsing the generic lists of widget
        switch ($urlManager->getUrlType())
        {
            case ShowroomUrlManager::URL_TYPE_TECHNO_PUBLISHED:
                $this->fillSpecificSlicesForShowroom(
                    [ShowroomXMLEntityParserFactory::SLICE_PN7, ShowroomXMLEntityParserFactory::SLICE_PN14],
                    $urlManager,
                    $currentPage,
                    $rootXPath,
                    $pageNode,
                    $isHomepage,
                    false,
                    $reporting
                );
                break;
            default:
                $this->fillSpecificSlicesForShowroom(
                    [ShowroomXMLEntityParserFactory::SLICE_PF2, ShowroomXMLEntityParserFactory::SLICE_PN14],
                    $urlManager,
                    $currentPage,
                    $rootXPath,
                    $pageNode,
                    $isHomepage,
                    true,
                    $reporting
                );
                break;
        }

        // Create block according to existing Widget XML
        $sliceNodes = $this->getXmlSliceNodes($rootXPath, $pageNode, $isHomepage);
        foreach ($sliceNodes as $sliceNode) {
            $widgetType = $this->getWidgetType($rootXPath, $sliceNode);
            // FOR PF6
            if ($widgetType === ShowroomXMLEntityParserFactory::WIDGET_TYPE_SINGLE_LARGE) {
                $mediaPath = $this->xPathQuery->queryFirstDOMElementNodeValue(
                    './articles/article/article_linked_media1', $rootXPath, $sliceNode
                );
                if ($mediaPath !== null && $mediaPath !== '') {
                    $widgetType = ShowroomXMLEntityParserFactory::WIDGET_TYPE_SINGLE_LARGE_COMPARATOR;
                }
            }
            $this->parseNodeAndAddBlockWithMetadata(
                $widgetType, null, $sliceNode, $urlManager, $currentPage, $rootXPath, $reporting
            );
        }
    }

    /**
     * @param array $staticSlices
     * @param ShowroomUrlManager $urlManager
     * @param PsaPageShowroomMetadata $currentPage
     * @param DOMXPath $rootXPath
     * @param DOMElement $pageNode
     * @param boolean $isHomepage
     * @param boolean $importPc23
     * @param AddReportingMessageInterface $reporting
     *
     * @throws BlockIdSliceMappingNotFoundException
     */
    private function fillSpecificSlicesForShowroom (
        array $staticSlices,
        ShowroomUrlManager $urlManager,
        PsaPageShowroomMetadata $currentPage,
        DOMXPath $rootXPath,
        DOMElement $pageNode,
        $isHomepage,
        $importPc23,
        AddReportingMessageInterface $reporting
    )
    {
        $page = $currentPage->getPage();
        $pageVersion = $page->getDraftVersion();

        // Create static block from parsing in all pages
        $this->fillStaticSlices(
            $staticSlices,
            $urlManager,
            $currentPage,
            $rootXPath,
            $pageNode,
            $reporting
        );

        // For Homepage, Add a pn13 slice as first element in the list of dynamic slices
        // This generation as the first block is important for other block to be generated with the correct order
        if ($isHomepage) {
            /** @var PsaPageMultiZone $pn13Block */
            $pn13Block = $this->parseBlock(
                ShowroomXMLEntityParserFactory::SLICE_PN13,
                null,
                $pageNode,
                $urlManager,
                $currentPage,
                $rootXPath,
                $reporting
            );
            if ($pn13Block) {
                $pageVersion->addDynamicPageBlock($pn13Block);
            }
        }

        // Create block according to PageType (PC23)
        $pageArticleType = $isHomepage ? null : $this->xPathQuery->queryFirstDOMElementNodeValue('article_type', $rootXPath, $pageNode);
        if ($pageArticleType === ShowroomXMLEntityParserFactory::PAGE_TYPE_GALLERY && $importPc23) {
            $this->parseNodeAndAddBlockWithMetadata(
                null, $pageArticleType, $pageNode, $urlManager, $currentPage, $rootXPath, $reporting
            );
        }
    }

    /**
     * @param array                         $staticSliceIds
     * @param ShowroomUrlManager            $urlManager
     * @param PsaPageShowroomMetadata       $currentPage
     * @param DOMXPath                      $rootXPath
     * @param DOMElement                    $pageNode
     * @param AddReportingMessageInterface  $reporting
     */
    private function fillStaticSlices(
        array $staticSliceIds,
        ShowroomUrlManager $urlManager,
        PsaPageShowroomMetadata $currentPage,
        DOMXPath $rootXPath,
        DOMElement $pageNode,
        AddReportingMessageInterface $reporting
    )
    {
        $page = $currentPage->getPage();
        $pageVersion = $page->getDraftVersion();

        // Create static block from parsing in all pages
        foreach ($staticSliceIds as $blockType) {
            /** @var PsaPageZone $block */
            $block = $this->parseBlock($blockType, null, $pageNode, $urlManager, $currentPage, $rootXPath, $reporting);
            if ($block) {
                $pageVersion->addBlock($block);
            }
        }
    }

    /**
     * @param string|null                   $widgetType
     * @param string|null                   $pageArticleType
     * @param DOMElement                    $xmlNode
     * @param ShowroomUrlManager            $urlManager
     * @param PsaPageShowroomMetadata       $currentPage
     * @param DOMXPath                      $rootXPath
     * @param AddReportingMessageInterface  $reporting
     *
     * @return PsaPageZoneConfigurableInterface
     *
     * @throws BlockIdSliceMappingNotFoundException
     * @throws \Exception
     */
    private function parseNodeAndAddBlockWithMetadata(
        $widgetType = null,
        $pageArticleType = null,
        DOMElement $xmlNode,
        ShowroomUrlManager $urlManager,
        PsaPageShowroomMetadata $currentPage,
        DOMXPath $rootXPath,
        AddReportingMessageInterface $reporting
    )
    {
       

        $page = $currentPage->getPage();
        $pageVersion = $page->getDraftVersion();

        $block = $this->parseBlock($widgetType, $pageArticleType, $xmlNode, $urlManager, $currentPage, $rootXPath, $reporting);
        if ($block) {
            $blockWithMetadata = new PsaBlockShowroomMetadata();
            $blockWithMetadata->setXmlId($this->xPathQuery->getXmlId($xmlNode));
            $blockWithMetadata->setBlock($block);
            $currentPage->addBlockWithMetadata($blockWithMetadata);

            if ($block instanceof PsaPageMultiZone) {
                $pageVersion->addDynamicPageBlock($block);
            } else if ($block instanceof PsaPageZone) {
                $pageVersion->addBlock($block);
            }
        }
    }

    /**
     * @param string|null                   $widgetType
     * @param string|null                   $pageArticleType
     * @param DOMElement                    $xmlNode
     * @param ShowroomUrlManager            $urlManager
     * @param PsaPageShowroomMetadata       $currentPage
     * @param DOMXPath                      $rootXPath
     * @param AddReportingMessageInterface  $reporting
     *
     * @return PsaPageZoneConfigurableInterface
     *
     * @throws BlockIdSliceMappingNotFoundException
     * @throws \Exception
     */
    private function parseBlock(
        $widgetType = null,
        $pageArticleType = null,
        DOMElement $xmlNode,
        ShowroomUrlManager $urlManager,
        PsaPageShowroomMetadata $currentPage,
        DOMXPath $rootXPath,
        AddReportingMessageInterface $reporting)
    {
        $block = null;
        $nodeType = ($widgetType !== null) ? $widgetType : $pageArticleType;
        $page = $currentPage->getPage();
        $pageVersion = $page->getDraftVersion();
        $sliceParser = $this->xmlEntityParserFactory->createSliceParser($widgetType, $pageArticleType);

        if ($sliceParser !== null) {
            try {
                $block = $sliceParser->parse($urlManager, $currentPage, $rootXPath, $xmlNode, $reporting);
            } catch (BlockIdSliceMappingNotFoundException $e) {
                $pageVersion = $currentPage->getPage()->getDraftVersion();
                if ($pageVersion->getTemplatePage()->getId() === PsaEntityFactory::TEMPLATE_PAGE_ID_GABARIT_TECHNOLOGY_G36) {
                    $reporting->addWarningMessage(
                        sprintf(
                            "A xml node to parse for slice '%s', but not handle in the Gabarit Techno G36 was found. Node Type : '%s' for page '%s in the XML %s. The widget was not imported as a new slice.",
                            $e->getSliceId(), $nodeType, $pageVersion->getPageClearUrl(), $urlManager->getXmlUrl()
                        )
                    );
                } else {
                    throw $e;
                }
            }
        } else {
            // Display warning no parsing except for PF53 and PF58 which are ignored.
            if ($widgetType !== ShowroomXMLEntityParserFactory::WIDGET_TYPE_TECH_MOTORLIST &&
                $pageArticleType !== ShowroomXMLEntityParserFactory::PAGE_TYPE_PRICES) {
                $reporting->addWarningMessage(
                    sprintf(
                        "A xml node to parse and not handle by the migration was found. Node Type : '%s' for page '%s' in the XML '%s'. This widget was not imported as a new slice.",
                        $nodeType, $pageVersion->getPageClearUrl(), $urlManager->getXmlUrl()
                    )
                );
            }
        }

        return $block;
    }

    /**
     * @param DOMXPath   $rootXPath
     * @param DOMElement $sliceNode
     *
     * @return \DOMNodeList
     */
    private function getWidgetType(DOMXPath $rootXPath, DOMElement $sliceNode)
    {
        $widgetType = $this->xPathQuery->queryFirstDOMElementNodeValue('widget_type', $rootXPath, $sliceNode);
        if ($widgetType === ShowroomXMLEntityParserFactory::WIDGET_TYPE_SINGLE_LARGE) {
            $articleLinkedMedia1 = $this->xPathQuery->queryFirstDOMElementNodeValue('.//article_linked_media1', $rootXPath, $sliceNode);
            if ($articleLinkedMedia1 !== null && $articleLinkedMedia1 !== '') {
                $widgetType = ShowroomXMLEntityParserFactory::WIDGET_TYPE_SINGLE_LARGE_COMPARATOR;
            }
        }

        return $widgetType;
    }
    /**
     * @param DOMXPath   $rootXPath
     * @param DOMElement $pageNode
     * @param bool       $isHomepage
     *
     * @return \DOMNodeList
     */
    private function getXmlSliceNodes(DOMXPath $rootXPath, DOMElement $pageNode, $isHomepage)
    {
        if ($isHomepage) {
            $query = './/widget';
        } else {
            $query = './articles[@title="widgets"]//widget';
        }

        return $rootXPath->query($query, $pageNode);
    }


    /**
     * @param ShowroomUrlManager        $urlManager
     * @param DOMXPath                  $rootXPath
     * @param DOMElement                $pageNode
     * @param PsaPageShowroomMetadata   $parent
     * @param boolean                   $isHomepage
     *
     * @return PsaPageShowroomMetadata
     */
    private function createNewPage(ShowroomUrlManager $urlManager, DOMXPath $rootXPath, DOMElement $pageNode, $isHomepage, PsaPageShowroomMetadata $parent = null)
    {
        // Create new draft page and it current version
        $pageWithMetadata = $this->entityFactory->createDraftPageWithMetadata(
            $urlManager->getSite(),
            $urlManager->getLanguage(),
            $urlManager->getUser(),
            $urlManager->getGabaritTemplateId(),
            $isHomepage ? PsaPageShowroomMetadata::PAGE_TYPE_HOMEPAGE : PsaPageShowroomMetadata::PAGE_TYPE_SUB_PAGE
        );
        // Link with parent
        if ($parent !== null) {
            $parent->addSubPage($pageWithMetadata);
        }

        // Complete page with XML data
        if ($isHomepage) {
            $this->fillHomePage($urlManager, $pageWithMetadata, $rootXPath, $pageNode);
        } else {
            $this->fillSubPage($urlManager, $pageWithMetadata, $rootXPath, $pageNode);
        }

        return $pageWithMetadata;
    }

    /**
     * @param ShowroomUrlManager        $urlManager
     * @param PsaPageShowroomMetadata   $pageWithMetadata
     * @param DOMXPath                  $rootXPath
     */
    private function fillHomePage(ShowroomUrlManager $urlManager, PsaPageShowroomMetadata $pageWithMetadata, DOMXPath $rootXPath)
    {
        $page = $pageWithMetadata->getPage();
        $draftVersion = $page->getDraftVersion();
        $showroomResponsiveHtmlXmlNode = $this->xPathQuery->queryFirstDOMElement('showroom_responsive_html', $rootXPath);

        if (null !== $showroomResponsiveHtmlXmlNode) {
            $showroomId = $showroomResponsiveHtmlXmlNode->getAttribute('showroom_id');
            $urlKey = $showroomResponsiveHtmlXmlNode->getAttribute('urlkey');
            $title = strip_tags($this->xPathQuery->queryFirstDOMElementNodeValue('car_name_model', $rootXPath, $showroomResponsiveHtmlXmlNode));
            $seoTitle = $this->xPathQuery->queryFirstDOMElementNodeValue('seointernal_title', $rootXPath, $showroomResponsiveHtmlXmlNode);
            $seoDescription = $this->xPathQuery->queryFirstDOMElementNodeValue('seointernal_description', $rootXPath, $showroomResponsiveHtmlXmlNode);
            $seoKeywords = $this->xPathQuery->queryFirstDOMElementNodeValue('seointernal_keywords', $rootXPath, $showroomResponsiveHtmlXmlNode);

            $pageWithMetadata->setShowroomId($showroomId);
            $pageWithMetadata->setShowroomUrlKey($urlKey);
            $pageWithMetadata->setPageUrlKey($urlKey);
            if (!empty($title)) {
                $draftVersion->setPageTitleBo($title);
                $draftVersion->setPageTitle($title);
            }
            $draftVersion->setPageMetaTitle($seoTitle);
            $draftVersion->setPageMetaDesc($seoDescription);
            $draftVersion->setPageMetaKeyword($seoKeywords);
            $draftVersion->setPageText(
                strip_tags(
                    $this->xPathQuery->queryFirstDOMElementNodeValue(
                        'main_description',
                        $rootXPath,
                        $showroomResponsiveHtmlXmlNode
                    )
                )
            );
            $oldUrl = $this->xPathQuery->generateWelcomePagePath($urlManager, $rootXPath);
            $pagePrewite = $this->entityFactory->createPageRewrite($oldUrl, $page);
            $page->addRewrite($pagePrewite);

            $draftVersion->setPageClearUrl(rtrim($oldUrl,'/').'.html');
        }
    }

    /**
     * @param ShowroomUrlManager        $urlManager
     * @param PsaPageShowroomMetadata   $pageWithMetadata
     * @param DOMXPath                  $rootXPath
     * @param DOMElement                $pageNode
     */
    private function fillSubPage(ShowroomUrlManager $urlManager, PsaPageShowroomMetadata $pageWithMetadata, DOMXPath $rootXPath, DOMElement $pageNode)
    {
        $page = $pageWithMetadata->getPage();
        $draftVersion = $page->getDraftVersion();
        $title =$this->xPathQuery->queryFirstDOMElementNodeValue('title', $rootXPath, $pageNode);
        $shortTitle =$this->xPathQuery->queryFirstDOMElementNodeValue('short_title', $rootXPath, $pageNode);
        $urlKey = $pageNode->getAttribute('urlkey');
        $pageTitle = $pageNode->getAttribute('title');
        $seoTitle = $this->xPathQuery->queryFirstDOMElementNodeValue('seointernal_title', $rootXPath, $pageNode);
        $seoDescription = $this->xPathQuery->queryFirstDOMElementNodeValue('seointernal_description', $rootXPath, $pageNode);
        $seoKeywords = $this->xPathQuery->queryFirstDOMElementNodeValue('seointernal_keywords', $rootXPath, $pageNode);
        $pageWithMetadata->setPageUrlKey($urlKey);
        if (!empty($pageTitle)) {
            $draftVersion->setPageTitleBo($pageTitle);
        }
        $draftVersion->setPageTitle($title . ' ' . $shortTitle);
        $draftVersion->setPageMetaTitle($seoTitle);
        $draftVersion->setPageMetaDesc($seoDescription);
        $draftVersion->setPageMetaKeyword($seoKeywords);
        $draftVersion->setPageText(
            strip_tags(
                $this->xPathQuery->queryFirstDOMElementNodeValue(
                    'content',
                    $rootXPath,
                    $pageNode
                )
            )
        );
        $oldUrl = $this->xPathQuery->generateSubPagePath($urlManager, $rootXPath, $pageNode);
        $pagePrewite = $this->entityFactory->createPageRewrite($oldUrl, $page);
        $page->addRewrite($pagePrewite);

        $draftVersion->setPageClearUrl(rtrim($this->xPathQuery->generateSubPagePath($urlManager, $rootXPath, $pageNode, ''),'/').'.html');
    }

    /**
     * @return PsaPageShowroomMetadata
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param PsaPageShowroomMetadata $parent
     *
     * @return ShowroomXMLPageParser
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

}
