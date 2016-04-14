<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Entity\PsaPageTypesCode;
use PsaNdp\MappingBundle\Services\PageFinder;
use PsaNdp\WebserviceConsumerBundle\Webservices\AnnuairePointDeVente;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class Pf11RecherchePointDeVenteDataSource.
 */
class Pf11RecherchePointDeVenteDataSource extends AbstractDataSource
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var AnnuairePointDeVente
     */
    private $dealerDirectory;

    /**
     * @var PageFinder
     */
    private $pageFinder;

    /**
     * @param RouterInterface      $router
     * @param AnnuairePointDeVente $dealerDirectory
     * @param PageFinder           $pageFinder
     */
    public function __construct(
        RouterInterface $router,
        AnnuairePointDeVente $dealerDirectory,
        PageFinder $pageFinder
    ) {
        $this->router = $router;
        $this->dealerDirectory = $dealerDirectory;
        $this->pageFinder = $pageFinder;
    }

    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input
     * BlockInterface and current url Request.
     *
     * @param ReadBlockInterface $block
     * @param Request            $request  Current url request displaying th block
     * @param bool               $isMobile Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {

        /* @var $block PsaPageZoneConfigurableInterface */
        $data['block'] = $block;
        $data['site'] = $block->getPage()->getSite();

        $data['isFull'] = $this->isFull();

        if (!$data['isFull']) {
            $data['urlRedirection'] = $this->getDealerLocatorPageUrl();
        } else {
            $data['urlJson'] = $this->generateUrlJson();
        }

        return $data;
    }

    /**
     * Return url to fetch the json data.
     *
     * @return string
     */
    private function generateUrlJson()
    {
        return $this->router->generate(
            'psa_ndp_api_search_sales_points_list',
            [
                'siteId' => $this->getBlock()->getPage()->getSiteId(),
                'langueCode' => $this->getBlock()->getLangue()->getLangueCode(),
                'countryCode' => $this->getBlock()->getPage()->getSite()->getCountryCode(),
                'minResultsPointOfSale' => intval($this->getBlock()->getZoneTitre4()),
                'minResultsNewVehicleDistributor' => intval($this->getBlock()->getZoneTitre5()),
                'maxResults' => $this->getBlock()->getZoneTitre2(),
                'radius' => $this->getBlock()->getZoneTitre3(),
                'filterMode' => $this->getBlock()->getZoneCriteriaId2(),
                'resultsMode' => $this->getBlock()->getZoneCriteriaId(),
                'enableSearchByName'=>$this->getBlock()->getZoneCriteriaId3()
            ]
        );
    }

    /**
     * Return url to redirect for search.
     *
     * @return string
     */
    private function getDealerLocatorPageUrl()
    {
        $dealerLocatorPageUrl = null;

        $dealerLocatorPage = $this->pageFinder->getDealerLocator(
            $this->getBlock()->getPage()->getSiteId(),
            $this->getBlock()->getPage()->getLanguage()
        );

        if ($dealerLocatorPage) {
            $dealerLocatorPageUrl = $dealerLocatorPage->getUrl();
        }

        return $dealerLocatorPageUrl;
    }

    /**
     * @return bool
     */
    private function isFull()
    {
        return ($this->getPage()->getTypeCode() == PsaPageTypesCode::PAGE_TYPE_CODE_DEALER_LOCATOR);
    }
}
