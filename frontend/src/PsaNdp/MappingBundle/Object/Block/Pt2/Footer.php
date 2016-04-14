<?php
namespace PsaNdp\MappingBundle\Object\Block\Pt2;

use Doctrine\Common\Collections\ArrayCollection;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use PsaNdp\MappingBundle\Manager\TreeManager;
use PsaNdp\MappingBundle\Object\Content;
use PsaNdp\MappingBundle\Object\Factory\CtaFactory;
use PsaNdp\MappingBundle\Services\MediaServerInitializer;
use PsaNdp\MappingBundle\Services\PageFinder;
use Symfony\Component\Translation\TranslatorInterface;

class Footer extends Content
{


    protected $helpSection;
    protected $linksSection;
    protected $legalSection;
    protected $siteMapSection;
    protected $socialSection;
    protected $newsletterSection;
    protected $contextualSection;
    protected $languages;
    protected $currentLanguageId;
    protected $currentPageId;
    protected $siteId;
    protected $contactSection;
    protected $datalayer;
    protected $anchorId;
    private $pageFinder;
    private $translator;
    protected $fullMapLink;
    protected $pageTypeCode;

    public function __construct(
        TranslatorInterface $translator,
        TreeManager $treeManager,
        MediaServerInitializer $mediaServerInitializer,
        CtaFactory $ctaFactory
    ) {
        $this->translator = $translator;
        $this->treeManager = $treeManager;
        $this->mediaServer = $mediaServerInitializer->getMediaServer();
        $this->ctaFactory = $ctaFactory;
    }

    /**
     * @param PageFinder $pageFinder
     *
     * @return $this
     */
    public function setPageFinder(PageFinder $pageFinder)
    {
        $this->pageFinder = $pageFinder;

        return $this;
    }

    /**
     * @return bool
     */
    public function displayHelp()
    {
        return (boolean) $this->helpSection->getZoneAffichage();
    }

    /**
     * @return string
     */
    public function getHelpTitle()
    {
        return $this->helpSection->getZoneTitre();
    }

    /**
     * @return array
     */
    public function getHelpLinks()
    {
        return $this->ctaFactory->create($this->helpSection->getCtaReferences());

    }

    /**
     * Get helpSection
     *
     * @return mixed
     */
    public function getHelpSection()
    {
        return $this->helpSection;
    }

    /**
     * @param mixed $helpSection
     *
     * @return Footer
     */
    public function setHelpSection($helpSection)
    {
        $this->helpSection = $helpSection;

        return $this;
    }

    /**
     * Get datalayer
     *
     * @return mixed
     */
    public function getDatalayer()
    {
        return $this->datalayer;
    }

    /**
     * @param mixed $datalayer
     *
     * @return Footer
     */
    public function setDatalayer($datalayer)
    {
        $this->datalayer = $datalayer;

        return $this;
    }

    /**
     * Get anchorId
     *
     * @return string
     */
    public function getAnchorId()
    {
        return $this->anchorId;
    }

    /**
     * @param string $anchorId
     *
     * @return Footer
     */
    public function setAnchorId($anchorId)
    {
        $this->anchorId = $anchorId;

        return $this;
    }

    /**
     * Get contactSection
     *
     * @return mixed
     */
    public function getContactSection()
    {
        return $this->contactSection;
    }

    /**
     * @param mixed $contactSection
     *
     * @return Footer
     */
    public function setContactSection($contactSection)
    {
        $this->contactSection = $contactSection;

        return $this;
    }

    /**
     * @return bool
     */
    public function displayContact()
    {
        return (boolean) $this->contactSection->getZoneAffichage();
    }

    /**
     * Get contactTitle
     *
     * @return string
     */
    public function getContactTitle()
    {
        return $this->contactSection->getZoneTitre();
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->contactSection->getZoneTitre2();
    }

    /**
     * Get linksSections
     *
     * @return mixed
     */
    public function getLinksSection()
    {
        return $this->linksSection;
    }

    /**
     * @param mixed $linksSection
     *
     * @return Footer
     */
    public function setLinksSection($linksSection)
    {
        $this->linksSection = $linksSection;

        return $this;
    }

    /**
     * Get allSitesLink
     *
     * @return array
     */
    public function getAllSitesLink()
    {
        $allLinks = $this->ctaFactory->create($this->linksSection->getCtaReferences());

        $link = array();

        if (count($allLinks) > 1) {
            $link = $allLinks[1];
        }

        return $link;
    }

    /**
     * Get allModelsLink
     *
     * @return array
     */
    public function getAllModelsLink()
    {

        $links = $this->ctaFactory->create($this->linksSection->getCtaReferences());

        return $links[0];
    }

    /**
     * Get legalSection
     *
     * @return mixed
     */
    public function getLegalSection()
    {
        return $this->legalSection;
    }

    /**
     * @param mixed $legalSection
     *
     * @return Footer
     */
    public function setLegalSection($legalSection)
    {
        $this->legalSection = $legalSection;

        return $this;
    }

    /**
     * Get legalLinks
     *
     * @return mixed
     */
    public function getLegalLinks()
    {
        return $this->ctaFactory->create($this->legalSection->getCtaReferences());
    }

    /**
     * Get legalText
     *
     * @return string
     */
    public function getLegalText()
    {
        return $this->legalSection->getZoneTexte();
    }

    /**
     * Get languages
     *
     * @return mixed
     */
    public function getLanguages()
    {
        return $this->getFooterLanguages();
    }


    /**
     * Data Transformer for Footer languages
     *
     * @param bool  $isMobile
     *
     * @return array
     */
    private function getFooterLanguages($isMobile = false)
    {
        $data = [];

        foreach ($this->languages as $language) {
            /**
             * @var PsaLanguage $language
             */
            $pageUrl = null;

            // get homepage
            $home = $this->pageFinder->getHomePage($this->siteId, $language->getLangueCode());

            //get other languages pages
            $pageDifferenteLangue = $this->pageFinder->getPageInDifferentLanguage(
                $this->currentPageId,
                $language->getLangueCode(),
                $this->siteId
            );

            if ($pageDifferenteLangue
                && $pageDifferenteLangue->getVersion() != null
                && $pageDifferenteLangue->getVersion()->getPageClearUrl()
            ) {
                $pageUrl = $pageDifferenteLangue->getVersion()->getPageClearUrl();
            } elseif ($home && $home->getVersion()->getPageClearUrl()) {
                $pageUrl = $home->getVersion()->getPageClearUrl();
            }

            if ($pageUrl) {
                $data[$language->getLangueId()] = array(
                    'title' => $language->getLangueCode(),
                    'mobileTitle'=>$language->getLangueTranslate(),
                    'target' => '_self',
                    'url' => $pageUrl,
                    'current' => ($language->getLangueId() == $this->currentLanguageId ? 'true' : 'false')
                );
            }

        }

        return $data;
    }

    /**
     * Get backToTopTitle
     *
     * @return string
     */
    public function getBackToTopTitle()
    {
        return $this->translator->trans('NDP_BACK_TOP_PAGE');
    }

    public function displaySiteMap()
    {
        return (boolean) $this->siteMapSection['pageZone']->getZoneLanguette();
    }

    /**
     * @return array
     */
    public function getSiteMapLinks()
    {
        return $this->getFooterLinksBySection();
    }

    /**
     * Generate Data for Site Map level 1
     *
     * @return array
     */
    private function getFooterLinksBySection()
    {
        $links = [];

        if ( ! empty($this->siteMapSection['siteMap'])) {
            $siteMap = $this->treeManager->createSiteMapTree($this->siteMapSection['siteMap']);

            if (isset($siteMap)) {
                $links = $this->getSiteMapCleanTree($siteMap);
            }
        }

        return $links;
    }

    /**
     * Clean tree of site map
     *
     * @param array $siteMapData
     *
     * @return array
     */
    private function getSiteMapCleanTree(array $siteMapData)
    {
        $sections = [];

        foreach ($siteMapData as $column) {
            $this->getSiteMapCleanLevel1($sections, $column);
        }

        return $sections;
    }

    /**
     * Clean tree of site map level1
     *
     * @param array $siteMapData
     * @param array $siteMap
     *
     * @return array
     */
    private function getSiteMapCleanLevel1(array &$siteMapData, array $siteMap)
    {
        foreach ($siteMap as $rub) {
            $level = $rub['rub'][0];
            $siteMapData[] = array(
                'title' => $level['title'],
                'links' => $this->getSiteMapCleanLevel2($level['subrub'])
            );
        }
    }

    /**
     * Clean tree of site map level2
     *
     * @param ArrayCollection $siteMapData
     *
     * @return array
     */
    private function getSiteMapCleanLevel2(ArrayCollection $siteMapData)
    {
        $links = [];

        foreach ($siteMapData as $level) {
            $links[] = array(
                'title' => $level['title'],
                'url' => $level['url']
            );
        }

        return $links;
    }

    /**
     * @return string
     */
    public function getSiteMapExpandLabel()
    {
        return $this->siteMapSection['pageZone']->getZoneLanguetteTexte();
    }

    /**
     * @return string
     */
    public function getFullMapLinkLabel()
    {
        return $this->siteMapSection['pageZone']->getZoneLabel();
    }

    /**
     * @return string
     */
    public function getFullMapLink()
    {
        $result = '#';
        if (isset($this->siteMapSection['fullMapLink'])) {
            $result = $this->siteMapSection['fullMapLink'];
        }

        return $result;
    }

    /**
     * Get socialSection
     *
     * @return mixed
     */
    public function getSocialSection()
    {
        return $this->socialLinks();
    }

    /**
     * @return string
     */
    public function getNewsletterTitle()
    {
        return $this->newsletterSection->getZoneTitre();
    }

    /**
     * @return string
     */
    public function getNewsletterUrl()
    {
       return $this->newsletterSection->getZoneUrl();
    }

    /**
     * @return bool
     */
    public function displayNewsletter()
    {
        return (boolean) $this->newsletterSection->getZoneAffichage();
    }

    /**
     * @return bool
     */
    public function displaySecondColumn()
    {
        return (boolean) $this->contextualSection->getZoneAffichage();
    }

    /**
     * @return string
     */
    public function getSecondColumnTitle()
    {
        return $this->contextualSection->getZoneTitre();
    }

    /**
     * @return array
     */
    public function getSecondColumnLinks()
    {
        return $this->ctaFactory->create($this->contextualSection->getCtaReferences());
    }

    /**
     * @return array
     */
    private function socialLinks()
    {
        $socialLinks = [];

        foreach ($this->socialSection as $socialNetwork) {
            $socialLinks[] = array(
                'title' => $socialNetwork['label'],
                'target' => '_self',
                'url' => $socialNetwork['urlWeb'],
                'img' => sprintf('%s%s', $this->mediaServer, $socialNetwork['mediaPath'])
            );
        }

        return $socialLinks;
    }

    /**
     * Get translations
     *
     * @return mixed
     */
    public function getTranslations()
    {
        $languages = $this->getFooterLanguages();
        $currentlanguage = $languages[$this->currentLanguageId];

        return array(
            'NDP_BACK_TOP_PAGE' => $this->translator->trans(
                'NDP_BACK_TOP_PAGE',
                array(),
                $this->siteId,
                $currentlanguage['title']
            ),
            'NDP_YOUR_EMAIL'=>$this->translator->trans('NDP_YOUR_EMAIL',array(),$this->siteId,$currentlanguage['title']),
            'NDP_OK'=>$this->translator->trans('NDP_OK',array(),$this->siteId,$currentlanguage['title']),
            'NDP_ERROR_EMAIL_REQUIRED'=>$this->translator->trans('NDP_ERROR_EMAIL_REQUIRED',array(),$this->siteId,$currentlanguage['title']),
            'NDP_ERROR_EMAIL_FORMAT'=>$this->translator->trans('NDP_ERROR_EMAIL_REQUIRED',array(),$this->siteId,$currentlanguage['title'])

        );
    }

    /**
     * @return string
     */
    public function getPageTypeCode()
    {
        return $this->pageTypeCode;
    }

    /**
     * @param string $pageTypeCode
     *
     * @return $this
     */
    public function setPageTypeCode($pageTypeCode)
    {
        $this->pageTypeCode = $pageTypeCode;

        return $this;
    }
}
