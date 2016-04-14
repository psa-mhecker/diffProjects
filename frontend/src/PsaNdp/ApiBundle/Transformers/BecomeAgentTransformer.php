<?php

namespace PsaNdp\ApiBundle\Transformers;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\MappingBundle\Entity\PsaBecomeAgent;
use PsaNdp\ApiBundle\Facade\AddressFacade;
use PsaNdp\ApiBundle\Facade\AgentFacade;
use PsaNdp\ApiBundle\Facade\ContactFacade;
use PsaNdp\ApiBundle\Facade\ContentFacade;

/**
 * Class BecomeAgentTransformer
 */
class BecomeAgentTransformer extends AbstractTransformer
{
    const BUSINESS_FOR_SALE_LIAISON_ID = 421119;
    const AVAILABLE_LOCATION_LIAISON_ID = 421122;

    /**
     * @param PsaBecomeAgent $mixed
     *
     * @return FacadeInterface
     */
    public function transform($mixed)
    {
        $agent = new AgentFacade();
        $adress = new AddressFacade();
        $contact = new ContactFacade();
        $content = new ContentFacade();

        //address info
        $adress->lat = $mixed->getLatitude();
        $adress->lng = $mixed->getLongitude();
        $adress->dist = '';
        $adress->city = $mixed->getZipCode().' '.$mixed->getCity();
        $adress->country = $mixed->getCountry();
        $adress->street = $mixed->getAddress1().' '.$mixed->getAddress2();

        // contact info
        $contact->tel = $mixed->getPhoneNumber1();
        $contact->fax = $mixed->getFax();
        $contact->mail = $mixed->getEmail();
        $contact->website = '';
        $contact->vcf = '';

        // content info
        $content->text = '';
        $content->title = '';

        //filters info
        $agent->dealerAgent = false;
        $agent->dealerSale = false;
        switch ($mixed->getLinkId()) {
            case self::BUSINESS_FOR_SALE_LIAISON_ID:
                $agent->dealerSale = true;
                break;
            case self::AVAILABLE_LOCATION_LIAISON_ID:
                $agent->dealerAgent = true;
                break;
        }
        // agent info
        $agent->name = $mixed->getName();
        $agent->type = 'agent';
        $agent->adress = $adress;
        $agent->contact = $contact;
        $agent->contenu = $content;
        $agent->id = $mixed->getId();

        return $agent;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'become_agent';
    }
}
