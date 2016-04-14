<?php

namespace PsaNdp\WebserviceConsumerBundle\Webservices;


use Itkg\Consumer\Service\Service;
use Itkg\Migration\XML\XPathQueryHelper;
use \InvalidArgumentException;
use PsaNdp\MappingBundle\Repository\PsaSiteWebserviceRepository;
use PsaNdp\MappingBundle\Repository\PsaWebserviceRepository;

class FinancementSimulator extends AbstractPsaSoapWebservice
{
    const FINANCING_MAKE = 'AP';
    const SITE_LOGIN = 'NDP';
    const SITE_PASSWORD = 'recette';
    const BRAND_CODE = 'PEUGEOT';
    const BRAND_LABEL = 'PEUGEOT';
    const VEHICLE_TYPE = 'VN';
    const DEFAULT_DISPLAY_NAME = 'TEASER';
    const FINANCING_DEFAULT_FLAG = 0;
    const DEFAULT_CLIENT_TYPE = 'PART';

    private $validXmlWrapper = '<?xml version="1.0" encoding="UTF-8"?><content>%s</content>';
    private $xpathQueryHelper;
    private $enabled=true;

    // TODO confirm with MOA all the keys to be display for 'options d'achat'
    private $financialDetailsOptionsKeys = array(
        'VehiclePriceTTC',
        'MTACOMPTE', //Premier Loyer
        'PMT2',
        'PMTMAINT_8_13',
        'PMTSECUREM',
        'PMTASSO',
        'PMT1',
        'DURFIN',
        'NOMMENS',
        'MESSAGEKM',
        'MTVR',
        'TOTALFIN'
    );

    const FINANCEMENT_DETAILS_KEY_INSURANCE_TITLE = 'ASSUFAC';

    // TODO confirm with MOA all the keys to be display for insurance
    private $financialDetailsInsuranceKeys = array(
        'DI',
        'TotalDI',
    );

    protected $lastResponse;

    protected $vehicle = array();

    protected $site = array();

    protected $displayList = array();

    protected $financing = array();

    protected $vehicule;
    
    protected $client;

    protected $allowedArguments = array(
        'context' => array(
            'Client',
            'Brand',
            'Country',
            'FlowDate',
            'Date',
            'FinancingMake',
            'Language',
            'Currency'

        ),
        'criteria' => array(
            'Version',
            'Feature',
        ),
        'site' => array(
            'Login',
            'Password',
        ),
        'financing' => array(
            'FinancingSpecialFlag'
        ),
        'displayList' => array(
            'DisplayName'
        ),
        'client' => array()

    );

    /**
     * @param Service                     $service
     * @param XPathQueryHelper            $xPathQueryHelper
     * @param PsaSiteWebserviceRepository $siteWebserviceRepository
     * @param PsaWebserviceRepository     $psaWebserviceRepository
     */
    public function __construct(Service $service, XPathQueryHelper $xPathQueryHelper, PsaSiteWebserviceRepository $siteWebserviceRepository, PsaWebserviceRepository $psaWebserviceRepository)
    {
        $this->xpathQueryHelper = $xPathQueryHelper;
        parent::__construct($service, $siteWebserviceRepository, $psaWebserviceRepository);
    }

    public function setDefaultContext()
    {
        parent::setDefaultContext()
            ->addContext('FinancingMake', self::FINANCING_MAKE)
            ->addContext('FlowDate', date('Y-m-d'));

        $this->addSiteParameter('Login', self::SITE_LOGIN)
            ->addSiteParameter('Password', self::SITE_PASSWORD)
            ->addDisplayListParameter('DisplayName', self::DEFAULT_DISPLAY_NAME)
            ->addFinancingParameter('FinancingSpecialFlag', self::FINANCING_DEFAULT_FLAG)
            ->addClientPersonalParameter('ClientType', self::DEFAULT_CLIENT_TYPE)
            ->addVehicleGeneralParameter('VehicleBrandCode', self::BRAND_CODE)
            ->addVehicleGeneralParameter('VehicleBrandLabel', self::BRAND_LABEL)
            ->addVehicleGeneralParameter('VehicleType', self::VEHICLE_TYPE)
            ->addVehiclePriceParameter('VehiclePriceTTC', 0)
            ->addVehiclePriceParameter('VehiclePriceHT', 0);
    }

    /**
     * Basic parameters for using the saveCalucationDisplay() WS call function
     *
     * @param string $vehicleIdentification 1PW2CAXKAQ04A0C1
     * @param string $languageCode fr-fr
     * @param string $currencyCode EUR
     * @param string $ttcPrice 43250.00
     * @param string $htPrice 43250.00
     *
     * @return mixed
     */
    public function setBasicParameters($vehicleIdentification, $languageCode, $currencyCode, $ttcPrice, $htPrice)
    {
        return $this
            ->addVehicleGeneralParameter('VehicleIdentification', $vehicleIdentification)
            ->addContext('Language', $languageCode)
            ->addContext('Currency', $currencyCode)
            ->addVehiclePriceParameter('VehiclePriceTTC', $ttcPrice)
            ->addVehiclePriceParameter('VehiclePriceHT', $htPrice);
    }

    /**
     * @param $name
     * @param $value
     *
     * @return $this
     * @throws InvalidArgumentException
     */

    public function addSiteParameter($name, $value)
    {
        return $this->addArgumentValue('site', $name, $value);
    }

    /**
     * @param $name
     * @param $value
     *
     * @return FinancementSimulator
     */
    public function addVehicleGeneralParameter($name, $value)
    {
        $this->vehicule['VehicleGeneral'][$name] = $value;

        return $this;
    }

    /**
     * @param $name
     * @param $value
     *
     * @return $this
     */
    public function addVehiclePriceParameter($name, $value)
    {
        $this->vehicule['VehiclePrices'][$name] = $value;

        return $this;
    }

    /**
     * @param $name
     * @param $value
     *
     * @return $this
     * @throws InvalidArgumentException
     */

    public function addFinancingParameter($name, $value)
    {
        return $this->addArgumentValue('financing', $name, $value);
    }

    /**
     * @param $name
     * @param $value
     *
     * @throws InvalidArgumentException
     * @return $this
     */
    public function addDisplayListParameter($name, $value)
    {
        return $this->addArgumentValue('displayList', $name, $value);
    }

    /**
     * @param $name
     * @param $value
     *
     * @return $this
     */
    public function addClientPersonalParameter($name, $value)
    {
        $this->client['ClientPersonalData'][$name] = $value;

        return $this;
    }

    /**
     *
     * @return mixed
     *
     */
    public function saveCalculationDisplay()
    {
        $parameters = array(
            'RequestCalculationDisplay' => array(
                'Context' => $this->context,
                'Site' => $this->site,
                'Vehicle' => $this->vehicule,
                'DisplayNameList' => $this->displayList,
                'Financing' => $this->financing,
                'Client' => $this->client,
            )
        );

        $this->lastResponse = $this->call('SaveCalculationDisplay', $parameters);

        return $this->lastResponse;
    }

    /**
     * example: 'Un crédit vous engage et doit être remboursé. Vérifiez vos capacités de remboursement avant de vous
     * engager.'
     *
     * @return string|null
     * @throws \Exception
     */
    public function getTeaserLegalText()
    {
        return $this->extractXmlElementValue('teaserInformation', '/content/teaserLegalText');
    }

    /**
     * example: 'Un crédit vous engage et doit être remboursé. Vérifiez vos capacités de remboursement avant de vous
     * engager.'
     *
     * @return string|null
     * @throws \Exception
     * @Deprecated to replace with getTeaserLegalText()
     */
    public function getGeneralLegalText()
    {
        return $this->extractXmlElementValue('teaserInformation', '/content/teaserLegalText');
    }

    /**
     * example: 'après un premier loyer de'
     * @return null|string
     * @Deprecated use array['title'] from getFirstAccount()
     */
    public function getStartingPriceLegalText()
    {
        return $this->extractXmlElementValue('teaserInformation', '/content/teaserTextSecondary');
    }

    /**
     * example: array(
     *  'title'=>'après un premier loyer de',
     *  'price'=>'10.757,00',
     *  'unit'=>'€'
     * )
     *
     * @return null|string
     */
    public function getFirstAccount()
    {
        return array(
            'title' => $this->extractXmlElementValue('teaserInformation', '/content/teaserTextSecondary'),
            'price' => $this->extractXmlElementValue('teaserInformation', '/content/firstAccountPriceDisplayValue'),
            'unit' => $this->extractXmlElementValue('teaserInformation', '/content/firstAccountUnit'),
        );
    }


    /**
     * example: array(
     *    'title' => 'Détails du financement',
     *    'validityText' => 'Offre valable 8 jours à partir du 28/08/2015',
     *    'header' => 'Location avec Option d'Achat : Peugeot Perspectives (1)',
     *    'legalText' => '(1) Offre non cumulable, réservée aux personnes physiques pour la location du véhicule
     *                    identifié plus haut à usage privé, et sous réserve d’acceptation du dossier
     *                    par PEUGEOT FINANCE, bailleur CREDIPAR. Vous bénéficiez du délai légal de
     *                    rétractation. &lt;br /&gt;(2)......'
     *    );
     *
     * @return array
     * @throws \Exception
     */
    public function getFinancialDetailsTexts()
    {
        return array(
            'title' => $this->extractXmlElementValue('financialDetails', '/content/title', true),
            'validityText' => $this->extractXmlElementValue('financialDetails', '/content/validityText', true),
            'header' => $this->extractXmlElementValue('financialDetails', '/content/header', true),
            'legalText' => $this->extractXmlElementValue('financialDetails', '/content/LegalText', true)
        );
    }

    /**
     *
     */
    public function getFinancementDetailsUnit($id)
    {
        // Init with default empty value
        $financementDetailUnit = array (
            'id' => null,
            'label' => null,
            'value' => null,
            'displayValue' => null,
            'unit' => null
        );
        $rootXPath = $this->getCleanContentDomPath('financialDetails', true);

        if ($rootXPath !== null) {
            $elements = $rootXPath->query('/content/variable');
            $element = $this->findFinancialUnitById($id, $elements);

            $financementDetailUnit = array(
                'id' => $id,
                'label' => $this->xpathQueryHelper->queryFirstDOMElementNodeValue('./label', $rootXPath, $element),
                'value' => $this->xpathQueryHelper->queryFirstDOMElementNodeValue('./value', $rootXPath, $element),
                'displayValue' => $this->xpathQueryHelper->queryFirstDOMElementNodeValue(
                    './DisplayValue',
                    $rootXPath,
                    $element
                ),
                'unit' => $this->xpathQueryHelper->queryFirstDOMElementNodeValue('./unit', $rootXPath, $element),
            );
        }

        return $financementDetailUnit;
    }

    /**
     * example: array(
     *  'title'=>'à partir de',
     *  'price'=>'573,20',
     *  'unit'=>'€/mois'
     * )
     *
     * @return array
     */
    public function getStartingPrice()
    {
        return array(
            'title' => $this->extractXmlElementValue('teaserInformation', '/content/teaserTextPrimary'),
            'price' => $this->extractXmlElementValue('teaserInformation', '/content/monthPricedisplayValue'),
            'unit' => $this->extractXmlElementValue('teaserInformation', '/content/monthPriceUnit'),
        );
    }

    /**
     * example: 'à partir de'
     * @return null|string
     */
    public function getStartingPricePrefix()
    {
       return  $this->extractXmlElementValue('teaserInformation', '/content/teaserTextPrimary');
    }

    /**
     * @throws \Exception
     */
    private function checkLastResponse()
    {
        if ($this->lastResponse == null) {
            throw new \Exception('empty response');
        } elseif ($this->lastResponse->ResponseDisplay->FlagError === true) {
           throw new \Exception($this->lastResponse->ResponseDisplay->Error->ErrorMessage);
        }
    }


    /**
     * @return mixed
     * @throws \Exception
     */
    private function getDisplayElement()
    {
        $displayElement = null;
        try{
            $this->checkLastResponse();
            $displayElement = reset($this->lastResponse->ResponseDisplay->DisplayList->Display);
        } catch (\Exception $e){
            //do something useful
        }


        return $displayElement;
    }

    /**
     * @param $contentToMatch
     *
     * @return mixed
     */
    private function getMatchingContent($contentToMatch)
    {
        $content = $this->getDisplayElement();

        $rawMatchingContent = array_filter(
            $content->DisplayContent,
            function ($displayContentItem) use ($contentToMatch) {
                return ($displayContentItem->ContentName == $contentToMatch);
            }
        );
        $matchingContent = array_pop($rawMatchingContent);

        return sprintf($this->validXmlWrapper, $matchingContent->Content);
    }

    /**
     * Get lastResponse
     *
     * @return mixed
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * @param string $contentHolder
     * @param string $xpath
     * @param bool $cleanXml
     *
     * @return null|string
     */
    private function extractXmlElementValue($contentHolder, $xpath, $cleanXml = false)
    {
        $value = null;
        $domXpath = $this->getCleanContentDomPath($contentHolder, $cleanXml);
        $elements = $domXpath->query($xpath);

        if ($elements->length) {
            $value = $elements->item(0)->nodeValue;
        }

        return $value;
    }

    /**
     * @param string $xmlContentQuery
     * @param bool $cleanXml
     *
     * @return \DOMXpath|null
     */
    private function getCleanContentDomPath($xmlContentQuery, $cleanXml = false)
    {
        $domXpath = null;
        $content = $this->getMatchingContent($xmlContentQuery);

        if ($content !== null) {
            if ($cleanXml) {
                //dirty hack to correct messy xml
                $content = str_replace(']]]]<![CDATA>', ']]>', $content);
            }

            $doc = new \DOMDocument();
            $doc->loadXML($content);
            $domXpath = new \DOMXpath($doc);

        }

        return $domXpath;
    }

    /**
     * @param $id
     * @param \DOMNodeList $elements
     *
     * @return \DOMNode|null
     */
    private function findFinancialUnitById($id, \DOMNodeList $elements)
    {
        $i = 0;
        $found = false;
        $detailUnit = null;

        while ($i < $elements->length && $found == false) {
            $element = $elements->item($i);
            $j = 0;
            $childNodes = $element->childNodes;

            if ($childNodes->length) {
                while ($j < $childNodes->length && $found == false) {

                    if ($childNodes->item($j)->nodeName == 'id' && $childNodes->item($j)->nodeValue == $id) {
                        $found = true;
                        $detailUnit = $element;
                    }
                    $j++;
                }
            }
            $i++;
        }

        if ( ! $found) {
            throw new \InvalidArgumentException(sprintf('the financement unit %s does not exist', $id));
        }

        return $detailUnit;
    }

    /**
     * Get financialDetailsKeys
     *
     * @return array
     */
    public function getFinancialDetailsKeys()
    {
        return array_merge(
            $this->getFinancialDetailsOptionsKeys(),
            [self::FINANCEMENT_DETAILS_KEY_INSURANCE_TITLE],
            $this->getFinancialDetailsInsuranceKeys()
        );
    }

    /**
     * Get financialDetailsKeys
     *
     * @return array
     */
    public function getFinancialDetailsOptionsKeys()
    {
        return $this->financialDetailsOptionsKeys;
    }

    /**
     * Get financialDetailsKeys
     *
     * @return array
     */
    public function getFinancialDetailsInsuranceKeys()
    {
        return $this->financialDetailsInsuranceKeys;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        $this->enabled = true;

        try {
            $this->checkLastResponse();
        } catch (\Exception $e){
            $this->enabled = false;
        }

        return $this->enabled;

    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'WS_SFG';
    }
}
