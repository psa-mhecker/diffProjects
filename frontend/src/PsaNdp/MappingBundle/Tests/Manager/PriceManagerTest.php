<?php

use PsaNdp\MappingBundle\Manager\PriceManager;
use PsaNdp\MappingBundle\Object\Formatter\PriceFormatter;
use PsaNdp\MappingBundle\Utils\SiteConfiguration;
use PsaNdp\WebserviceConsumerBundle\Webservices\FinancementSimulator;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class PriceManagerTest.
 */
class PriceManagerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var FinancementSimulator
     */
    protected $financementSimulator;
    /**
     * @var SiteConfiguration
     */
    protected $siteConfiguration;
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var PriceManager
     */
    protected $priceManager;

    /**
     * @var PriceFormatter
     */
    protected $priceFormatter;

    /**
     * Set Up the test.
     */
    public function setUp()
    {
        $this->financementSimulator = Phake::mock('PsaNdp\WebserviceConsumerBundle\Webservices\FinancementSimulator');
        $this->siteConfiguration = Phake::mock('PsaNdp\MappingBundle\Utils\SiteConfiguration');
        $this->priceFormatter = Phake::mock('PsaNdp\MappingBundle\Object\Formatter\PriceFormatter');
        Phake::when($this->priceFormatter)->getOrderedPricePart(50)->thenReturn('<small>FROM</small> <strong>50 $</strong> TTC*');

        $parameterBag = Phake::mock('Symfony\Component\HttpFoundation\ParameterBag');
        Phake::when($parameterBag)->get('siteId')->ThenReturn(2);
        Phake::when($parameterBag)->get('language')->ThenReturn('fr');
        $request = Phake::mock('Symfony\Component\HttpFoundation\Request');
        $request->attributes = $parameterBag;
        $this->requestStack = Phake::mock('Symfony\Component\HttpFoundation\RequestStack');
        Phake::when($this->requestStack)->getCurrentRequest()->ThenReturn($request);
    }

    /**
     * @param bool $showPriceSite
     * @param bool $showPriceModel
     * @param bool $expected
     *
     * @dataProvider providerCanShowPrice
     */
    public function testCanShowPrice($showPriceSite, $showPriceModel, $expected)
    {
        Phake::when($this->siteConfiguration)->getParameters()->thenReturn(array('VEHICULE_PRICE_DISPLAY' => $showPriceSite));
        Phake::when($this->siteConfiguration)->getNationalParameters()->thenReturn(array(
            'CURRENCY_SYMBOL' => '$',
            'VEHICULE_PRICE_NB_DECIMAL' => '0',
            'NB_DELIMITER_DECIMAL' => '.',
            'NB_DELIMITER_THOUSAND' => ' ',
            'VEHICULE_PRICE_LEGAL_SYMBOL' => '*',
            'VEHICULE_PRICE_LEGAL_DISPLAY' => true,
            'CURRENCY_POSITION' => 0,
            'VEHICULE_PRICE_FROM_POSITION' => 1,
        ));
        Phake::when($this->siteConfiguration)->getNationalParameter('CUSTOM')->thenReturn(array('CUSTOM' => array()));
        $information = Phake::mock('PsaNdp\MappingBundle\Entity\Vehicle\PsaModelSilhouetteSite');
        Phake::when($information)->isDisplayPrice()->ThenReturn($showPriceModel);

        $this->priceManager = new PriceManager($this->siteConfiguration, $this->financementSimulator, $this->requestStack, $this->priceFormatter);
        $this->priceManager->setModelSilhouetteInformation($information);

        $result = $this->priceManager->canShowPrice();

        $this->assertSame($expected, $result);
    }

    /**
     * @return array
     */
    public function providerCanShowPrice()
    {
        return array(
            array(false, false, false),
            array(true, false, false),
            array(true, true, true),
        );
    }

    /**
     * @param bool   $priceLegal
     * @param bool   $priceDisplay
     * @param string $priceType
     * @param bool   $expected
     *
     * @dataProvider providerCanShowMentions
     */
    public function testCanShowMentions($priceLegal, $priceDisplay, $priceType, $expected)
    {
        Phake::when($this->siteConfiguration)->getParameters()->ThenReturn(array(
            'VEHICULE_PRICE_LEGAL_DISPLAY' => $priceLegal,
            'VEHICULE_PRICE_MONTHLY_DISPLAY' => $priceDisplay,
            'VEHICULE_PRICE_TYPE_PAYMENT_DEFAULT' => $priceType,
            'CURRENCY_SYMBOL' => '$',
            'VEHICULE_PRICE_NB_DECIMAL' => '0',
            'NB_DELIMITER_DECIMAL' => '.',
            'NB_DELIMITER_THOUSAND' => ' ',
            'VEHICULE_PRICE_LEGAL_SYMBOL' => '*',
            'CURRENCY_POSITION' => 0,
            'VEHICULE_PRICE_FROM_POSITION' => 1,
        ));
        Phake::when($this->siteConfiguration)->getNationalParameters()->ThenReturn(array());
        Phake::when($this->siteConfiguration)->getNationalParameter('CUSTOM')->ThenReturn(array('CUSTOM' => array()));

        $this->priceManager = new PriceManager($this->siteConfiguration, $this->financementSimulator, $this->requestStack, $this->priceFormatter);

        $result = $this->priceManager->canShowNotice();

        $this->assertSame($expected, $result);
    }

    /**
     * @return array
     */
    public function providerCanShowMentions()
    {
        return array(
            array(false, false, '', false),
            array(true, false, '', true),
            array(true, true, '', true),
            array(true, true, 'MENSUALISE', false),
        );
    }

    /**
     * Test getFinancementDetailsTexts.
     */
    public function testGetFinancementDetailsTexts()
    {
        $key = 'key';

        Phake::when($this->financementSimulator)->isEnabled()->ThenReturn(true);
        Phake::when($this->siteConfiguration)->getParameters()->ThenReturn(array(
            'CURRENCY_SYMBOL' => '$',
            'VEHICULE_PRICE_NB_DECIMAL' => '0',
            'NB_DELIMITER_DECIMAL' => '.',
            'NB_DELIMITER_THOUSAND' => ' ',
            'VEHICULE_PRICE_LEGAL_SYMBOL' => '*',
            'VEHICULE_PRICE_LEGAL_DISPLAY' => true,
            'CURRENCY_POSITION' => 0,
            'VEHICULE_PRICE_FROM_POSITION' => 1,
        ));
        Phake::when($this->siteConfiguration)->getNationalParameters()->ThenReturn(array());
        Phake::when($this->siteConfiguration)->getNationalParameter('CUSTOM')->ThenReturn(array('CUSTOM' => array()));
        $this->priceManager = new PriceManager($this->siteConfiguration, $this->financementSimulator, $this->requestStack, $this->priceFormatter);
        $this->priceManager->setSfg(array('financementDetails' => array($key => $key)));

        $result = $this->priceManager->getFinancementDetailsTexts($key);

        $this->assertSame($key, $result);
    }

    /**
     * Test getCashPrice.
     */
    public function testGetCashPrice()
    {
        $translator = Phake::mock('Symfony\Component\Translation\TranslatorInterface');
        Phake::when($translator)->trans('NDP_TTC', array(), null, null)->ThenReturn('TTC');
        Phake::when($translator)->trans('NDP_FROM', array(), null, null)->ThenReturn('FROM');
        $site = Phake::mock('PSA\MigrationBundle\Entity\Site\PsaSite');
        Phake::when($site)->getCountryCode()->ThenReturn('FR');
        Phake::when($this->siteConfiguration)->getParameters()->ThenReturn(array(
            'CURRENCY_SYMBOL' => '$',
            'VEHICULE_PRICE_NB_DECIMAL' => '0',
            'NB_DELIMITER_DECIMAL' => '.',
            'NB_DELIMITER_THOUSAND' => ' ',
        ));
        Phake::when($this->siteConfiguration)->getNationalParameters()->ThenReturn(array(
            'CURRENCY_CODE' => '973',
            'VEHICULE_PRICE_LEGAL_SYMBOL' => '*',
            'VEHICULE_PRICE_LEGAL_DISPLAY' => true,
            'CURRENCY_POSITION' => 0,
            'VEHICULE_PRICE_FROM_POSITION' => 1,
        ));
        Phake::when($this->siteConfiguration)->getNationalParameter('CUSTOM')->ThenReturn(array('CUSTOM' => array()));
        Phake::when($this->siteConfiguration)->getSite()->ThenReturn($site);

        $this->priceManager = new PriceManager($this->siteConfiguration, $this->financementSimulator, $this->requestStack, $this->priceFormatter);
        $this->priceManager->setVersion(array('Price' => array('Value' => 30)));
        $this->priceManager->setCheapest(array('Price' => array('Value' => 50), 'LCDV16' => '1264'));
        $this->priceManager->setTranslator($translator);

        $result = $this->priceManager->getCashPrice();

        $this->assertSame('<small>FROM</small> <strong>50 $</strong> TTC*', $result);
    }
}
