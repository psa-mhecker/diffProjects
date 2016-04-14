<?php
namespace PsaNdp\MappingBundle\Sources;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Page\PsaPageZone;
use PsaNdp\MappingBundle\Entity\PsaAppliMobile;
use PsaNdp\MappingBundle\Entity\PsaSitesEtWebservicesPsa;
use PsaNdp\MappingBundle\Helper\CtaHelper;
use PsaNdp\MappingBundle\Object\Cta;
use PsaNdp\MappingBundle\Repository\PsaAppliMobileRepository;
use PsaNdp\MappingBundle\Repository\PsaSitesEtWebservicesPsaRepository;
use Symfony\Component\HttpFoundation\Request;


/**
 * Data source
 */
class Pt22MyPeugeotDataSource extends AbstractDataSource
{

    /**
     * @var PsaSitesEtWebservicesPsaRepository
     */
    private $sitesEtWebservicesPsaRepository;

    /**
     * @var PsaAppliMobileRepository
     */
    private $appliMobileRepository;


    /**
     * @param PsaSitesEtWebservicesPsaRepository $sitesEtWebservicesPsaRepository
     * @param PsaAppliMobileRepository           $appliMobileRepository
     */
    public function __construct( PsaSitesEtWebservicesPsaRepository $sitesEtWebservicesPsaRepository,
                                PsaAppliMobileRepository $appliMobileRepository)
    {
        $this->sitesEtWebservicesPsaRepository = $sitesEtWebservicesPsaRepository;
        $this->appliMobileRepository = $appliMobileRepository;
    }

    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input BlockInterface and current url Request
     *
     * @param ReadBlockInterface $block
     * @param Request            $request  Current url request displaying th block
     * @param bool               $isMobile Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        /** @var PsaPageZone $block */
        $sitesEtWebservicesPsa = $this->sitesEtWebservicesPsaRepository->findOneBySiteId($block->getPage()->getSiteId());
        $data = [];
        $data['pageZone'] = $block;
        $data['ctaSignIn'] = $this->getCTASignIn($sitesEtWebservicesPsa, $isMobile);
        $data['ctaSignUp'] = $this->getCTASignUp($sitesEtWebservicesPsa, $isMobile);
        $data['pushMobile'] = $this->getPushMobile($block, $isMobile);

        return $data;
    }

    /**
     * @param PsaSitesEtWebservicesPsa $sitesEtWebservicesPsa
     * @param $isMobile
     * @return array
     */
    protected function getCTASignIn(PsaSitesEtWebservicesPsa $sitesEtWebservicesPsa = null, $isMobile = false) {
        $result = [];

        if($sitesEtWebservicesPsa) {
            if ($isMobile) {
                $result = array(
                    'style' => 'cta',
                    'url' => $sitesEtWebservicesPsa->getZoneUrlMobileConnexion(),
                    'version' => Cta::NDP_CTA_VERSION_LIGHT_BLUE,
                    'dimension' => '12'
                );
            }

            if (!$isMobile) {
                $result = array(
                    "url" => $sitesEtWebservicesPsa->getZoneUrlWebConnexion(),
                    "target" => "_blank"
                );
            }
        }

        return $result;
    }

    /**
     * @param PsaSitesEtWebservicesPsa $sitesEtWebservicesPsa
     * @param $isMobile
     * @return array
     */
    protected function getCTASignUp(PsaSitesEtWebservicesPsa $sitesEtWebservicesPsa = null, $isMobile = false) {
        $result = [];

        if($sitesEtWebservicesPsa) {
            if ($isMobile) {
                $result = array(
                    'style' => 'cta',
                    'url' => $sitesEtWebservicesPsa->getZoneUrlMobileAccueil(),
                    'version' => CtaHelper::NDP_CTA_VERSION_NIVEAU4,
                    'dimension' => '12'
                );
            }

            if (!$isMobile) {
                $result = array(
                    "url" => $sitesEtWebservicesPsa->getZoneUrlWebAccueil(),
                    "target" => "_blank"
                );
            }
        }

        return $result;
    }

    /**
     * @param PsaPageZone $pageZone
     * @param bool $isMobile
     * @return array|null
     */
    protected function getPushMobile(PsaPageZone $pageZone, $isMobile = false) {
        $result = null;

        if (!$isMobile && $pageZone->getZoneAttribut() === 1) {
            $result = [];

            // Image de fond (obligatoire si la zone push media est active)
            $media = $pageZone->getMedia();
            $result['mediaPath'] = $media->getMediaPath();

            // Badges Application

            /** @var PsaAppliMobile $appliMobile */
            $appliMobile = $this->appliMobileRepository->findOneBySiteAndLanguageAndId(
                $pageZone->getPage()->getSite(),
                $pageZone->getLangue(),
                $pageZone->getZoneAttribut2()
            );

            if ($appliMobile) {
                $result['urlGooglePlay'] = $appliMobile->getUrlGooglePlay();
                $result['mediaGooglePlay'] = $appliMobile->getMediaGooglePlay();
                $result['urlAppleStore'] = $appliMobile->getUrlAppleStore();
                $result['mediaAppleStore'] = $appliMobile->getMediaAppleStore();
                $result['urlWindows']    = $appliMobile->getUrlWindows();
                $result['mediaWindows']    = $appliMobile->getMediaWindows();
                $result['modeOuverture'] = $appliMobile->getModeOuverture();
            }
        }

        return $result;
    }
}
