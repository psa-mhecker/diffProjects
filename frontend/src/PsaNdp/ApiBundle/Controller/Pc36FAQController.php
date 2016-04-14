<?php
namespace PsaNdp\ApiBundle\Controller;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZone;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use OpenOrchestra\BaseApiBundle\Controller\Annotation as Api;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Pc36FAQController
 *
 * @Config\Route("faq")
 */
class Pc36FAQController extends Controller
{
    /**
     * @param Request $request
     * @param $siteId
     * @param $langId
     * @param $categoryId
     * @param $pageId
     * @param $zoneTemplateId
     *
     * @Config\Route("/desktop/{siteId}/{langId}/{categoryId}/{pageId}/{zoneTemplateId}", name="psa_ndp_api_pc36_faq_desktop")
     * @Config\Method({"GET"})
     *
     * @Api\Serialize()
     *
     * @throw RuntimeException
     *
     * @return FacadeInterface
     */
    public function showDesktopAction(Request $request, $siteId, $langId, $categoryId, $pageId, $zoneTemplateId)
    {
        $jsonResult = $this->generateDesktopJson($siteId, $langId, intval($categoryId), $pageId, $zoneTemplateId);

        return $jsonResult;
    }

    /**
     * @param int $siteId
     * @param int $langId
     * @param int $categoryId
     * @param int $pageId
     * @param int $zoneTemplateId
     *
     * @return mixed
     */
    protected function generateDesktopJson($siteId, $langId, $categoryId, $pageId, $zoneTemplateId)
    {
        $contentCategoryRepository = $this->container->get('psa_ndp_content_category_repository');
        $contentCategory = $contentCategoryRepository->findOneByContentCategoryId($categoryId);

        $additionalData = [];
        $pageZoneRepository = $this->container->get('psa_ndp_page_zone_repository');

        $pageZone = $pageZoneRepository->findPageZoneByLanguePageAndZone($langId, $pageId, $zoneTemplateId);
        if ($pageZone && isset($pageZone['zoneTexte'])) {
            $additionalData['surveyNo'] = $pageZone['zoneTexte'];
        }

        return $this->container->get('open_orchestra_api.transformer_manager')->get('faq_desktop')->setAdditionalData($additionalData)->transform($contentCategory);
    }
}
