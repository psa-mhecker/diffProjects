<?php namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Cta\PsaContentVersionCta;
use PSA\MigrationBundle\Entity\Media\PsaMedia;
use PSA\MigrationBundle\Repository\PsaMediaRepository;
use PSA\MigrationBundle\Repository\PsaPageRepository;
use PsaNdp\MappingBundle\Entity\PsaSitesEtWebservicesPsa;
use PsaNdp\MappingBundle\Repository\PsaSitesEtWebservicesPsaRepository;
use Symfony\Component\HttpFoundation\Request;
use \PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PSA\MigrationBundle\Entity\Content\PsaContent;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class Pf17FormulairesDataSource
 * @package PsaNdp\MappingBundle\Sources
 */
class Pf17FormulairesDataSource extends AbstractDataSource
{

    const URL_PATH_FORMS = '/forms';
    const URL_PATH_DESKTOP_FORMS = '/desktop';
    const URL_PATH_MOBILE_FORMS = '/mobile';
    const URL_PATH_PARAMETER_SEPARATOR_FORMS = '/';
    const SHOWROOM_TEMPLATE_PAGE_ID = 378;
    const NDP_TYPE_CAR = 'NDP_TYPE_CAR';
    const NDP_TYPE_CAR_FORMS_VALUE = 'CAR';
    const NDP_TYPE_PDV = 'NDP_TYPE_PDV';
    const NDP_TYPE_PDV_FORMS_VALUE = 'PDV';
    const TYPE_CTA = "FORM_CTA";
    const CTA_MYPEUGEOT = 'CTA_MYPEUGEOT';


    /**
     * @var PsaPageRepository
     */
    private $pageRepository;

    /**
     * @var PsaMediaRepository
     */
    private $mediaRepository;

    /**
     * @var PsaSitesEtWebservicesPsaRepository
     */
    private $sitesEtWebservicesPsaRepository;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param PsaPageRepository                  $pageRepository
     * @param PsaMediaRepository                 $psaMediaRepository
     * @param PsaSitesEtWebservicesPsaRepository $sitesEtWebservicesPsaRepository
     * @param RouterInterface                    $router
     */
    public function __construct(
        PsaPageRepository $pageRepository,
        PsaMediaRepository $psaMediaRepository,
        PsaSitesEtWebservicesPsaRepository $sitesEtWebservicesPsaRepository,
        RouterInterface $router
    ) {
        $this->pageRepository = $pageRepository;
        $this->mediaRepository = $psaMediaRepository;
        $this->sitesEtWebservicesPsaRepository = $sitesEtWebservicesPsaRepository;
        $this->router = $router;
    }

    /**
     * @inheritdoc
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        /* @var $block PsaPageZoneConfigurableInterface */
        $siteId = $block->getPage()->getSiteId();

        $oPsaSitesEtWebservicesPsa = $this->sitesEtWebservicesPsaRepository->findOneBySiteId($siteId);
        $site = $block->getPage()->getSite();

        $data = [
            'lang' => $block->getPage()->getLangue()->getLangueCode(),
            'country' => $site->getCountryCode(),
            'culture' => sprintf('%s-%s', $block->getPage()->getLangue()->getLangueCode(), $site->getCountryCode()),
            'content' => null,
            'block' => $block,
            'myPeugeotUrl' => $this->initCTAMyPeugeotURL($isMobile, $oPsaSitesEtWebservicesPsa),
        ];

        $psaContent = $block->getContent();

        $data['content'] = $psaContent;

        $data['urlJson'] = $this->getUrlJson($psaContent, $isMobile);

        $data['contentCtaList'] = $psaContent->getCurrentVersion()->getCtasByRefType(self::TYPE_CTA);
        $data['ctaMyPeugeot'] = $psaContent->getCurrentVersion()->getCtasByRefType(self::CTA_MYPEUGEOT);

        if ($request->get('lcdv16')) {
            $data['lcdv16'] = $request->get('lcdv16');
        }

        return $data;
    }

    /**
     * @param $isMobile
     * @param PsaSitesEtWebservicesPsa $oPsaSitesEtWebservicesPsa
     *
     * @return string
     */
    private function initCTAMyPeugeotURL($isMobile, PsaSitesEtWebservicesPsa $oPsaSitesEtWebservicesPsa = null)
    {
        if ($oPsaSitesEtWebservicesPsa && intval($oPsaSitesEtWebservicesPsa->getZoneMyPeugeot()) === 1) {
            if ($isMobile) {
                return $oPsaSitesEtWebservicesPsa->getZoneUrlMobileAccueil();
            }

            return $oPsaSitesEtWebservicesPsa->getZoneUrlWebAccueil();
        }

        return '#';
    }

    /**
     * @param PsaContent $content
     * @param $isMobile
     *
     * @return string
     */
    private function getUrlJson(PsaContent $content, $isMobile)
    {
        return $this->router->generate('psa_ndp_dynjs_pf17',array(
            'siteId'=> $content->getSite()->getSiteId(),
            'langueCode'=> $content->getLangue()->getLangueCode(),
            'contentId'=> $content->getContentId(),
            'mobile'=> (int) $isMobile,
        ));

    }
}
