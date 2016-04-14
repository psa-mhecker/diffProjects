<?php
namespace PsaNdp\MappingBundle\Object\Block;

use PsaNdp\MappingBundle\Object\BlockTrait\Pt22MyPeugeotTrait;
use PsaNdp\MappingBundle\Object\Content;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Pt21NavigationData
 * @package PsaNdp\MappingBundle\Object\Block
 */
class Pt21NavigationData extends Content
{
    use Pt22MyPeugeotTrait;

    const TPL_DEALER_LOCATOR_G10 = 364;
    const TPL_DEALER_LOCATOR_G15 = 381;
    const TPL_DEALER_LOCATOR_G16 = 382;

    protected $mapping = array(
        'datalayer' => 'dataLayer',
    );

    /** @var object $homepage */
    protected $homepage = null;

    /** @var array $title */
    protected $title = [];

    /** @var array $search */
    protected $search = [];

    /** @var array $translate */
    protected $translate = [];

    /** @var array $menu */
    protected $menu = [];

    /** @var boolean $confishow */
    protected $confishow = false;

    /** @var array $logo */
    protected $logo = [];

    /** @var array $navigation */
    protected $navigation = [];

    /** @var array $footerNavigation */
    protected $footerNavigation = [];

    /** @var array $backToTop */
    protected $backToTop = [];

    /**
     * @return object
     */
    public function getHomepage()
    {
        return $this->homepage;
    }

    /**
     * @param object $homepage
     */
    public function setHomepage($homepage)
    {
        $this->homepage = $homepage;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        $this->title['url'] = (null !== $this->homepage->getVersion()) ? $this->homepage->getVersion()->getPageClearUrl() : '';

        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = array(
            'label' => $title,
            'url' => null
        );
    }

    /**
     * @return string
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param string $search
     */
    public function setSearch($search)
    {
        $this->search = array(
            'label' => $search,
            'url' => '#',
            'target' => '#'
        );
    }

    /**
     * @param array $translate
     */
    public function setTranslate(array $translate)
    {
        $this->translate = array(
            'backToTop' => array(
                'label' => $translate['NDP_BACK_TOP_PAGE'],
                'url' => '#body',
            ),
            'allPeugeot' => array(
                'label' => $translate['NDP_ALL_PEUGEOT'],
                'url' => '#'
            ),
            'menu' => $translate['NDP_MENU'],
            'ok_button' => $translate['NDP_OK'],
            'search_site' => $translate['NDP_PC38_RECHERCHER_SUR_LE_SITE'],
            'close' => $translate['NDP_CLOSE'],
            'nav' => $translate['NDP_MENU'],
            'NDP_SHOW_MORE' => $translate['NDP_SHOW_MORE'],
        );
    }

    /**
     * @return array
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @param array $menu
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;
    }

    /**
     * @return array
     */
    public function getConfishow()
    {
        return $this->confishow;
    }

    /**
     * @param array $confishow
     */
    public function setConfishow($confishow)
    {
        $this->confishow = $confishow;
    }

    /**
     * @return array
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param array $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    /**
     * @return array
     */
    public function getNavigation()
    {
        return $this->navigation;
    }

    /**
     * @param array $navigation
     */
    public function setNavigation($navigation)
    {
        $this->navigation = $navigation;
    }

    /**
     * @return array
     */
    public function getFooterNavigation()
    {
        return $this->footerNavigation;
    }

    /**
     * @param array $footerNavigation
     */
    public function setFooterNavigation($footerNavigation)
    {
        $this->footerNavigation = $footerNavigation;
    }

    /**
     * @return array
     */
    public function getBackToTop()
    {
        return $this->backToTop;
    }

    /**
     * @param array $backToTop
     */
    public function setBackToTop($backToTop)
    {
        $this->backToTop = $backToTop;
    }

    public function init(array $dataSource, $mediaServer, $isMobile)
    {
        $this->menu = $this->getNavigationData($dataSource);

        if ($isMobile) {

            $url = '';

            if (isset($dataSource['page']) && $dataSource['page']->getVersion()->getPageCode() === '1') {
                $url = $this->getTitle()['url'];
            }

            $this->logo = array(
                'src' => $mediaServer.'/design/frontend/mobile/img/logo-peugeot.png',
                'alt' => $this->translate['allPeugeot']['label'],
                'link' => array(
                    'url' => $url
                )
            );

            $this->navigation['links'] = $this->getNavigationData($dataSource, $isMobile);
        }

        if ($isMobile && $this->footerNavigation !== null) {
            $pageZoneFooterNavigation = $this->footerNavigation;

            $this->footerNavigation = $this->getFooterCtas($pageZoneFooterNavigation->getCtaReferences());
        }

    }

    /**
     * Generate Data for Navigation
     *
     * @param array $datasource
     *
     * @return array
     */
    private function getNavigationData($datasource, $isMobile = false)
    {
        $data = [];
        $siteMapData = $datasource['siteMapData'];

        if (isset($siteMapData)) {
            if ($isMobile){
                $data = $this->getSiteMapCleanTreeMobile($siteMapData);
            } else {
                $data = $this->getSiteMapCleanTree($siteMapData);
            }
        }

        return $data;
    }

    /**
     * Clean tree of Navigation
     *
     * @param array $siteMap
     *
     * @return array
     */
    private function getSiteMapCleanTree(array $siteMap)
    {
        $siteMapData = [];

        foreach ($siteMap as $column) {
            $this->getSiteMapCleanLevel1($siteMapData, $column);
        }

        return $siteMapData;
    }

    /**
     * Clean tree of site map level1
     *
     * @param array $siteMapData
     * @param array $siteMaps
     *
     * @return array
     */
    private function getSiteMapCleanLevel1(array &$siteMapData, array $siteMaps)
    {
        foreach ($siteMaps as $rub) {
            /** @var \PsaNdp\MappingBundle\Object\SiteMap $level */
            $level =  $rub['rub'][0];

            $data = [
                'libelle' => $level->getTitle(),
                'link' => array(
                    'url' => $level->getUrl(),
                    'target' => $level->getTarget(),
                ),
                'subMenu' => ($level->getDirectOpen() === 1) ? [] : $this->getSiteMapCleanLevel2($level->getChild())

            ];

            if( null !== $level->getQuickAccess() && $level->getDirectOpen() !== 1) {
                /** @var PsaPageZoneConfigurableInterface $quickAccess */
                $quickAccess = $level->getQuickAccess();

                $quickAccessData = array(
                    'title' => $quickAccess->getZoneTitre2(),
                    'link' => array(
                        'url' => '#'
                    ),
                    'libelle' => $this->getQuickAccessCtasData($level->getQuickAccess())
                );

                $data["subMenu"][] = $quickAccessData;
            }

            // RG_FO_PT21_08 : Si pas de rubrique de niveau 2, alors pas de sous-menu.
            if (count ($data["subMenu"]) === 0) {
                unset ($data["subMenu"]);
            }

            $siteMapData[] = $data;
        }
    }

    /**
     * Clean tree of site map level1
     *
     * @param array $siteMapData
     * @param array $siteMap
     *
     * @return array
     */
    private function getSiteMapCleanLevel1Mobile(array &$siteMapData, array $siteMap)
    {
        $homepage = $this->homepage->getVersion();
        $siteMapData[] = array(
            'title' => $homepage->getPageTitle(),
            'url' => $homepage->getPageUrlExterne() ? $homepage->getPageUrlExterne() : $homepage->getPageClearUrl(),
            'class' => ''
        );

        foreach ($siteMap as $rub) {
            $level =  $rub['rub'][0];

            $class = '';

            if (count($level->getChild()) > 0) {
                $class = 'menu-link';
            }

            if (array_search($level->getTemplateId(), [self::TPL_DEALER_LOCATOR_G10, self::TPL_DEALER_LOCATOR_G15, self::TPL_DEALER_LOCATOR_G16]) !== false) {
                $class = 'navicon-marker';
            }

            $data = array(
                'title' => $level->getTitle(),
                'url' => $level->getUrl(),
                'target' => $level->getTarget(),
                'class' => $class,
                'sousRubriques' => $this->getSiteMapCleanLevel2Mobile($level->getChild())
            );

            if( null !== $level->getQuickAccess()) {
                /** @var PsaPageZoneConfigurableInterface $quickAccess */
                $quickAccess = $level->getQuickAccess();

                $quickAccessData = array(
                    'title' => $quickAccess->getZoneTitre2(),
                    'url' => '',
                    "target" => "_self",
                    'sousRubriques' => array ('titleRubrique' => $quickAccess->getZoneTitre2(),
                        'itemsRubriques'=>$this->getQuickAccessCtasDataMobile($quickAccess),
                    ),);
                $data["sousRubriques"][] = $quickAccessData;
            }

            $siteMapData[] = $data;
        }
    }

    /**
     * Clean tree of site map level2
     *
     * @param ArrayCollection $siteMap
     *
     * @return array
     */

    private function getSiteMapCleanLevel2(ArrayCollection $siteMap)
    {
        $items = [];

        foreach ($siteMap as $level) {
            $data = array(
                'title' => $level->getTitle(),
                'link' => array(
                    'url' => $level->getUrl(),
                    'target' => $level->getTarget()
                ),
                'libelle' => $this->getSiteMapCleanLevel3($level['list'])
            );

            if (count($level['list']) > 5) {

                $data['more'] = array(
                    'libelle' => $this->translate['NDP_SHOW_MORE'],
                    'link' => array(
                        'url' => $level->getUrl(),
                        'target' => '_self'
                    )
                );
            }

            $items[]= $data;

            if (count($items) === 8) {
                break;
            }
        }
        return $items;
    }

    /**
     * Clean tree of site map level3
     *
     * @param ArrayCollection $siteMap
     *
     * @return array
     */
    private function getSiteMapCleanLevel3(ArrayCollection $siteMap)
    {
        $siteMapSmartyData = [];

        foreach ($siteMap as $level) {
            if (count($siteMapSmartyData) == 4 && count($siteMap) > 5) {
                continue;
            }

            $siteMapSmartyData[] = array(
                'title' => $level->getTitle(),
                'link' => array(
                    'url' => $level->getUrl(),
                    'target' => $level->getTarget()
                )
            );
        }

        return $siteMapSmartyData;
    }

    /**
     * Clean tree of Navigation
     *
     * @param array $siteMap
     *
     * @return array
     */
    private function getSiteMapCleanTreeMobile(array $siteMap)
    {
        $siteMapData = [];

        foreach ($siteMap as $column) {
            $this->getSiteMapCleanLevel1Mobile($siteMapData, $column);
        }

        return $siteMapData;
    }

    /**
     * Clean tree of site map level2
     *
     * @param ArrayCollection $siteMap
     *
     * @return array
     */
    private function getSiteMapCleanLevel2Mobile(ArrayCollection $siteMap)
    {
        $siteMapSmartyData = [];

        foreach ($siteMap as $level) {
            $data = array(
                'title' => $level->getTitle(),
                'url' => $level->getUrl(),
                'target' => $level->getTarget(),
                'sousRubriques' => array(
                    'titleRubrique' => $level->getTitle(),
                    'itemsRubriques' => $this->getSiteMapCleanLevel3Mobile($level['list'])
                )
            );

            if (empty($data['sousRubriques'][ 'itemsRubriques'])) {
                unset($data['sousRubriques']);
            }

            $siteMapSmartyData[] = $data;

            if (count($siteMapSmartyData) === 8) {
                break;
            }
        }

        return $siteMapSmartyData;
    }

    /**
     * Clean tree of site map level3
     *
     * @param ArrayCollection $siteMap
     *
     * @return array
     */
    private function getSiteMapCleanLevel3Mobile(ArrayCollection $siteMap)
    {
        $siteMapSmartyData = [];

        foreach ($siteMap as $level) {
            if (count($siteMapSmartyData) == 4 && count($siteMap) > 5) {
                continue;
            }

            $siteMapSmartyData[] = array(
                'title' => $level->getTitle(),
                'url' => $level->getUrl()
            );
        }

        return $siteMapSmartyData;
    }

    /**
     * Data Transformer for Quick Access Ctas
     *
     * @param PsaPageZone $quickAccess
     *
     * @return array
     */
    public function getQuickAccessCtasData($quickAccess)
    {
        $ctas = [];
        $ctaReferences = $quickAccess->getCtaReferences();

        foreach ($ctaReferences as $ctaReference) {
            $cta = $ctaReference->getCta();

            $data = array(
                'title' => $cta->getTitle(),
                'link' => array(
                    'url' => $cta->getAction(),
                    'target' => $ctaReference->getTarget(),
                )
            );

            $ctas[] = $data;
        }

        return $ctas;
    }
    public function getQuickAccessCtasDataMobile($quickAccess)
    {
        $ctas = [];
        $ctaReferences = $quickAccess->getCtaReferences();

        foreach ($ctaReferences as $ctaReference) {
            $cta = $ctaReference->getCta();

            $data = array(
                'title' => $cta->getTitle(),
                'link' => array(
                    'url' => $cta->getAction(),
                )
            );

            $ctas[] = $data;
        }

        return $ctas;
    }

    /**
     * Data Transformer for Mobile Footer CTAs
     *
     * @param Collection $ctaReferences
     *
     * @return array
     */
    public function getFooterCtas($ctaReferences)
    {
        $ctas = [];

        foreach ($ctaReferences as $ctaReference) {
            $cta = $ctaReference->getCta();

            $ctas[] = array(
                'title' => $cta->getTitle(),
                'url' => $cta->getAction()
            );
        }

        return $ctas;
    }

}