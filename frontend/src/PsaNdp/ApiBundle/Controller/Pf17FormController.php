<?php

namespace PsaNdp\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use OpenOrchestra\BaseApiBundle\Controller\Annotation as Api;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use PSA\MigrationBundle\Entity\Content\PsaContent;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
/**
 * Class JavascriptController
 * @package PsaNdp\ApiBundle\Controller
 *
 * @Config\Route("forms")
 */
class Pf17FormController extends Controller
{
    /**
     * Expected url paremeters zone
     *
     * @Config\Route("/{langueCode}/{siteId}/{mobile}/{contentId}", name="psa_ndp_dynjs_pf17")
     * @Config\Method({"GET"})
     * @Api\Serialize()
     *
     * @param string $langueCode
     * @param int    $siteId
     * @param bool    $mobile
     * @param int    $contentId
     *
     *      * @return FacadeInterface
     */
    public function indexAction($langueCode, $siteId, $mobile, $contentId)
    {

        /** @var PsaLanguage $langue */
        $langue = $this->get('psa_ndp_language_repository')->findOneBy(['langueCode'=>$langueCode]);

        /** @var PsaContent $content */
        $content = $this->get('psa_ndp_content_repository')->findOneBy(['contentId'=>$contentId,'langue'=>$langue]);

        $data = [
            'content' => $content,
            'mobile'  => $mobile,
        ];
        $result = $this->container
            ->get('open_orchestra_api.transformer_manager')
            ->get('pf17_forms')
            ->transform($data);

        return $result;
    }
}
