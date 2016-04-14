<?php


namespace Itkg\Migration\XML;

use Doctrine\ORM\EntityManager;
use Itkg\Migration\Configurator\ConfiguratorService;
use Itkg\Migration\Reporting\AddReportingMessageInterface;
use Itkg\Migration\Transaction\PsaPageShowroomMetadata;
use Itkg\Migration\Transaction\PsaShowroomEntityFactory;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use Itkg\Migration\XML\EntityParser\ShowroomXMLEntityParserFactory;
use Itkg\Migration\XML\EntityParser\ShowroomXMLPageParser;
use Itkg\Utils\FileManagerService;
use PSA\MigrationBundle\Entity\Page\PsaPage;
use PSA\MigrationBundle\Entity\Page\PsaPageVersion;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use DOMDocument;
use DOMXPath;
use PSA\MigrationBundle\Repository\PsaPageRepository;
use PSA\MigrationBundle\Repository\PsaPageVersionRepository;
use PSA\MigrationBundle\Repository\PsaRewriteRepository;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Download showroom XML and parse transform xml data into NDP entities
 *
 * Class ShowroomXMLChecker
 * @package Itkg\Migration
 */
class ShowroomXMLParserService
{
    /** @var PsaShowroomEntityFactory */
    private $entityFactory;
    /** @var FileManagerService */
    private $xmlFileManager;
    /** @var XPathQueryHelper */
    private $xPathQuery;
    /** @var ShowroomXMLEntityParserFactory */
    private $xmlEntityParserFactory;
    /** @var ConfiguratorService  */
    protected $configurator;

    /** @var string */
    private $xmlDirectory;

    /** @var AddReportingMessageInterface */
    private $reporting;
    /** @var PsaSite */
    private $site;
    /** @var ShowroomUrlManager[] */
    private $urlManagers;

    /**
     * @param EntityManager $em
     * @param PsaShowroomEntityFactory $entityFactory
     * @param FileManagerService $xmlFileManager
     * @param XPathQueryHelper $xPathQuery
     * @param ShowroomXMLEntityParserFactory $xmlEntityParserFactory
     * @param ConfiguratorService $configurator
     */
    public function __construct(
        EntityManager $em,
        PsaShowroomEntityFactory $entityFactory,
        FileManagerService $xmlFileManager,
        XPathQueryHelper $xPathQuery,
        ShowroomXMLEntityParserFactory $xmlEntityParserFactory,
        ConfiguratorService $configurator
    )
    {
        $this->em = $em;
        $this->entityFactory = $entityFactory;
        $this->xmlFileManager = $xmlFileManager;
        $this->xmlEntityParserFactory= $xmlEntityParserFactory;
        $this->xPathQuery = $xPathQuery;
        $this->configurator = $configurator;
    }

    /**
     *
     *
     * @param array $urlManagers                        array of ShowroomUrlManager for each showroom by language
     * @param AddReportingMessageInterface $reporting   Report XML parsing result
     * @param PsaSite $site                             Current site launching the migration
     *
     * @return PsaPageShowroomMetadata[]
     */
    public function parse(array $urlManagers, AddReportingMessageInterface $reporting, PsaSite $site)
    {
        $multiLingualShowrooms = [];
        $this->reporting = $reporting;
        $this->site = $site;
        $this->urlManagers = $urlManagers;

        // Check XML
        $isAllValidXml = $this->retrieveAllShowroomXml();

        // If all valid xml, Convert XML data to entities
        if ($isAllValidXml && $this->notExistingShowroomPaths()) {
            $multiLingualShowrooms = $this->parseAllShowroomXml();
        }

        // Delete dwd xml
        $fs = new Filesystem();
        foreach ($this->urlManagers as $urlManager) {
            $fs->remove($urlManager->getXmlFilePath());
        }

        return $multiLingualShowrooms;
    }


    /**
     *  Parse XML downloaded to retrieve entities object
     *
     * @return PsaPageShowroomMetadata[]
     */
    private function notExistingShowroomPaths()
    {
        $result = true;

        // Parse each showroom xml for each urlManager language
        foreach ($this->urlManagers as $urlManager) {
            /** @var ShowroomUrlManager $urlManager */
            if (null !== $urlManager->getXmlFilePath()) {
                $xmlDom = new DOMDocument();
                $xmlDom->load($urlManager->getXmlFilePath());
                $xpath = new DOMXPath($xmlDom);
                $homePage = null;

                // Get Homepage XML node
                $homePageNode = $this->xPathQuery->queryFirstDOMElement(
                    '//articles[@title="' . ShowroomXMLPageParser::XML_HOMEPAGE_TITLE . '"]',
                    $xpath
                );
                $url = $this->xPathQuery->generateWelcomePagePath($urlManager, $xpath);
                $notExistingPath = $this->notExistingPagePath($url, $urlManager);
                // Check Home Page
                $result = $result && $notExistingPath;
                if ($homePageNode && $notExistingPath) {

                    $subPageNodes = $xpath->query('//articles[@title="' . ShowroomXMLPageParser::XML_SUBPAGES_ROOT_TITLE . '"]/article');
                    // Check Subpage
                    $count = $subPageNodes->length;
                    $i = 0;
                    while ($result && $i < $count) {
                        $url = $this->xPathQuery->generateSubPagePath($urlManager, $xpath, $subPageNodes->item($i));
                        $notExistingPath = $this->notExistingPagePath($url, $urlManager);
                        $result = $result && $notExistingPath;
                        $i++;
                    }
                }
            }
        }

        //TODO check also url on created default page ?

        return $result;
    }


    /**
     *  Parse XML downloaded to retrieve entities object
     *
     * @return PsaPageShowroomMetadata[]
     */
    private function parseAllShowroomXml()
    {
        $showrooms = [];

        // Parse each showroom xml for each urlManager language
        foreach ($this->urlManagers as $urlManager) {
            /** @var ShowroomUrlManager $urlManager */
            if (null !== $urlManager->getXmlFilePath()) {
                $showrooms[] = $this->parseShowroomPages($urlManager);
            }
        }

        return $showrooms;
    }

    /**
     *
     * @param ShowroomUrlManager $urlManager
     *
     * @return PsaPageShowroomMetadata|null
     */
    private function parseShowroomPages(ShowroomUrlManager $urlManager)
    {
        $xmlDom = new DOMDocument();
        $xmlDom->load($urlManager->getXmlFilePath());
        $xpath = new DOMXPath($xmlDom);
        $homePage = null;

        // Get Homepage XML node
        $homePageNode = $this->xPathQuery->queryFirstDOMElement(
            '//articles[@title="' . ShowroomXMLPageParser::XML_HOMEPAGE_TITLE . '"]',
            $xpath
        );

        if ($homePageNode) {
            // Parse Home Page
            $pageParser = $this->xmlEntityParserFactory->createPageParser();
            // Note: The welcome page will be attached to the website during transaction saving
            $pageParser->setParent(null);
            $homePage = $pageParser->parse($urlManager, null, $xpath, $homePageNode, $this->reporting);

            // Get XML subpages root node
            $subPagesRootNode = $this->xPathQuery->queryFirstDOMElement(
                '//articles[@title="' . ShowroomXMLPageParser::XML_SUBPAGES_ROOT_TITLE . '"]',
                $xpath
            );

            if ($subPagesRootNode) {
                $pageParser = $this->xmlEntityParserFactory->createPageParser();
                $pageParser->setParent($homePage);
                $subPageNodes = $xpath->query('article', $subPagesRootNode);

                // Create default SubPages
                $homePage->setDefaultPages(
                    $this->createDefaultPages($homePage, $urlManager, $xpath, $subPageNodes)
                );

                // Parse Subpages
                foreach ($subPageNodes as $subPageNode) {
                    $articleType = $this->xPathQuery->queryFirstDOMElementNodeValue('article_type', $xpath, $subPageNode);

                    if ($articleType !== ShowroomXMLEntityParserFactory::PAGE_TYPE_PRICES) {
                        $pageParser->parse($urlManager, null, $xpath, $subPageNode, $this->reporting);
                    }
                }
            } else {
                $this->reporting->addWarningMessage(
                    t(
                        'NDP_MIG_ERROR_MAINTOPIC_NODE',
                        '',
                        [
                            'XmlUrl' => $urlManager->getXmlUrl()
                        ]
                    )
                );
            }

            // Add default configuration to created pages
            $this->configurator->configurePageAndSubPages($homePage, $urlManager, $this->reporting);
        } else {
            $this->reporting->addErrorMessage(
                t(
                    'NDP_MIG_ERROR_HOMEPAGE_NODE',
                    '',
                    [
                        'XmlUrl' => $urlManager->getXmlUrl()
                    ]
                )
            );
        }

        return $homePage;
    }


    /**
     * @param PsaPageShowroomMetadata $welcomePage
     * @param ShowroomUrlManager $urlManager
     * @param DOMXPath $xpath
     * @param \DOMNodeList $subPagesNodes
     *
     * @return array
     */
    private function createDefaultPages(PsaPageShowroomMetadata $welcomePage, ShowroomUrlManager $urlManager, DOMXPath $xpath, \DOMNodeList $subPagesNodes)
    {
        $result = [];

        if ($subPagesNodes->length > 1) {
            $showroomResponsiveHtmlXmlNode = $this->xPathQuery->queryFirstDOMElement('showroom_responsive_html', $xpath);
            $rootUrlKey = $showroomResponsiveHtmlXmlNode->getAttribute('urlkey');

            $result = $this->configurator->createDefaultSubPages($welcomePage, $rootUrlKey, $urlManager, $this->reporting);
        }

        return $result;
    }




    /**
     * Check all showroom url to generate XML url associated.
     * Download XML and check file downloaded is an XML file.
     *
     * @return bool
     */
    private function retrieveAllShowroomXml()
    {
        $isAllValidXml = true;

        foreach ($this->urlManagers as $urlManager) {
            /** @var ShowroomUrlManager $urlManager */
            $isValid = $this->downloadAndCheckIsXml($urlManager);
            $isAllValidXml = $isAllValidXml && $isValid;
        }

        return $isAllValidXml;
    }

    /**
     * Download and check downloaded file is a valid XML file
     *
     * @param ShowroomUrlManager $urlManager
     *
     * @return bool
     */
    private function downloadAndCheckIsXml(ShowroomUrlManager $urlManager)
    {
        $xmlFilePath = null;
        $isValidXml = true;

        // Dwd XML
        try {
            $xmlFilePath = $this->xmlFileManager->download(
                $urlManager->getXmlUrl(),
                $this->xmlDirectory,
                $this->site->getSiteId()
            );
        } catch (\Exception $e) {
            $this->reporting->addErrorMessage(
                t('NDP_MIG_ERROR_DOWNLOADING_XML_FILE')
            );
            $this->reporting->setException($e->getMessage());
            $this->reporting->setUrl($urlManager->getXmlUrl());
            $isValidXml = false;
        }

        if ($isValidXml) {
            $urlManager->setXmlFilePath($xmlFilePath);
            // Check file is a valid XML
            $isValidXml = $this->xmlFileManager->isValidXmlFile($xmlFilePath);

            // Check Result
            if (!$isValidXml) {
                $this->reporting->addErrorMessage(
                    t('NDP_MIG_XML_FILE_DOWNLOADED_NOT_VALID')
                );
                $this->reporting->setUrl($urlManager->getXmlUrl());
            }
        }

        if ($isValidXml) {
            $this->reporting->addInfoMessage(
                t(
                    "NDP_MIG_XML_FILE_DOWNLOAD_SUCCESS",
                    '',
                    [
                        'XmlUrl' => $urlManager->getXmlUrl()
                    ]
                )
            );
            $this->reporting->setUrl($urlManager->getXmlUrl());
        }

        return $isValidXml;
    }


    /**
     * Set directory fox downloaded xml file
     *
     * @param $xmlDirectory
     *
     * @return ShowroomXMLParserService
     */
    public function setXmlDirectory($xmlDirectory)
    {
        $this->xmlDirectory = $xmlDirectory;

        return $this;
    }

    /**
     * @param string $pagePath
     * @param ShowroomUrlManager $urlManager
     *
     * @return bool
     *
     */
    private function notExistingPagePath($pagePath, ShowroomUrlManager $urlManager)
    {
        $languageId = $urlManager->getLanguage()->getLangueId();
        $siteId = $urlManager->getSite()->getSiteId();

        /** @var PsaRewriteRepository $rewriteRepository */
        $rewriteRepository = $this->em->getRepository('PSA\MigrationBundle\Entity\PsaRewrite');
        $rewrite = $rewriteRepository->findOneByRewriteUrlAndSiteId($pagePath, $siteId);

        /** @var PsaPageVersionRepository $pageVersionRepository */
        $pageVersionRepository = $this->em->getRepository('PSA\MigrationBundle\Entity\Page\PsaPageVersion');

        if ($rewrite) {
            $pages = $pageVersionRepository->findBy(array('pageId' => $rewrite->getPage()->getPageId(), 'langueId' => $languageId));
        } else {
            $pages = $pageVersionRepository->findPageVersionByClearUrlLanguageIdAndSiteId($pagePath, $languageId, $siteId);
        }

        foreach($pages as $page) {
            /** @var PsaPageVersion $page */
            $this->reporting->addErrorMessage(
                t(
                    'NDP_MIG_EXISTING_PAGE_ERROR',
                    '',
                    [
                        'Url'         => $pagePath,
                        'LangueCode'  => $urlManager->getLanguage()->getLangueCode(),
                        'LangueId'    => $urlManager->getLanguage()->getLangueId(),
                        'PageTitle'   => $page->getPageTitle(),
                        'PageId'      => $page->getPageId(),
                        'PageVersion' => $page->getPageVersion(),

                    ]
                )
            );
        }

        return (count($pages) === 0);
    }
}
