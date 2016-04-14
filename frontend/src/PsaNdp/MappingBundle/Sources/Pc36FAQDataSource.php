<?php

namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use Symfony\Component\HttpFoundation\Request;
use PSA\MigrationBundle\Entity\Page\PsaPageZone;
use PSA\MigrationBundle\Entity\Content\PsaContentCategory;
use PSA\MigrationBundle\Entity\Content\PsaContentCategoryCategory;
use PSA\MigrationBundle\Repository\PsaContentCategoryRepository;
use PSA\MigrationBundle\Repository\PsaContentRepository;
use PSA\MigrationBundle\Repository\PsaContentTypeRepository;
use Symfony\Component\Routing\RouterInterface;

/**
 * Data source fro Pc36FAQ block
 */
class Pc36FAQDataSource extends AbstractDataSource
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var PsaContentRepository
     */
    private $contentRepository;

    /**
     * @var PsaContentCategoryRepository
     */
    private $contentCategoryRepository;

    /**
     * @var PsaContentTypeRepository
     */
    private $contentTypeRepository;

    protected $siteId;
    protected $langId;
    protected $categoryId;
    protected $pageClearUrl;
    protected $pageId;
    protected $zoneTemplateId;

    /**
     * @param RouterInterface              $router
     * @param PsaContentRepository         $contentRepository
     * @param PsaContentCategoryRepository $contentCategoryRepository
     * @param PsaContentTypeRepository     $contentTypeRepository
     */
    public function __construct(RouterInterface $router, PsaContentRepository $contentRepository, PsaContentCategoryRepository $contentCategoryRepository, PsaContentTypeRepository $contentTypeRepository)
    {
        $this->router = $router;
        $this->contentRepository = $contentRepository;
        $this->contentCategoryRepository = $contentCategoryRepository;
        $this->contentTypeRepository = $contentTypeRepository;
    }

    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input BlockInterface and current url Request
     *
     * @param  ReadBlockInterface $block
     * @param  Request            $request  Current url request displaying th block
     * @param  bool               $isMobile Indicate if is a mobile display
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        $data = [];

        /** @var PsaPageZone $block */
        $pageZone = $block;

        // Get data input parameters
        $this->siteId = $pageZone->getPage()->getSiteId();
        $this->langId = $pageZone->getLangue()->getLangueId();
        $this->categoryId = $request->attributes->get('cat');
        $this->pageClearUrl = $pageZone->getPage()->getVersion()->getPageClearUrl();

        $this->pageId = $pageZone->getPageId();
        $this->zoneTemplateId = $pageZone->getZoneTemplateId();

        // Get data
        if (!$isMobile) {
            $data = $this->getFaqData();
        }
        if ($isMobile) {
            $faqParam = $request->attributes->get('faq');

            switch ($faqParam) {
                case 'list':
                    $data = $this->getFaqListMobileData();
                    break;
                default:
                    $data = $this->getFaqFirstLevelMobileData();
                    break;
            }
        }

        $data['pageZone'] = $pageZone;

        return $data;
    }

    /**
     * Return faq data for desktop
     *
     * @return array
     */
    public function getFaqData()
    {
        $desktop = true;

        $firstLevelCat = $this->contentCategoryRepository
            ->getFaqFirstLevelCategoriesForSiteAndLang($this->siteId, $this->langId);

        $result['firstLevelData'] = $this->getFirstLevelData($firstLevelCat, $desktop);
        $result['selectedCatAndQuestions'] = $this->getSelectedCatAndQuestionsTree($firstLevelCat);

        return $result;
    }

    /**
     * Return faq smarty data to display first level categories for mobile
     *
     * @return array
     */
    public function getFaqFirstLevelMobileData()
    {
        $result = [];
        $desktop = false;

        $firstLevelCat = $this->contentCategoryRepository
            ->getFaqFirstLevelCategoriesForSiteAndLang($this->siteId, $this->langId);

        $result['firstLevelData'] = $this->getFirstLevelData($firstLevelCat, $desktop);

        return $result;
    }

    /**
     * Return faq smarty data to list of question for specific categories for mobile
     *
     * @return array
     */
    public function getFaqListMobileData()
    {
        $result = [];

        $firstLevelCat = $this->contentCategoryRepository
            ->getFaqFirstLevelCategoriesForSiteAndLang($this->siteId, $this->langId);

        $result['selectedCatAndQuestions'] = $this->getSelectedCatAndQuestionsTree($firstLevelCat);
        $result['faqBackURL'] = $this->pageClearUrl;

        return $result;
    }

    /**
     * @param array $firstLevelCat
     * @param bool $desktop
     *
     * @return array
     */
    private function getFirstLevelData(array $firstLevelCat, $desktop)
    {
        $result = [];

        foreach ($firstLevelCat as $category) {
            /** @var PsaContentCategory $category */
            $url = null;

            if($desktop) {
                $url = $this->getUrlFaqJsonDesktop($category->getContentCategoryId());
            }

            if(!$desktop) {
                $url = $this->getUrlFaqListMobile($category->getContentCategoryId());
            }

            $newCat = array(
                'title' => $category->getContentCategoryLabel(),
                'url' => $url
            );
            if ($this->categoryId === $category->getContentCategoryId()) {
                $newCat['selected'] = true;
            }

            $result[] = $newCat;

            // RG_FO_PC36_03 : 8 entrées maximum
            if (count($result) == 8) {
                break;
            }
        }

        return $result;
    }

    /**
     * @param array $firstLevelCat
     * @param int   $selectedCatId
     *
     * @return array
     */
    private function getSelectedCat(array $firstLevelCat, $selectedCatId)
    {
        $result = null;
        $selectedCatId = (int) $selectedCatId;

        foreach ($firstLevelCat as $category) {
            /** @var PsaContentCategory $category */
            if ($selectedCatId === $category->getContentCategoryId()) {
                $result = $category;
                break;
            }
        }

        return $result;
    }

    /**
     * @param array $firstLevelCat
     *
     * @return array
     */
    private function getSelectedCatAndQuestionsTree(array $firstLevelCat)
    {
        $result['parentTitle'] = '';
        $result['subCatArray'] = [];
        /** @var PsaContentCategory $selectedCat */
        $selectedCat = $this->getSelectedCat($firstLevelCat, $this->categoryId);

        if ($selectedCat !== null) {
            $result['parentTitle'] = $selectedCat->getContentCategoryLabel();

            foreach ($selectedCat->getChildCategories() as $childCatCat) {
                /** @var PsaContentCategoryCategory $childCatCat */
                /** @var PsaContentCategory $childCat */
                $childCat = $childCatCat->getChild();

                $newCat['catTitle'] = $childCat->getContentCategoryLabel();
                $newCat['questions'] = $this->contentRepository
                    ->findFaqByCategoryIdSiteIdAndLanguageId($childCat->getContentCategoryId(), $this->siteId, $this->langId);

                $result['subCatArray'][] = $newCat;
            }
        }

        return $result;
    }

    /**
     * @param $categoryId
     *
     * @return string
     */
    private function getUrlFaqJsonDesktop($categoryId)
    {
        return $this->router->generate(
            $this->getDefaultFaqRouteName(),
            array('siteId' => $this->siteId, 'langId' => $this->langId, 'categoryId' => $categoryId, 'pageId' => $this->pageId, 'zoneTemplateId' => $this->zoneTemplateId)
        );
    }

    /**
     * @param $categoryId
     *
     * @return string
     */
    private function getUrlFaqListMobile($categoryId)
    {
        $params = '?cat=' . intval($categoryId) . '&faq=list';

        return $this->pageClearUrl . $params;
    }

    private function getDefaultFaqRouteName()
    {
        return 'psa_ndp_api_pc36_faq_desktop';
    }
}
