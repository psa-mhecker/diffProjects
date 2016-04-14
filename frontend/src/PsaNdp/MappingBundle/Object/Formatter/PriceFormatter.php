<?php
/**
 * Created by PhpStorm.
 * User: Hamdi Afrit
 * Date: 29/03/16
 * Time: 11:46
 */

namespace PsaNdp\MappingBundle\Object\Formatter;
use PsaNdp\MappingBundle\Translation\TranslatorAwareTrait;
use PsaNdp\MappingBundle\Utils\SiteConfiguration;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class PriceFormatter
 * @package PsaNdp\MappingBundle\Object\Formatter
 */
class PriceFormatter
{
    use TranslatorAwareTrait;

    const  PRICE_FROM_AFTER = 2;
    const  CURRENCY_BEFORE = 1;

    protected $taxType = array('HT' => 'NDP_PRICE_HT' , 'TTC' => 'NDP_TTC');

    /**
     * @var string
     */
    protected $formattedPrice;

    /**
     * @var string
     */
    protected $currencyBeforePrice = '<strong>%s%s</strong>';

    /**
     * @var string
     */
    protected $currencyAfterPrice = '<strong>%s %s</strong>';

    /**
     * @var string
     */
    protected $fromPriceTemplate = '%s %s %s';

    /**
     * @var string
     */
    protected $fromTemplate = '<small>%s</small>';

    /**
     * @var string
     */
    protected $currencyPosition;

    /**
     * @var string
     */
    protected $fromPricePosition;

    /**
     * @var string
     */
    protected $legalNoticeDisplay;

    /**
     * @var string
     */
    protected $legalNoticeSymbol;

    /**
     * @var string
     */
    protected $priceNbDecimal;

    /**
     * @var string
     */
    protected $priceDecimalDelimiter;

    /**
     * @var string
     */
    protected $priceThousandDelimiter;

    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $tax;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var array
     */
    protected $nationalParameters;

    /**
     * @param SiteConfiguration   $siteConfiguration
     * @param TranslatorInterface $translator
     * @param RequestStack        $requestStack
     */
    public function __construct(SiteConfiguration $siteConfiguration, TranslatorInterface $translator, RequestStack $requestStack = null)
    {
        $this->translator = $translator;
        $request = $requestStack->getCurrentRequest();
        $this->domain  = $request->get('siteId');
        $this->locale = $request->get('language');
        $siteConfiguration->setSiteId($request->get('siteId'));
        $siteConfiguration->loadConfiguration();
        $this->nationalParameters = $siteConfiguration->getNationalParameters();
    }

    /**
     * @param $price
     *
     * @return string
     */
    protected function getFormattedPrice($price)
    {
        $tempPrice = (float) str_replace(',', '.', $price);// if price with dot change it into comma

        $return = number_format($tempPrice, $this->getPriceNbDecimal(), $this->getPriceDecimalDelimiter(), $this->getPriceThousandDelimiter());

        return $return;
    }

    /**
     * @param $price
     *
     * @return string
     */
    public function getOrderedPricePart($price)
    {
        $from = sprintf($this->getFromTemplate(), $this->getFrom());

        switch ($this->getCurrencyPosition())
        {
            case self::CURRENCY_BEFORE:
                $formattedPrice = sprintf($this->getCurrencyBeforePrice(), $this->getCurrency(), $this->getFormattedPrice($price));
                break;

            default:
                $formattedPrice = sprintf($this->getCurrencyAfterPrice(), $this->getFormattedPrice($price), $this->getCurrency());
                break;
        }

        // Add asterix for legal mention
        if ($this->getLegalNoticeDisplay()) {
            $this->tax .= $this->getLegalNoticeSymbol();
        }

        // Position "A partir de" and price
        switch ($this->getFromPricePosition())
        {
            case self::PRICE_FROM_AFTER:
                $result = $this->getPriceTypeTemplate($formattedPrice, $this->getTax(), $from);
                break;

            default:
                $result = $this->getPriceTypeTemplate($from, $formattedPrice, $this->getTax());
                break;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getCurrencyBeforePrice()
    {
        return $this->currencyBeforePrice;
    }

    /**
     * @param $currencyBeforePrice
     *
     * @return $this
     */
    public function setCurrencyBeforePrice($currencyBeforePrice)
    {
        $this->currencyBeforePrice = $currencyBeforePrice;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrencyAfterPrice()
    {
        return $this->currencyAfterPrice;
    }

    /**
     * @param $currencyAfterPrice
     *
     * @return $this
     */
    public function setCurrencyAfterPrice($currencyAfterPrice)
    {
        $this->currencyAfterPrice = $currencyAfterPrice;

        return $this;
    }

    /**
     * @return string
     */
    public function getFromPriceTemplate()
    {
        return $this->fromPriceTemplate;
    }

    /**
     * @param $fromPriceTemplate
     *
     * @return $this
     */
    public function setFromPriceTemplate($fromPriceTemplate)
    {
        $this->fromPriceTemplate = $fromPriceTemplate;

        return $this;
    }

    /**
     * @return string
     */
    public function getFromTemplate()
    {
        return $this->fromTemplate;
    }

    /**
     * @param $fromTemplate
     *
     * @return $this
     */
    public function setFromTemplate($fromTemplate)
    {
        $this->fromTemplate = $fromTemplate;

        return $this;
    }

    /**
     * @param $first
     * @param $second
     * @param $third
     *
     * @return string
     */
    public function getPriceTypeTemplate($first, $second, $third)
    {
        return sprintf($this->getFromPriceTemplate(), $first, $second, $third);
    }

    /**
     * @param $currencyPosition
     *
     * @return $this
     */
    public function setCurrencyPosition($currencyPosition)
    {
        $this->currencyPosition = $currencyPosition;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrencyPosition()
    {
        if (empty($this->currencyPosition)) {
            $this->currencyPosition = $this->nationalParameters['CURRENCY_POSITION'];
        }

        return $this->currencyPosition;
    }

    /**
     * @return string
     */
    public function getFromPricePosition()
    {
        if (empty($this->fromPricePosition)) {
            $this->fromPricePosition = $this->nationalParameters['VEHICULE_PRICE_FROM_POSITION'];
        }

        return $this->fromPricePosition;
    }

    /**
     * @param $fromPricePosition
     *
     * @return $this
     */
    public function setFromPricePosition($fromPricePosition)
    {
        $this->fromPricePosition = $fromPricePosition;

        return $this;
    }

    /**
     * @return string
     */
    public function getLegalNoticeSymbol()
    {
        if (empty($this->legalNoticeSymbol)) {
            $this->legalNoticeSymbol = $this->nationalParameters['VEHICULE_PRICE_LEGAL_SYMBOL'];
        }

        return $this->legalNoticeSymbol;
    }

    /**
     * @return string
     */
    public function getLegalNoticeDisplay()
    {
        if (empty($this->legalNoticeDisplay)) {
            $this->legalNoticeDisplay = $this->nationalParameters['VEHICULE_PRICE_LEGAL_DISPLAY'];
        }

        return $this->legalNoticeDisplay;
    }

    /**
     * @param $legalNoticeDisplay
     *
     * @return $this
     */
    public function setLegalNoticeDisplay($legalNoticeDisplay)
    {
        $this->legalNoticeDisplay = $legalNoticeDisplay;

        return $this;
    }

    /**
     * @param $legalNoticeSymbol
     *
     * @return $this
     */
    public function setLegalNoticeSymbol($legalNoticeSymbol)
    {
        $this->legalNoticeSymbol = $legalNoticeSymbol;

        return $this;
    }

    /**
     * @return string
     */
    public function getPriceNbDecimal()
    {
        if (empty($this->priceNbDecimal)) {
            $this->priceNbDecimal = $this->nationalParameters['VEHICULE_PRICE_NB_DECIMAL'];
        }

        return $this->priceNbDecimal;
    }

    /**
     * @param $priceNbDecimal
     *
     * @return $this
     */
    public function setPriceNbDecimal($priceNbDecimal)
    {
        $this->priceNbDecimal = $priceNbDecimal;

        return $this;
    }

    /**
     * @return string
     */
    public function getPriceDecimalDelimiter()
    {
        if (empty($this->priceDecimalDelimiter)) {
            $this->priceDecimalDelimiter = $this->nationalParameters['NB_DELIMITER_DECIMAL'];
        }

        return $this->priceDecimalDelimiter;
    }

    /**
     * @param $priceDecimalDelimiter
     *
     * @return $this
     */
    public function setPriceDecimalDelimiter($priceDecimalDelimiter)
    {
        $this->priceDecimalDelimiter = $priceDecimalDelimiter;

        return $this;
    }

    /**
     * @return string
     */
    public function getPriceThousandDelimiter()
    {
        if (empty($this->priceThousandDelimiter)) {
            $this->priceThousandDelimiter = $this->nationalParameters['NB_DELIMITER_THOUSAND'];
        }

        return $this->priceThousandDelimiter;
    }

    /**
     * @param $priceThousandDelimiter
     *
     * @return $this
     */
    public function setPriceThousandDelimiter($priceThousandDelimiter)
    {
        $this->priceThousandDelimiter = $priceThousandDelimiter;

        return $this;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        if (empty($this->from)) {
            $this->from = $this->trans('NDP_FROM');
        }

        return $this->from;
    }

    /**
     * @param string $from
     *
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return string
     */
    public function getTax()
    {
        return $this->trans($this->taxType[$this->nationalParameters['OTHER_PRICE_TYPE']]);
    }

    /**
     * @param string $tax
     *
     * @return $this
     */
    public function setTax($tax)
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        if (empty($this->currency)) {
            $this->currency = $this->nationalParameters['CURRENCY_SYMBOL'];
        }

        return $this->currency;
    }

    /**
     * @param string $currency
     *
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }
}
