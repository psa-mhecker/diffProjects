<?php

namespace PsaNdp\MappingBundle\Manager;

use PsaNdp\MappingBundle\Entity\Vehicle\PsaModelSilhouetteSite;
use PsaNdp\MappingBundle\Object\Formatter\PriceFormatter;
use PsaNdp\MappingBundle\Translation\TranslatorAwareTrait;
use PsaNdp\WebserviceConsumerBundle\Webservices\FinancementSimulator;
use PsaNdp\MappingBundle\Utils\SiteConfiguration;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class PriceManager
 * @package PsaNdp\MappingBundle\Manager
 */
class PriceManager
{
    use TranslatorAwareTrait;

    const  HT = 'HT';
    const  TTC = 'TTC';
    const  MENSUALISE = 'MENSUALISE';
    const  COMPTANT = 'COMPTANT';

    const SFG_RESPONSE = 'response';
    const SFG_FIRST_ACCOUNT = 'firstAccount';
    const SFG_STARTING_PRICE = 'startingPrice';
    const SFG_GENERAL_LEGAL_TEXT = 'generalLegalText';
    const SFG_FINANCEMENT_DETAILS = 'financementDetails';

    const FINANCEMENT_DETAILS_TEXT_HEADER = 'TEXT_HEADER';
    const FINANCEMENT_DETAILS_TEXT_VALIDITY_TEXT = 'TEXT_VALIDITY_TEXT';
    const FINANCEMENT_DETAILS_TEXT_TITLE = 'TEXT_TITLE';
    const FINANCEMENT_DETAILS_TEXT_LEGAL_TEXT = 'TEXT_LEGAL_TEXT';
    const FINANCEMENT_DETAILS_TEXT_INSURANCE_TITLE = 'TEXT_INSURANCE_TITLE';
    const FINANCEMENT_DETAILS_VARIABLES_OPTIONS = 'VARIABLES_OPTIONS';
    const FINANCEMENT_DETAILS_VARIABLES_INSURANCE = 'VARIABLES_INSURANCE';

    /**
     * @var
     */
    protected $sfgWs;

    /**
     * @var SiteConfiguration
     */
    protected $siteConfiguration;

    /**
     * @var array
     */
    protected $siteSettings;

    /**
     * @var stdClass
     */
    protected $version;

    /**
     * @var array
     */
    protected $cheapest;

    /**
     * @var PsaModelSilhouetteSite
     */
    protected $modelSilhouetteInformation;

    /**
     * @var array
     */
    protected $sfg = [];

    /**
     * @var PriceFormatter
     */
    protected $priceFormatter;

    /**
     * PriceManager constructor.
     * @param SiteConfiguration $siteConfiguration
     * @param FinancementSimulator $financementSimulator
     * @param RequestStack|null $requestStack
     * @param PriceFormatter $priceFormatter
     */
    public function __construct(
        SiteConfiguration $siteConfiguration,
        FinancementSimulator $financementSimulator,
        RequestStack $requestStack = null,
        PriceFormatter $priceFormatter
    ) {
        $this->siteConfiguration = $siteConfiguration;
        $this->sfgWs = $financementSimulator;
        $this->priceFormatter = $priceFormatter;

        if(null !== $requestStack && (($request = $requestStack->getCurrentRequest()) !== null)) {
            $request = $requestStack->getCurrentRequest();
            $this->domain  = $request->attributes->get('siteId');
            $this->locale = $request->attributes->get('language');
            $this->initSiteSettings($request->attributes->get('siteId'));
        }
    }

    /**
     * @return $this
     */
    public function initSiteSettings($siteId)
    {
        $this->siteConfiguration->setSiteId($siteId);
        $this->siteConfiguration->loadConfiguration();
        $siteSettings = array_merge(
            $this->siteConfiguration->getParameters(),
            $this->siteConfiguration->getNationalParameters(),
            $this->siteConfiguration->getNationalParameter('CUSTOM')
        );

        unset($siteSettings['CUSTOM']);

        $this->siteSettings = $siteSettings;

        return $siteSettings;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     *
     * @return PriceManager
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return array
     */
    public function getCheapest()
    {
        return $this->cheapest;
    }

    /**
     * @param array $cheapest
     *
     * @return $this
     */
    public function setCheapest($cheapest)
    {
        $this->cheapest = $cheapest;

        return $this;
    }


    /**
     * @return array
     */
    public function getSfg()
    {
        return $this->sfg;
    }

    /**
     * @param int $siteId
     *
     * @return bool
     */
    public function getSfgStatus($siteId)
    {
        return $this->sfgWs->getWebserviceStatus($siteId, $this->sfgWs->getName());
    }

    /**
     * @param array $sfg
     *
     * @return PriceManager
     */
    public function setSfg($sfg)
    {
        $this->sfg = $sfg;

        return $this;
    }

    /**
     * @return PsaModelSilhouetteSite
     */
    public function getModelSilhouetteInformation()
    {
        return $this->modelSilhouetteInformation;
    }

    /**
     * @param PsaModelSilhouetteSite $modelSilhouetteInformation
     *
     * @return $this
     */
    public function setModelSilhouetteInformation($modelSilhouetteInformation)
    {
        $this->modelSilhouetteInformation = $modelSilhouetteInformation;

        return $this;
    }

    /**
     * @param mixed $version
     *
     * @return array
     */
    private function initSfG($version)
    {
        $site = $this->siteConfiguration->getSite();

        $this->sfgWs->setBasicParameters(
            $version['LCDV16'],
            sprintf('%s-%s',  $this->locale, strtolower($site->getCountryCode())),
            $this->siteSettings['CURRENCY_CODE'],
            $version['Price']['Value'],
            $version['Price']['Value']
        );

        $result[self::SFG_RESPONSE] = $this->sfgWs->saveCalculationDisplay();
        // SFG can be disable if no answer or no data for specific model version
        if ($this->sfgWs->isEnabled()) {
            //$result['financementDetails'] = $this->sfgWs->getFinancementDetails();
            $result[self::SFG_FIRST_ACCOUNT] = $this->sfgWs->getFirstAccount();
            $result[self::SFG_STARTING_PRICE] = $this->sfgWs->getStartingPrice();
            $result[self::SFG_GENERAL_LEGAL_TEXT] = $this->sfgWs->getGeneralLegalText();

            $financialDetailsTexts = $this->sfgWs->getFinancialDetailsTexts();
            $variableInsuranceTitle = $this->sfgWs->getFinancementDetailsUnit(
                FinancementSimulator::FINANCEMENT_DETAILS_KEY_INSURANCE_TITLE
            );
            $result[self::SFG_FINANCEMENT_DETAILS] = array(
                self::FINANCEMENT_DETAILS_TEXT_HEADER => $financialDetailsTexts['header'],
                self::FINANCEMENT_DETAILS_TEXT_VALIDITY_TEXT => $financialDetailsTexts['validityText'],
                self::FINANCEMENT_DETAILS_TEXT_TITLE => $financialDetailsTexts['title'],
                self::FINANCEMENT_DETAILS_TEXT_LEGAL_TEXT => $financialDetailsTexts['legalText'],
                self::FINANCEMENT_DETAILS_TEXT_INSURANCE_TITLE => ($variableInsuranceTitle !== null) ? $variableInsuranceTitle['label'] : '',
                self::FINANCEMENT_DETAILS_VARIABLES_OPTIONS => $this->getFinancementDetailsKeys(
                    $this->sfgWs->getFinancialDetailsOptionsKeys()
                ),
                self::FINANCEMENT_DETAILS_VARIABLES_INSURANCE => $this->getFinancementDetailsKeys(
                    $this->sfgWs->getFinancialDetailsInsuranceKeys()
                )
            );
            $this->sfg = $result;
        } else {
            $this->sfg =  [];
        }
    }

    /**
     * @param $keys
     *
     * @return array
     */
    private function getFinancementDetailsKeys($keys)
    {
        $result = [];

        if ($this->sfgWs->isEnabled()) {
            foreach ($keys as $key) {
                $result[$key] = $this->sfgWs->getFinancementDetailsUnit($key);
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getFirstAccountValue()
    {
        $startingPriceLegalText = null;

        if ($this->isMonthlyPrice() && isset($this->sfg['firstAccount'])) {
            $firstAccount = $this->sfg['firstAccount'];
            $startingPriceLegalText = $firstAccount['title'] . ' ' . $firstAccount['price'] . ' ' . $firstAccount['unit'];
        }

        return $startingPriceLegalText;
    }

    /**
     * @return string
     */
    public function getLegalNoticeByMonth()
    {
        $generalLegalText = ($this->isMonthlyPrice() && isset($this->sfg['generalLegalText'])) ? $this->sfg['generalLegalText'] : null;

        return $generalLegalText;
    }

    /**
     * @return string
     */
    public function getLegalNoticeCashPrice()
    {
        return $this->priceFormatter->getLegalNoticeSymbol() . ' ' . $this->trans('NDP_LEGAL_MENTION');
    }

    /**
     * @return bool
     */
    public function canShowPrice()
    {
        $return = false;

        if ((bool) $this->siteSettings['VEHICULE_PRICE_DISPLAY']) {
            if ($this->modelSilhouetteInformation && $this->modelSilhouetteInformation->isDisplayPrice()) {
                $return = true;
            }
        }

        return $return;
    }


    /**
     * @return mixed
     */
    public function getPriceValue()
    {
        $return = null;
        // es ce qu'on affiche les prix
        if ($this->canShowPrice()) {
            // quel type de prix afficher
            $return = ($this->isMonthlyPrice()) ? $this->getPriceByMonth() : $this->getCashPrice();
        }

        return $return;
    }


    /**
     * For Monthly Price, display as send by WS without reformatting
     *
     * @return string
     */
    public function getPriceByMonth()
    {
        $return = null;
        if($this->sfgWs->isEnabled() && !empty($this->cheapest)) {
            $this->initSfG($this->cheapest);
            $price = (isset($this->sfg['startingPrice']['price'])) ? $this->sfg['startingPrice']['price'] : '';
            $from = (isset($this->sfg['startingPrice']['title'])) ? $this->sfg['startingPrice']['title'] : '';
            $currency = (isset($this->sfg['startingPrice']['unit'])) ? $this->sfg['startingPrice']['unit'] : '';

            $this->priceFormatter->setFrom($from);
            $this->priceFormatter->setCurrency($currency);
            $return  = $this->priceFormatter->getOrderedPricePart($price);
        }

        return  $return;
    }

    /**
     * @return string
     */
    public function getCashPrice()
    {
        $return = '';
        if ($this->version) {
            $version = $this->cheapest;

            $return = $this->priceFormatter->getOrderedPricePart($version['Price']['Value']);
        }

        return $return;
    }

    /**
     * @return bool
     */
    public function isMonthlyPrice()
    {
        return (
            $this->siteSettings['VEHICULE_PRICE_MONTHLY_DISPLAY']  // es ce que la fonction est desactivé en BO
            && $this->userWantMonthly()
        );
    }

    /**
     * @return bool
     */
    private function userWantMonthly()
    {
        $return = false;
        if ($this->siteSettings['VEHICULE_PRICE_TYPE_PAYMENT_DEFAULT'] == self::MENSUALISE) {
            $return = true;
        }
        // TODO use Symfony request stack if possible for cookie ?
        // TODO TO confirm : cookie utilisé mais rien spécifié de comment ceci sera géré. Point a caler avec ISOBAR
        if (isset($_COOKIE['VEHICULE_PRICE_TYPE_PAYMENT']) && $_COOKIE['VEHICULE_PRICE_TYPE_PAYMENT'] == self::COMPTANT) {
            $return = false;
        }

        return $return;
    }

    /**
     * Should be applied only for Cash Price
     * For Monthly price show price and mention as WS send
     *
     * @return bool
     */
    public function canShowNotice()
    {
        return (!$this->isMonthlyPrice() && $this->siteSettings['VEHICULE_PRICE_LEGAL_DISPLAY']);
    }

    /**
     * @param string $key should be a const self::FINANCEMENT_DETAILS_XXXX
     *
     * @return null|string|array
     */
    public function getFinancementDetailsTexts($key)
    {
        $result = null;

        if ($this->sfgWs->isEnabled() &&
            isset($this->sfg['financementDetails']) &&
            isset($this->sfg['financementDetails'][$key])) {
            $result = $this->sfg['financementDetails'][$key];
        }

        return $result;
    }
    
    /**
     * 
     * @return SiteConfiguration
     */
    public function getSiteConfiguration()
    {
        return $this->siteConfiguration;
    }
    
    /**
     * 
     * @param SiteConfiguration $siteConfiguration
     * 
     * @return PriceManager
     */
    public function setSiteConfiguration(SiteConfiguration $siteConfiguration)
    {
        $this->siteConfiguration = $siteConfiguration;
        
        return $this;
    }

    /**
     * @return null|string
     */
    public function getLegalNotice()
    {
        $legalMention = null;

        if ($this->canShowNotice()) {
            $legalMention = $this->getLegalNoticeCashPrice();
        } else {
            $legalMention = $this->getLegalNoticeByMonth();
        }

        return $legalMention;
    }
}
