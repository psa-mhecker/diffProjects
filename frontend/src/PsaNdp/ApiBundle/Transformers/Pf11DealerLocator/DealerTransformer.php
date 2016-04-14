<?php

namespace PsaNdp\ApiBundle\Transformers\Pf11DealerLocator;

use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\ApiBundle\Facade\AddressFacade;
use PsaNdp\ApiBundle\Facade\ContactFacade;
use PsaNdp\ApiBundle\Facade\Pf11\CtaFacade;
use PsaNdp\ApiBundle\Facade\Pf11\DealerFacade;
use PsaNdp\ApiBundle\Facade\Pf11\OffreCollectionFacade;
use PsaNdp\ApiBundle\Facade\Pf11\OffreFacade;
use PsaNdp\ApiBundle\Facade\Pf11\ServiceFacade;
use PsaNdp\MappingBundle\Translation\TranslatorAwareTrait;
use Symfony\Component\Routing\RouterInterface;

class DealerTransformer extends AbstractTransformer
{
    use TranslatorAwareTrait;

    const TYPE = 'magasin';
    const CODE_NEW = 'VN';
    const CODE_LOCATION = 'VL';
    const CODE_OCCASION = 'VO';
    const NDP_ITINARY = 'NDP_ITINARY';
    const NDP_CONTACT_US = 'NDP_CONTACT_US';
    const NDP_PROMOTIONS_OF_POINT_OF_SALE = 'NDP_PROMOTIONS_OF_POINT_OF_SALE';
    const URL_MAPS_ITINERAIRE = 'https://www.google.fr/maps/dir/{$start}/{$dest}/';
    const NB_OFFERS = 3;
    const STYLE_CTA_ITINERARY = 'cta-direction';
    const STYLE_CTA_CONTACT = 'cta-contact';

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var int
     */
    private $siteId;

    /**
     * @var string
     */
    private $languageCode;

    /**
     * @var string
     */
    private $start;

    /**
     * @var array
     */
    private $overriddenServices;

    /**
     * @var MediaFactory
     */
    private $mediaFactory;

    /**
     * DealerTransformer constructor.
     *
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param mixed $mixed
     *
     * @return FacadeInterface
     */
    public function transform($mixed)
    {
        $dealerFacade = new DealerFacade();

        $dealerFacade->adress = $this->getDealerAddress($mixed);
        $dealerFacade->contact = $this->getDealerContact($mixed);
        $dealerFacade->id = $mixed['SiteGeo'];
        $dealerFacade->ctaList = $this->getDealerCtaList($mixed);
        $dealerFacade->name = $mixed['Name'];
        $dealerFacade->offres = $this->getDealerOffres($mixed);
        $dealerFacade->schedules = $this->getDealerSchedules($mixed);
        $dealerFacade->services = $this->getDealerServices($mixed);
        $dealerFacade->type = self::TYPE;
        $dealerFacade->vehicle_location = $this->isLocation($mixed);
        $dealerFacade->vehicle_new = $this->isNew($mixed);
        $dealerFacade->vehicle_occasion = $this->isOccasion($mixed);
        $dealerFacade->dealer = $mixed['IsSuccursale'];
        $dealerFacade->agent = $mixed['IsAgentAP'];
        $dealerFacade->principalVn = $mixed['Principal']['IsPrincipalVN'];

        return $dealerFacade;
    }

    /**
     * @param $mixed
     *
     * @return AddressFacade
     */
    private function getDealerAddress($mixed)
    {
        $address = new AddressFacade();

        $address->city = $mixed['Address']['ZipCode'].' '.$mixed['Address']['City'];
        $address->country = $mixed['Address']['Country'];
        $address->dist = $mixed['DistanceFromPoint'];
        $address->lat = $mixed['Coordinates']['Latitude'];
        $address->lng = $mixed['Coordinates']['Longitude'];
        $address->street = $mixed['Address']['Line1'];
        if (!empty($mixed['Address']['Line2'])) {
            $address->street .= $mixed['Address']['Line2'];
        }
        if (!empty($mixed['Address']['Line3'])) {
            $address->street .= $mixed['Address']['Line3'];
        }

        return $address;
    }

    /**
     * @param $mixed
     *
     * @return ContactFacade
     */
    private function getDealerContact($mixed)
    {
        $contact = new ContactFacade();
        $contact->fax = $mixed['FaxNumber'];
        $contact->tel = $mixed['Phones']['PhoneNumber'];
        $contact->website = $mixed['WebSites']['Public'];
        $contact->mail = (!empty($mixed['Emails']['email'])) ? $mixed['Emails']['email'] : null;
        $contact->vcf = $this->getUrlVcf($mixed);

        return $contact;
    }

    /**
     * @param $mixed
     *
     * @return string
     */
    private function getUrlVcf($mixed)
    {
        return $this->router->generate(
            'psa_ndp_vcf_pcf11_sitegeo_card',
            [
                'siteId' => $this->siteId,
                'langueCode' => $this->languageCode,
                'siteGeo' => $mixed['SiteGeo'],

            ],
            true
        );
    }

    /**
     * @param $mixed
     *
     * @return array
     */
    private function getDealerCtaList($mixed)
    {
        $ctaList = [];
        $ctaList[] = $this->getCtaItineraire($mixed);
        $ctaList[] = $this->getCtaContact($mixed);

        return $ctaList;
    }

    /**
     * @param $mixed
     *
     * @return CtaFacade
     */
    private function getCtaItineraire($mixed)
    {
        //RG_FO_PF11_61
        $cta = new CtaFacade();
        $cta->title = $this->trans(self::NDP_ITINARY);//"Itinéraire"
        $cta->url = $this->buildUrlItineraire($mixed);
        $cta->version = self::STYLE_CTA_ITINERARY;
        $cta->target = '_blank';

        return $cta;
    }

    /**
     * @param $mixed
     *
     * @return string
     */
    private function buildUrlItineraire($mixed)
    {
        $replace = array(
            '{$start}' => $this->start,
            '{$dest}' => $mixed['Coordinates']['Latitude'].','.$mixed['Coordinates']['Longitude'],
        );

        return strtr(self::URL_MAPS_ITINERAIRE, $replace);
    }

    /**
     * @param $mixed
     *
     * @return CtaFacade
     */
    private function getCtaContact($mixed)
    {
        $cta = null;
        if (!empty($mixed['UrlPages']['UrlContact'])) {
            $cta = new CtaFacade();
            $cta->title = $this->trans(self::NDP_CONTACT_US);//"Contactez-nous"
            $cta->url = $mixed['UrlPages']['UrlContact'];
            $cta->version = self::STYLE_CTA_CONTACT;
            $cta->target = '_self';
        }

        return $cta;
    }

    /**
     * @param $mixed
     *
     * @return null|OffreCollectionFacade
     */
    private function getDealerOffres($mixed)
    {
        $offres = null;

        if (!empty($mixed['promotions']->FavoriteOffer)) {
            $offres = new OffreCollectionFacade();
            $sortedOffers = $this->sortOffers((array) $mixed['promotions']->FavoriteOffer);

            $offres->title = $this->trans(self::NDP_PROMOTIONS_OF_POINT_OF_SALE); //"promotions du point de vente"
            foreach ($sortedOffers as $offer) {
                $offre = new OffreFacade();
                $offre->title = $offer->Title;
                $offres->offresList[] = $offre;
            }
        }

        return $offres;
    }

    private function sortOffers(array $offers)
    {
        // tableau d'offres filtrée
        $filtered = [];
        // filtre des offres par type si parametre de l'utilisateur
        if (!empty($this->filters)) {
            foreach ($offers as $offer) {
                if (in_array($offer->Contract, $this->filters)) {
                    $filtered[] = $offer;
                }
            }
        }
        if (empty($filtered)) {
            $filtered = $offers;
        }
        // on limite au 3 dernieres offre
        $result = array_slice($filtered, 0, self::NB_OFFERS);

        /// tri des offres par date de la plus recente a la  plus ancienne
        usort(
            $result,
            function ($a, $b) {
                $aDate = new \DateTime($a->PublicationStartDate); // format de date "2015-03-09T18:30:59.26"
                $bDate = new \DateTime($b->PublicationStartDate); //
                if ($aDate == $bDate) {
                    return 0;
                }

                return ($aDate > $bDate) ? -1 : 1;

            }
        );

        return $result;
    }

    /**
     * @return array
     */
    private function getDealerSchedules($mixed)
    {
        $schedules = null;
        foreach ($mixed['OpeningHoursList'] as $schedule) {
            if ($schedule['Type'] == 'GENERAL') {
                $schedules = $schedule['Label'];
            }
        }

        return $schedules;
    }

    /**
     * @return array
     */
    private function getDealerServices($mixed)
    {
        $services = [];

        //correspond à des services, des activités ou des licences ou des indicateurs.
        foreach ($mixed['BusinessList'] as $business) {
            if (!array_key_exists($business['Code'], $this->overriddenServices)) {
                continue;
            }

            $service = new ServiceFacade();

            $overriddenService = $this->overriddenServices[$business['Code']];

            if ($overriddenService->getMedia() !== null) {
                $image = $this->mediaFactory->createFromMedia($overriddenService->getMedia());
                $service->icon = $image->getSrc();
            }

            $code = $business['Code'];
            $service->code = strtolower($code);
            $service->type = $business['Label'];
            $service->order = $overriddenService->getServiceOrder();
            $service->typeName = $business['Type'];
            $service->name = ($overriddenService->getServiceLabelCustom()) ? $overriddenService->getServiceLabelCustom(
            ) : $overriddenService->getServiceLabel();
            $service->fax = $mixed['FaxNumber'];
            $service->tel = (isset($mixed['Phones']['Phone'.$code])) ? $mixed['Phones']['Phone'.$code] : $mixed['Phones']['PhoneNumber'];
            $service->mail = (isset($mixed['Emails']['Email'.$code])) ? $mixed['Emails']['Email'.$code] : $mixed['Emails']['Email'];
            $services[] = $service;
        }

        //order services
        usort($services, function ($service1, $service2) {
            $order1 = $service1->order;
            $order2 = $service2->order;
            if ($order1 == $order2) {
                return 0;
            }

            return ($order1 < $order2) ? -1 : 1;
        });

        return $services;
    }

    /**
     * @return bool
     */
    private function isLocation($mixed)
    {
        return $this->hasBusiness(self::CODE_LOCATION, $mixed);
    }

    /**
     * @return bool
     */
    private function isNew($mixed)
    {
        return $this->hasBusiness(self::CODE_NEW, $mixed);
    }

    /**
     * @return bool
     */
    private function isOccasion($mixed)
    {
        return $this->hasBusiness(self::CODE_OCCASION, $mixed);
    }

    /**
     * @param $code
     *
     * @return bool
     */
    private function hasBusiness($code, $mixed)
    {
        $returnValue = false;
        foreach ($mixed['BusinessList'] as $business) {
            if ($code == $business['Code']) {
                $returnValue = true;
                break;
            }
        }

        return $returnValue;
    }

    /**
     * @param $services
     *
     * @return $this
     */
    public function setOverriddenServices($services)
    {
        $this->overriddenServices = $services;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dealer';
    }

    /**
     * @param mixed $siteId
     *
     * @return DealerTransformer
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;

        return $this;
    }

    /**
     * @param mixed $languageCode
     *
     * @return DealerTransformer
     */
    public function setLanguageCode($languageCode)
    {
        $this->languageCode = $languageCode;

        return $this;
    }

    /**
     * @param mixed $start
     *
     * @return DealerTransformer
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @return MediaFactory
     */
    public function getMediaFactory()
    {
        return $this->mediaFactory;
    }

    /**
     * @param MediaFactory $mediaFactory
     */
    public function setMediaFactory($mediaFactory)
    {
        $this->mediaFactory = $mediaFactory;

        return $this;
    }
}
