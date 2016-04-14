<?php

namespace PsaNdp\ApiBundle\Transformers;

use Doctrine\Common\Collections\ArrayCollection;
use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\ApiBundle\Facade\AddressFacade;
use PsaNdp\ApiBundle\Facade\ContactFacade;
use PsaNdp\ApiBundle\Facade\Pf8\DaysFacade;
use PsaNdp\ApiBundle\Facade\Pf8\SearchResultDealerVehicleFacade;
use PsaNdp\ApiBundle\Facade\Pf8\SearchResultItemFacade;
use PsaNdp\ApiBundle\Facade\Pf8\PricesFacade;
use PsaNdp\ApiBundle\Facade\Pf8\DisponibilityFacade;
use PsaNdp\MappingBundle\Translation\TranslatorAwareTrait;

/**
 * Class Pf8SearchResultRegionalTransformer
 * @package PsaNdp\ApiBundle\Transformers
 */
class Pf8SearchResultRegionalTransformer extends AbstractTransformer
{
    use TranslatorAwareTrait;

    /**
     * @param ArrayCollection $vehiclesCollection
     *
     * @return array
     */
    public function transform($vehiclesCollection)
    {
        $vehicles = $vehicle = array();
        foreach ($vehiclesCollection as $oVehicle) {
            if (is_object($oVehicle)) {
                $vehicle = get_object_vars($oVehicle);

                $daysFacade = new DaysFacade();
                $daysFacade->jour = $this->trans('NDP_DAY');
                $daysFacade->jours = $this->trans('NDP_DAYS');

                $disponibilityFacade = new DisponibilityFacade();
                $disponibilityFacade->text = $this->trans('NDP_AVAILABLE_IN');
                $disponibilityFacade->nbDays = $vehicle['AvailabilityDelay'];
                $disponibilityFacade->days = $daysFacade;

                $pricesFacade = new PricesFacade();
                $pricesFacade->price = $vehicle['InternetPrice'];
                $pricesFacade->advice = $vehicle['CatalogPrice'];
                $pricesFacade->saving = $vehicle['Reduction'];
                $pricesFacade->disponibility = $disponibilityFacade;

                $dealerInfo = $vehicle['dealer'];

                $dealerAddress = new AddressFacade();
                $dealerAddress->street = $dealerInfo['Address1'];
                $dealerAddress->city = $dealerInfo['City'];
                $dealerAddress->postalCode = $dealerInfo['ZipCode'];
                $dealerAddress->country = $dealerInfo['GeoLevel2'];
                $dealerAddress->lat = ''; //Pas d'info
                $dealerAddress->long = ''; //Pas d'info

                $dealerContact = new ContactFacade();
                $dealerContact->tel = $dealerInfo['Phone'];
                $dealerContact->fax = ''; //Pas d'info
                $dealerContact->mail = ''; //Pas d'info
                $dealerContact->website = ''; //Pas d'info
                $dealerContact->vcf = ''; //Pas d'info

                $dealer = new SearchResultDealerVehicleFacade();
                $dealer->id = $dealerInfo['IDSiteGEO'];
                $dealer->distanceKm = $dealerInfo['Distance'];
                $dealer->name = $dealerInfo['Name'];
                $dealer->type = $dealerInfo['Description'];
                $dealer->vehicleNew = false; //Pas d'info
                $dealer->vehicleOccasion = false; //Pas d'info
                $dealer->vehicleLocation = false; //Pas d'info
                $dealer->address = $dealerAddress;
                $dealer->contact = $dealerContact;

                $resultItemFacade = new SearchResultItemFacade();
                $resultItemFacade->imageSrc = $vehicle['VisuExt'];
                $resultItemFacade->imageAlt = $vehicle['ModelLabel'];
                $resultItemFacade->news = true;
                $resultItemFacade->title = $vehicle['CommercialLabel'];
                $resultItemFacade->engine = $vehicle['EngineLabel'];
                $resultItemFacade->painting = $vehicle['ExtFeatureLabel'];
                $resultItemFacade->trimming = $vehicle['IntFeatureLabel'];
                $resultItemFacade->consumption = $vehicle['ConsoMixte'];
                $resultItemFacade->emission = $vehicle['CO2Rate'];
                $resultItemFacade->urlCtaOffre = $vehicle['StoreDetailUrl'];
                $resultItemFacade->prices = $pricesFacade;
                $resultItemFacade->dealer = $dealer;

                $vehicles[] = $resultItemFacade;
            }
        }

        return array('listCars' => $vehicles);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pf8_search_result_vehicles';
    }

    /**
     * @param string $id
     * @param array $parameters
     * @param null $domain
     * @param null $locale
     * @return string
     */
    public function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        if ($domain == null) {
            $domain = $this->domain;
        }
        if ($locale == null) {
            $locale = $this->locale;
        }

        return $this->translator->trans($id, $parameters, $domain, $locale);
    }
}
