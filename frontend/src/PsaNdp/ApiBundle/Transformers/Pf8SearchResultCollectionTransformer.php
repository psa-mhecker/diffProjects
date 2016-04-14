<?php

namespace PsaNdp\ApiBundle\Transformers;

use Doctrine\Common\Collections\ArrayCollection;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use PsaNdp\ApiBundle\Facade\AddressFacade;
use PsaNdp\ApiBundle\Facade\ContactFacade;
use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\ApiBundle\Facade\Pf8\SearchResultCollectionFacade;
use PsaNdp\ApiBundle\Facade\Pf8\SearchResultDealerFacade;

/**
 * Class Pf8SearchResultCollectionTransformer
 * @package PsaNdp\ApiBundle\Transformers
 */
class Pf8SearchResultCollectionTransformer extends AbstractTransformer
{
    /** @var string $domain */
    protected $domain;

    /** @var string $locale */
    protected $locale;

    /**
     * @param string $domain
     * @return $this
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @param string $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @param ArrayCollection $dealersCollection
     *
     * @return FacadeInterface
     */
    public function transform($dealersCollection)
    {
        $dealerCollection = new SearchResultCollectionFacade();

        foreach ($dealersCollection as $dealerId => $dealerInfo) {
            if (is_array($dealerInfo)) {
                $dealerAddress = new AddressFacade();
                $dealerAddress->street = $dealerInfo['Address1'];
                $dealerAddress->city = $dealerInfo['City'];
                $dealerAddress->postalCode = $dealerInfo['ZipCode'];
                $dealerAddress->country = $dealerInfo['GeoLevel2'];
                $dealerAddress->lat = $dealerInfo['Latitude'];
                $dealerAddress->long = $dealerInfo['Longitude'];

                $dealerContact = new ContactFacade();
                $dealerContact->tel = $dealerInfo['Phone'];
                $dealerContact->fax = ''; //Pas d'info
                $dealerContact->mail = ''; //Pas d'info
                $dealerContact->website = ''; //Pas d'info
                $dealerContact->vcf = ''; //Pas d'info

                $dealer = new SearchResultDealerFacade();
                $dealer->id = $dealerId;
                $dealer->distanceKm = $dealerInfo['Distance'];
                $dealer->name = $dealerInfo['Name'];
                $dealer->type = $dealerInfo['Description'];
                $dealer->vehicleNew = false; //Pas d'info
                $dealer->vehicleOccasion = false; //Pas d'info
                $dealer->vehicleLocation = false; //Pas d'info
                $dealer->address = $dealerAddress;
                $dealer->contact = $dealerContact;
                $dealer->reference = $dealerId;

                $vehicles = $this->getTransformer('pf8_search_result_item')
                    ->setDomain($this->domain)
                    ->setLocale($this->locale)
                    ->transform($dealerInfo['vehicles']);

                foreach ($vehicles as $vehicle) {
                    $dealer->addVehicleItem($vehicle);
                }

                $dealerCollection->addDealerItem($dealer);
            }
        }

        return $dealerCollection;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pf8_search_result_collection';
    }
}
