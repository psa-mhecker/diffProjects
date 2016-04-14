<?php

namespace PsaNdp\MappingBundle\Sources;

use PsaNdp\MappingBundle\Utils\SiteConfiguration;
use PsaNdp\WebserviceConsumerBundle\Webservices\FinancementSimulator;
use Symfony\Component\HttpFoundation\Request;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;

/**
 * Data source for Pc41MentionsJuridiques block
 */
class Pc41MentionsJuridiquesDataSource extends AbstractDataSource
{
    /**
     * @var FinancementSimulator $financeSimulator
     */
    protected $financeSimulator;

    /**
     * @var SiteConfiguration $siteConfiguration
     */
    protected $siteConfiguration;

    public function __construct(FinancementSimulator $financeSimulator,
                                SiteConfiguration $siteConfiguration)
    {
        $this->financeSimulator = $financeSimulator;
        $this->siteConfiguration = $siteConfiguration;
    }

    public function fetch(ReadBlockInterface $block, Request $request, $isMobile)
    {
        $data['mentions'] = $this->initMentions($request);

        return $data;
    }

    private function initMentions(Request $request)
    {
        $siteId = $request->get('siteId');
        $langCode = $request->getLocale();

        $serviceFinance = $this->financeSimulator;

        $this->siteConfiguration->setSiteId($siteId);
        $this->siteConfiguration->loadConfiguration();
        $site = $this->siteConfiguration->getSite();

        $language = strtolower($langCode . '-' . $site->getCountryCode()); // e.g. 'fr-fr'
        $currency = $this->siteConfiguration->getNationalParameter('CURRENCY_CODE'); // e.g. 'EUR'

        $serviceFinance
            ->addVehicleGeneralParameter('VehicleIdentification', '1PIAA5FKR5B0A0E0') // TODO attendre la PF25
            ->addContext('Language', $language)
            ->addContext('Currency', $currency)
            ->saveCalucationDisplay();

        $info = $serviceFinance->getGeneralLegalText();

        return [$info];
    }
}
