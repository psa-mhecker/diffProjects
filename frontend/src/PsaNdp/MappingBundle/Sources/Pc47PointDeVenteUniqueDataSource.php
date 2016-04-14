<?php

namespace PsaNdp\MappingBundle\Sources;

use Doctrine\Common\Collections\Collection;

use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use PSA\MigrationBundle\Entity\Content\PsaContentZone;
use PSA\MigrationBundle\Entity\Cta\AbstractPsaCtaReference;
use PSA\MigrationBundle\Entity\Cta\PsaPageZoneCta;
use PSA\MigrationBundle\Entity\Site\PsaSiteService;
use Symfony\Component\HttpFoundation\Request;
use PSA\MigrationBundle\Entity\Page\PsaPageZone;
use PSA\MigrationBundle\Repository\PsaPageZoneRepository;
use PSA\MigrationBundle\Repository\PsaContentRepository;
use PSA\MigrationBundle\Repository\PsaContentZoneRepository;
use PSA\MigrationBundle\Repository\PsaSiteServiceRepository;
use Symfony\Component\Routing\RouterInterface;

/**
 * Data source for Pc47PointDeVenteUnique block
 */
class Pc47PointDeVenteUniqueDataSource extends AbstractDataSource
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var PsaPageZoneRepository
     */
    private $pageZoneRepository;

    /**
     * @var PsaContentRepository
     */
    private $contentRepository;

    /**
     * @var PsaContentZoneRepository
     */
    private $contentZoneRepository;

    /**
     * @var PsaSiteServiceRepository
     */
    private $siteServiceRepository;

    /**
     * @param RouterInterface          $router
     * @param PsaPageZoneRepository    $pageZoneRepository
     * @param PsaContentRepository     $contentRepository
     * @param PsaContentZoneRepository $contentZoneRepository
     * @param PsaSiteServiceRepository $siteServiceRepository
     */
    public function __construct(
        RouterInterface $router,
        PsaPageZoneRepository $pageZoneRepository,
        PsaContentRepository $contentRepository,
        PsaContentZoneRepository $contentZoneRepository,
        PsaSiteServiceRepository $siteServiceRepository
    )
    {
        $this->router = $router;
        $this->pageZoneRepository = $pageZoneRepository;
        $this->contentRepository = $contentRepository;
        $this->contentZoneRepository = $contentZoneRepository;
        $this->siteServiceRepository = $siteServiceRepository;
    }

    /**
     * Return Data array from different sources such as Database, parameters, webservices... depending of its input BlockInterface and current url Request
     *
     * @param ReadBlockInterface $block
     * @param Request            $request  Current url request displaying th block
     * @param bool               $isMobile Indicate if is a mobile display

     * @return array
     */
    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        /* @var $block PsaPageZone */
        $pageZone = $block;

        // ** Fetch all DB datas
        $content = $this->pageZoneRepository->findRelatedContentFromPageZone($pageZone);
        $contentVersion = $content->getCurrentVersion();
        // ContentZones for closing days, promotions, services
        $contentZones = $this->contentZoneRepository->findByIdLangAndVersion(
            $contentVersion->getContentId(),
            $contentVersion->getLangue(),
            $contentVersion->getContentVersion()
        );
        // CTAs for Web and mobile
        $ctasReference = $contentVersion->getCtaReferences();

        // ** Titles
        $data = $this->getTitles($pageZone, $isMobile);

        // ** Contact information
        $data['posName'] = strtoupper($contentVersion->getContentTitle());
        $data['posAdress'] = $contentVersion->getContentTitle2();
        $data['posPhone'] = $contentVersion->getContentTitle3();

        // ** Schedules
        // json expected format : '{"1": {"1O": "08:00","1C": "12:00","2O": "14:00","2C": "20:00"},"2": {"1O": "08:00","1C": "12:00","2O": "14:00","2C": "20:20" },"3": {"1O": "08:00","1C": "12:00","2O": "14:00","2C": "19:00" },"4": {"1O": "08:00","1C": "12:00","2O": "14:00","2C": "20:25"},"5": {"1O": "08:00","1C": "12:00","2O": "14:00","2C": "20:00"},"6": {"1O": "08:00","1C": "12:00","2O": "","2C": ""},"7": {"1O": "","1C": "","2O": "","2C": ""}}'
        //                         with "1" => Monday, "2"=> Tuesday... "1O" => opening morning, "2C » => closing morning, "2O" => opening afternoon, "2C" => closing afternoon
        $data['schedule'] = json_decode($contentVersion->getContentText(), true);

        // ** Closing days
        // Not displayed in FO for now. (cf Spec.)
        // $data['closingDays'] = $this->getClosingDaysData($contentZones);

        // ** Promotions
        $data['promotions'] = $this->getPromotionsData($contentZones);

        // ** Services
        $data['services'] = $this->getServicesData($contentZones);

        // ** CTAs web and mobile
        $data = array_merge($data, $this->getCtasData($ctasReference, $isMobile));

        // ** Generate Google Map Address
        if ($isMobile) {
            /** @var PsaSiteService $siteService */
            // Note : google site service should be transverse for all languages (set to null for the findBy)
            $siteService = $this->siteServiceRepository->findOneBy([
                'site' => $pageZone->getPage()->getSite(),
                'serviceCode' => PsaSiteService::SERVICE_CODE_GOOGLEMAP4WORK,
                'langue' => null
            ]);

            $data['googleMapUrl'] = '';
            if ($siteService !== null) {
                $googleMap = new GoogleMapUtils($siteService->getClientId(), $siteService->getConsumerKey());
                $data['googleMapUrl'] = $googleMap->createGoogleMapUrl(['markers' => $data['posAdress'], 'size' => '640x160', 'zoom' => 17]);
            }
        }

        // ** Url for VCF download
        $data['vcfUrl'] = $this->router->generate(
            $this->getVcfDownloadRouteName(),
            array('langId' => $pageZone->getLangueId(), 'contentId' => $content->getContentId())
        );

        return $data;
    }

    private function getVcfDownloadRouteName()
    {
        return 'pc47_vcf_contact_content';
    }

    /**
     * @todo get Titles from Trad
     *
     * @param PsaPageZone $pageZone
     * @param bool        $isMobile Indicate if is a mobile display
     *
     * @return array
     */
    private function getTitles(PsaPageZone $pageZone, $isMobile)
    {
        $result = [];

        $result['title'] = $pageZone->getZoneTitre();
        $result['promotionTitle'] = strtoupper('Promotions point de vente');

        if ($pageZone->getZoneAttribut() === 1) {
            $result['contactVcfTitle'] = 'Fiche Contact (VCf)';
        }

        if (!$isMobile) {
            $result['contactTitle'] = 'Contact';
            $result['serviceTitle'] = 'Services';
        }

        if ($isMobile) {
            $result['mobileScheduleTitle'] = strtoupper("HORAIRES D'OUVERTURE");
            $result['mobileServicesTitle'] = strtoupper('SERVICES');
        }

        return $result;
    }

    /**
     * Return point of sales closing days data
     * Note: Not used in FO for now. (cf Spec.)
     *
     * @param array $contentZones
     *
     * @return array
     *
     */
    private function getClosingDaysData(array $contentZones)
    {
        $result = [];

        foreach ($contentZones as $contentZone) {
            /** @var PsaContentZone $contentZone */

            if ($contentZone->getContentZoneType() === PsaContentZone::CONTENT_ZONE_TYPE_JOUR_FERMETURE) {
                $closingDayData = [];

                if ($contentZone->getContentZoneDateBegin() !== null) {
                    $closingDayData['start'] = $contentZone->getContentZoneDateBegin()->format('d-m-Y');
                }

                if ($contentZone->getContentZoneDateEnd() !== null) {
                    $closingDayData['end'] = $contentZone->getContentZoneDateEnd()->format('d-m-Y');
                }

                $result[] = $closingDayData;
            }
        }

        return $result;
    }

    /**
     * Return point of sales promotions data
     *
     * @param array $contentZones
     *
     * @return array
     *
     */
    private function getPromotionsData(array $contentZones)
    {
        $result = [];
        $today = new \DateTime();

        foreach ($contentZones as $contentZone) {
            /** @var PsaContentZone $contentZone */

            if ($contentZone->getContentZoneType() === PsaContentZone::CONTENT_ZONE_TYPE_PROMOTION
                && $contentZone->getContentZoneDateBegin() <= $today
                && $contentZone->getContentZoneDateEnd() >= $today) {
                $promotionData = [];

                $promotionData['text'] = $contentZone->getContentZoneTitle();

                if ($contentZone->getContentZoneUrl() !== null && $contentZone->getContentZoneUrl() !== '') {
                    $promotionData['url'] = $contentZone->getContentZoneUrl();
                    $promotionData['target'] = $contentZone->getContentZoneLabel();
                }

                $result[] = $promotionData;
            }
        }

        return $result;
    }

    /**
     * Return point of sales services data
     *
     * @param array $contentZones
     *
     * @return array
     */
    private function getServicesData(array $contentZones)
    {
        $result = [];

        foreach ($contentZones as $contentZone) {
            /** @var PsaContentZone $contentZone */

            if ($contentZone->getContentZoneType() === PsaContentZone::CONTENT_ZONE_TYPE_SERVICE) {
                $serviceData = [];

                $serviceData['title'] = $contentZone->getContentZoneTitle();
                $serviceData['name'] = $contentZone->getContentZoneLabel();
                $serviceData['tel'] = $contentZone->getContentZoneLabel2();
                $serviceData['email'] = $contentZone->getContentZoneLabel3();
                $serviceData['file'] = '';
                $serviceData['alt'] = '';

                if ($contentZone->getMedia() !== null) {
                    $serviceData['file'] = $this->getMediaServer() . $contentZone->getMedia()->getMediaPath();
                    $serviceData['alt'] = $contentZone->getMedia()->getMediaAlt();
                }

                $result[] = $serviceData;
            }
        }

        return $result;
    }

    /**
     * @param Collection $ctasReference
     * @param bool       $isMobile      Display mode is desktop or mobile
     *
     * @return array
     */
    private function getCtasData(Collection $ctasReference, $isMobile)
    {
        $result = [];
        $ctaRefType = $isMobile ? AbstractPsaCtaReference::CTA_REF_TYPE_MOBILE : AbstractPsaCtaReference::CTA_REF_TYPE_WEB;
        $missingOnePicto = false;

        foreach ($ctasReference as $ctaReference) {
            /** @var PsaPageZoneCta $ctaReference */

            //@Todo use new CTA structure
            $isActive = true; //$ctaReference->isActive(); @todo use new CTA structure
            if ($ctaReference->getCtaRefType() === $ctaRefType && $isActive) {
                $cta = $ctaReference->getCta();
                $ctaData = [];

                $ctaData['title'] = $cta->getTitle();
                $ctaData['url'] = $cta->getFormattedAction();
                $ctaData['target'] = $ctaReference->getTarget();
                $ctaData['style'] = $ctaReference->getStyle();

                //Get picture for mobile only
                if ($isMobile && $cta->getMediaMobile() !== null) {
                    $ctaData['mediaPath'] = $this->getMediaServer() . $cta->getMediaMobile()->getMediaPath();
                    $ctaData['mediaAlt'] = $cta->getMediaMobile()->getMediaAlt();
                }
                // For mobile, make sure all ctas has a picto
                $missingOnePicto = $missingOnePicto || ($cta->getMediaMobile() === null);

                $result[] = $ctaData;
            }
        }

        return array(
            'ctasMissingOnePicto' => $missingOnePicto,
            'ctas'                 => $result
        );
    }
}
