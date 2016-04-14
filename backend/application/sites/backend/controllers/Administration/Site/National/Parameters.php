<?php

/**
 * Formulaire de gestion de la configuration des paramètres nationaux.
 *
 * @author Pierre Pottié <pierre.pottie@businessdecision.com>
 *
 * @since 27/05/2015
 */
class Administration_Site_National_Parameters_Controller extends Ndp_Controller
{
    protected $administration = true;
    protected $form_name = "site_national_param";
    protected $field_id = 'SITE_ID';

    const DISABLED = 0;
    const YES = 1;
    const NO = 0;
    const JJMMAAAA = 'JJ/MM/AAAA';
    const MMJJAAAA = 'MM/JJ/AAAA';
    const AAAAMMJJ = 'AAAA/MM/JJ';
    const H0_24 = '0-24';
    const H0_24_LABEL = 'NDP_0_TO_24';
    const AM_PM = 'AM/PM';
    const HT = 'HT';
    const TTC = 'TTC';
    const BEFORE_PRICE = 1;
    const AFTER_PRICE = 2;
    const DEFAULT_PRICE_LEGAL_SYMBOL = '*';
    const CASH = 'COMPTANT';
    const MONTHLY = 'MENSUALISE';
    const DEFAULT_CURRENCY_CODE = 'EUR';
    const DEFAULT_CURRENCY_SYMBOL = '€';
    const KILOMETER = 'KM';
    const MILE = 'ML';
    const DEFAULT_DISTANCE_NB_DECIMAL = '0';
    const METER = 'm';
    const CENTIMETER = 'cm';
    const MILLIMETER = 'mm';
    const FEET = 'ft';
    const INCH = 'in';
    const DEFAULT_DIMENSION_NB_DECIMAL = '0';
    const DEFAULT_DIMENSION_MULTIPLIER = '1.0';
    const DEFAULT_VOLUME_NB_DECIMAL = '0';
    const DEFAULT_VOLUME_MULTIPLIER = '1.0';
    const DEFAULT_PAYLOAD_MULTIPLIER = '1.0';
    const LM3 = 'l-m3';
    const GLCL = 'gl-cl';
    const ML = 'ml';
    const BL = 'bl';
    const DEFAULT_TIME_DIFFERENCE = '0';

    private $wsSFG; //Webservice Financement SFG
    protected $form;

    protected $decacheBack = ['NationalParametersBySiteId'];
    /**
     *
     */
    protected function init()
    {
        parent::init();
        $params = $this->getParams();
        $this->id = $params['SITE_ID'];

        $webservice = Pelican_Factory::getInstance('Webservice');
        $webservice->setSiteId($this->id)->setName(Ndp_Webservice::SFG)->getValues();
        $this->wsSFG = $webservice;
    }

    /**
     *
     */
    protected function setEditModel()
    {
        $this->aBind[':SITE_ID'] = (int) $this->id;

        $this->editModel = "SELECT * "
            ." FROM #pref#_".$this->form_name." s"
            ." WHERE s.SITE_ID=:SITE_ID";
    }

    /**
     *
     */
    public function listAction()
    {
        $this->editAction();
    }

    protected function getParametersValues()
    {
        $this->values = self::formatParametersValues($this->values['NATIONAL_PARAMS'], $this->id);
    }

    public static function formatParametersValues($nationalParams, $siteId)
    {
        $parametersValues = json_decode($nationalParams, true);
        $parametersValues['SITE_ID'] = $siteId;

        return $parametersValues;
    }

    /**
     *
     */
    public function editAction()
    {
        self::init();
        parent::editAction();
        $this->getParametersValues();

        $this->form = $this->getParam('oForm');

//        $form = $this->form->createHidden($this->field_id, $this->id, true);

        $form = $this->getFormNumberSetup();
        $form .= $this->getFormDateSetup();
        $form .= $this->getFormCarPrice();
        $form .= $this->getFormOtherPrice();
        $form .= $this->getFormCurrency();
        $form .= $this->getFormDistance();
        $form .= $this->getFormDimension();
        $form .= $this->getFormVolume();
        $form .= $this->getFormPayload();
        $form .= $this->getFormPeugeotFont();

        $this->setResponse($form);
    }

    /**
     *
     * @return string
     */
    public function getFormNumberSetup()
    {
        $form = $this->form->createTitle(t('NDP_NUMBER'));
        $form .= $this->form->showSeparator("formsep");

        $this->setDefaultValueTo('NB_DELIMITER_MILLION', ' ');
        $form .= $this->form->createInput("NATIONAL_PARAMS[NB_DELIMITER_MILLION]", t('NDP_NB_DELIMITER_MILLION'), 1, "", false, stripslashes($this->values ["NB_DELIMITER_MILLION"]), $this->readO, 1);

        $this->setDefaultValueTo('NB_DELIMITER_THOUSAND', ' ');
        $form .= $this->form->createInput("NATIONAL_PARAMS[NB_DELIMITER_THOUSAND]", t('NDP_NB_DELIMITER_THOUSAND'), 1, "", false, stripslashes($this->values ["NB_DELIMITER_THOUSAND"]), $this->readO, 1);

        $this->setDefaultValueTo('NB_DELIMITER_DECIMAL', ',');
        $form .= $this->form->createInput("NATIONAL_PARAMS[NB_DELIMITER_DECIMAL]", t('NDP_NB_DELIMITER_DECIMAL'), 1, "", false, stripslashes($this->values ["NB_DELIMITER_DECIMAL"]), $this->readO, 1);

        return $form;
    }

    /**
     *
     * @return string
     */
    public function getFormDateSetup()
    {
        $form = $this->form->createTitle(t('NDP_DATE'));
        $form .= $this->form->showSeparator("formsep");

        $dateFormat = [
            self::JJMMAAAA => self::JJMMAAAA,
            self::MMJJAAAA => self::MMJJAAAA,
            self::AAAAMMJJ => self::AAAAMMJJ,
        ];
        $this->setDefaultValueTo('FORMAT_DATE', self::JJMMAAAA);
        $form .= $this->form->createComboFromList("NATIONAL_PARAMS[FORMAT_DATE]", t('NDP_FORMAT_DATE'), $dateFormat, $this->values["FORMAT_DATE"], false, $this->readO, 1, false, '', false);

        $hourFormat = [
            self::H0_24 => t(self::H0_24_LABEL),
            self::AM_PM => self::AM_PM,
        ];
        $this->setDefaultValueTo('FORMAT_CLOCK', self::H0_24);
        $form .= $this->form->createComboFromList("NATIONAL_PARAMS[FORMAT_CLOCK]", t('NDP_FORMAT_CLOCK'), $hourFormat, $this->values["FORMAT_CLOCK"], false, $this->readO, 1, false, '', false);

        $timeDiff = [];
        for ($i = -12; $i <= 12; $i++) {
            $timeDiff[$i] = $i;
        }
        $this->setDefaultValueTo('TIME_DIFFERENCE', self::DEFAULT_TIME_DIFFERENCE);
        $form .= $this->form->createComboFromList("NATIONAL_PARAMS[TIME_DIFFERENCE]", t('NDP_TIME_DIFFERENCE'), $timeDiff, $this->values["TIME_DIFFERENCE"], false, $this->readO, 1, false, '', false);

        return $form;
    }

    /**
     *
     * @return string
     */
    public function getFormCarPrice()
    {
        $form = $this->form->createTitle(t('NDP_CAR_PRICE'));
        $form .= $this->form->showSeparator("formsep");

        $form .= $this->form->createDescription(t('NDP_MESSAGE_DISPLAY_PRICE_SITE'));

        $targetCarPrice = [
            self::YES => t('NDP_YES'),
            self::NO => t('NDP_NO'),
        ];
        $type = 'VEHICULE_PRICE_DISPLAY';
        $jsActivation = $this->form->addJsContainerRadio($type);

        $this->setDefaultValueTo('VEHICULE_PRICE_DISPLAY', self::YES);

        $form .= $this->form->createRadioFromList(
            "NATIONAL_PARAMS[VEHICULE_PRICE_DISPLAY]", t('NDP_VEHICULE_PRICE_DISPLAY'), $targetCarPrice, $this->values['VEHICULE_PRICE_DISPLAY'], true, $this->readO, 'h', false, $jsActivation);

        //version du formulaire activé
        $form .= $this->form->addHeadContainer(self::YES, $this->values['VEHICULE_PRICE_DISPLAY'], $type);
        $form .= $this->getFormCashPrice();
        $form .= $this->getFormMonthlyPrice();
        $form .= $this->form->addFootContainer();

        //version du formulaire désactivé
        $form .= $this->form->addHeadContainer(self::NO, $this->values['VEHICULE_PRICE_DISPLAY'], $type);
        $disabled = true;
        $form .= $this->getFormCashPrice($disabled);
        $form .= $this->getFormMonthlyPrice($disabled);
        $form .= $this->form->addFootContainer();

        return $form;
    }

    /**
     *
     * @return string
     */
    public function getFormCashPrice($disabled = false)
    {
        $typeValue = '[CUSTOM]';
        $typeName = '';
        $forceDefaultValue = false;

        if ($disabled) {
            $typeValue = '[DEFAULT]';
            $typeName = '_DEFAULT';
            $forceDefaultValue = true;
            $disabled = 'disabled = "disabled" ';
        }
        $form = $this->form->createTitle(t('NDP_CASH_PRICE'));

        $this->setDefaultValueTo('VEHICULE_PRICE_FROM_POSITION', self::BEFORE_PRICE, $forceDefaultValue);
        $this->setDefaultValueTo('VEHICULE_PRICE_LEGAL_DISPLAY', self::YES, $forceDefaultValue);
        $this->setDefaultValueTo("VEHICULE_PRICE_LEGAL_SYMBOL", self::DEFAULT_PRICE_LEGAL_SYMBOL, $forceDefaultValue);



        $targetPricePosition = [
            self::BEFORE_PRICE => t('NDP_BEFORE_PRICE'),
            self::AFTER_PRICE => t('NDP_AFTER_PRICE'),
        ];

        $form .= $this->form->createRadioFromList(
            "NATIONAL_PARAMS".$typeValue."[VEHICULE_PRICE_FROM_POSITION]", t('NDP_PRICE_FROM_POSITION'), $targetPricePosition, $this->values['VEHICULE_PRICE_FROM_POSITION'], true, $this->readO, 'h', false, $disabled);

        $form .= $this->form->createInput("NATIONAL_PARAMS".$typeValue."[VEHICULE_PRICE_NB_DECIMAL]", t('NDP_NB_DECIMAL'), 10, "number", false, (int) $this->values ["VEHICULE_PRICE_NB_DECIMAL"], $this->readO, 5, false, $disabled);

        $targetPriceDisplay = [
            self::YES => t('NDP_YES'),
            self::NO => t('NDP_NO'),
        ];
        $type = 'PRICE_LEGAL_DISPLAY'.$typeName;
        $jsActivation = $this->form->addJsContainerRadio($type);

        $form .= $this->form->createRadioFromList(
            "NATIONAL_PARAMS".$typeValue."[VEHICULE_PRICE_LEGAL_DISPLAY]", t('NDP_VEHICULE_PRICE_LEGAL_DISPLAY'), $targetPriceDisplay, $this->values['VEHICULE_PRICE_LEGAL_DISPLAY'], true, $this->readO, 'h', false, $jsActivation.$disabled, null, array('infoBull' => array('isIcon' => true, 'message' => 'NDP_LEGAL_MENTION_CASH_PRICE'))
        );

        $form .= $this->form->addHeadContainer(self::YES, $this->values['VEHICULE_PRICE_LEGAL_DISPLAY'], $type);

        $form .= $this->form->createInput("NATIONAL_PARAMS".$typeValue."[VEHICULE_PRICE_LEGAL_SYMBOL]", t('NDP_VEHICULE_PRICE_LEGAL_SYMBOL'), 10, "", true, $this->values ["VEHICULE_PRICE_LEGAL_SYMBOL"], $this->readO, 5, false, $disabled);

        $form .= $this->form->addFootContainer();

        return $form;
    }

    /**
     *
     * @return string
     */
    public function getFormMonthlyPrice($disabled = false)
    {
        $typeValue = '[CUSTOM]';
        $forceDefaultValue = false;
        if ($disabled) {
            $typeValue = '[DEFAULT]';
            $forceDefaultValue = true;
            $disabled = 'disabled = "disabled" ';
        }

        $form = $this->form->createTitle(t('NDP_MONTHLY_PRICE'));

        if ($this->wsSFG->getStatus() == Ndp_Webservice::IS_ON) {
            $this->setDefaultValueTo('VEHICULE_PRICE_MONTHLY_DISPLAY', self::YES, $forceDefaultValue);
            $this->setDefaultValueTo('VEHICULE_PRICE_TYPE_PAYMENT_DEFAULT', self::MONTHLY, $forceDefaultValue);

            $targetPrice = [
                self::YES => t('NDP_YES'),
                self::NO => t('NDP_NO'),
            ];

            $form .= $this->form->createRadioFromList(
                "NATIONAL_PARAMS".$typeValue."[VEHICULE_PRICE_MONTHLY_DISPLAY]", t('NDP_VEHICULE_PRICE_MONTHLY_DISPLAY'), $targetPrice, $this->values['VEHICULE_PRICE_MONTHLY_DISPLAY'], true, $this->readO, 'h', false, $disabled);

            $targetPrice = [
                self::MONTHLY => t('NDP_MONTHLY'),
                self::CASH => t('NDP_CASH'),
            ];

            $form .= $this->form->createRadioFromList(
                "NATIONAL_PARAMS".$typeValue."[VEHICULE_PRICE_TYPE_PAYMENT_DEFAULT]", t('NDP_VEHICULE_PRICE_TYPE_PAYMENT_DEFAULT'), $targetPrice, $this->values['VEHICULE_PRICE_TYPE_PAYMENT_DEFAULT'], true, $this->readO, 'h', false, $disabled);
        }

        if ($this->wsSFG->getStatus() == Ndp_Webservice::IS_OFF) {
            $form .= $this->form->createDescription(t('NDP_MSG_WS_SFG_DISABLED'));
            $this->setDefaultValueTo('VEHICULE_PRICE_MONTHLY_DISPLAY', self::NO, $forceDefaultValue);
            $form .= $this->form->createHidden('NATIONAL_PARAMS'.$typeValue.'[VEHICULE_PRICE_MONTHLY_DISPLAY]', $this->values['VEHICULE_PRICE_MONTHLY_DISPLAY'], true);
            $this->setDefaultValueTo('VEHICULE_PRICE_TYPE_PAYMENT_DEFAULT', self::CASH, $forceDefaultValue);
            $form .= $this->form->createHidden('NATIONAL_PARAMS'.$typeValue.'[VEHICULE_PRICE_TYPE_PAYMENT_DEFAULT]', $this->values['VEHICULE_PRICE_TYPE_PAYMENT_DEFAULT'], true);
        }

        return $form;
    }

    /**
     *
     * @return string
     */
    public function getFormOtherPrice()
    {
        $form = $this->form->createTitle(t('NDP_OTHER_PRICE'));
        $form .= $this->form->showSeparator("formsep");

        $targetPrice = [
            self::HT => t('NDP_HT'),
            self::TTC => t('NDP_TTC'),
        ];
        $this->setDefaultValueTo('OTHER_PRICE_TYPE', self::TTC);
        $form .= $this->form->createRadioFromList(
            "NATIONAL_PARAMS[OTHER_PRICE_TYPE]", t('NDP_OTHER_PRICE_TYPE'), $targetPrice, $this->values['OTHER_PRICE_TYPE'], true, $this->readO, 'h');

        $targetPricePosition = [
            self::BEFORE_PRICE => t('NDP_BEFORE_PRICE'),
            self::AFTER_PRICE => t('NDP_AFTER_PRICE'),
        ];
        $this->setDefaultValueTo('OTHER_PRICE_FROM_POSITION', self::BEFORE_PRICE);
        $form .= $this->form->createRadioFromList(
            "NATIONAL_PARAMS[OTHER_PRICE_FROM_POSITION]", t('NDP_PRICE_FROM_POSITION'), $targetPricePosition, $this->values['OTHER_PRICE_FROM_POSITION'], true, $this->readO, 'h');

        $form .= $this->form->createInput("NATIONAL_PARAMS[OTHER_PRICE_NB_DECIMAL]", t('NDP_NB_DECIMAL'), 10, "number", false, stripslashes($this->values ["OTHER_PRICE_NB_DECIMAL"]), $this->readO, 5);

        return $form;
    }

    /**
     *
     * @return string
     */
    public function getFormCurrency()
    {
        $form = $this->form->createTitle(t('NDP_CURRENCY'));
        $form .= $this->form->showSeparator("formsep");

        $this->setDefaultValueTo('CURRENCY_CODE', self::DEFAULT_CURRENCY_CODE);
        $form .= $this->form->createInput("NATIONAL_PARAMS[CURRENCY_CODE]", t('NDP_CURRENCY_CODE'), 3, "", false, stripslashes($this->values["CURRENCY_CODE"]), $this->readO, 3);

        $this->setDefaultValueTo('CURRENCY_SYMBOL', self::DEFAULT_CURRENCY_SYMBOL);
        $form .= $this->form->createInput("NATIONAL_PARAMS[CURRENCY_SYMBOL]", t('NDP_CURRENCY_SYMBOL'), 3, "", false, stripslashes($this->values["CURRENCY_SYMBOL"]), $this->readO, 3);

        $targetPricePosition = [
            self::BEFORE_PRICE => t('NDP_BEFORE_PRICE'),
            self::AFTER_PRICE => t('NDP_AFTER_PRICE'),
        ];
        $form .= $this->form->createRadioFromList(
            "NATIONAL_PARAMS[CURRENCY_POSITION]", t('NDP_CURRENCY_POSITION'), $targetPricePosition, $this->values['CURRENCY_POSITION'], false, $this->readO, 'h');

        $targetPrice = [
            self::YES => t('NDP_YES'),
            self::NO => t('NDP_NO'),
        ];
        $form .= $this->form->createRadioFromList(
            "NATIONAL_PARAMS[CURRENCY_USE_LOCAL]", t('NDP_CURRENCY_USE_LOCAL'), $targetPrice, $this->values['CURRENCY_USE_LOCAL'], false, $this->readO, 'h');

        return $form;
    }

    /**
     *
     * @return string
     */
    public function getFormDistance()
    {
        $form = $this->form->createTitle(t('NDP_DISTANCE'));
        $form .= $this->form->showSeparator("formsep");

        $targetDistanceUnit = [
            self::KILOMETER => t('NDP_KILOMETER'),
            self::MILE => t('NDP_MILE'),
        ];
        $form .= $this->form->createRadioFromList(
            "NATIONAL_PARAMS[DISTANCE_UNIT]", t('NDP_DISTANCE_UNIT'), $targetDistanceUnit, $this->values['DISTANCE_UNIT'], false, $this->readO, 'h');

        $this->setDefaultValueTo('DISTANCE_NB_DECIMAL', self::DEFAULT_DISTANCE_NB_DECIMAL);
        $form .= $this->form->createInput("NATIONAL_PARAMS[DISTANCE_NB_DECIMAL]", t('NDP_NB_DECIMAL'), 10, "number", false, stripslashes($this->values ["DISTANCE_NB_DECIMAL"]), $this->readO, 5);

        return $form;
    }

    /**
     *
     * @return string
     */
    public function getFormDimension()
    {
        $form = $this->form->createTitle(t('NDP_DIMENSION'));
        $form .= $this->form->showSeparator("formsep");

        $dimensionUnit = [
            self::METER => t('NDP_METER'),
            self::CENTIMETER => t('NDP_CENTIMETER'),
            self::MILLIMETER => t('NDP_MILLIMETER'),
            self::FEET => t('NDP_FEET'),
            self::INCH => t('NDP_INCH'),
        ];

        $form .= $this->form->createComboFromList("NATIONAL_PARAMS[DIMENSION_UNIT]", t('NDP_DIMENSION_UNIT'), $dimensionUnit, $this->values["DIMENSION_UNIT"], false, $this->readO, 1, false, '', false);

        $this->setDefaultValueTo('DIMENSION_NB_DECIMAL', self::DEFAULT_DIMENSION_NB_DECIMAL);
        $form .= $this->form->createInput("NATIONAL_PARAMS[DIMENSION_NB_DECIMAL]", t('NDP_NB_DECIMAL'), 10, "number", false, $this->values ["DIMENSION_NB_DECIMAL"], $this->readO, 5);

        $this->setDefaultValueTo('DIMENSION_MULTIPLIER', self::DEFAULT_DIMENSION_MULTIPLIER);
        $form .= $this->form->createInput("NATIONAL_PARAMS[DIMENSION_MULTIPLIER]", t('NDP_MULTIPLIER'), 10, "float", false, $this->values ["DIMENSION_MULTIPLIER"], $this->readO, 5);
        $form .= $this->form->createLabel('', t('NDP_DIMENSION_MULTIPLIER'));

        return $form;
    }

    /**
     *
     * @return string
     */
    public function getFormVolume()
    {
        $form = $this->form->createTitle(t('NDP_VOLUME'));
        $form .= $this->form->showSeparator("formsep");

        $volumeUnit = [
            self::LM3 => t('NDP_LM3'),
            self::GLCL => t('NDP_GLCL'),
            self::ML => t('NDP_ML'),
            self::BL => t('NDP_BL'),
        ];

        $form .= $this->form->createComboFromList("NATIONAL_PARAMS[VOLUME_UNIT]", t('NDP_VOLUME_UNIT'), $volumeUnit, $this->values["VOLUME_UNIT"], false, $this->readO, 1, false, '', false);

        $this->setDefaultValueTo('VOLUME_NB_DECIMAL', self::DEFAULT_VOLUME_NB_DECIMAL);
        $form .= $this->form->createInput("NATIONAL_PARAMS[VOLUME_NB_DECIMAL]", t('NDP_NB_DECIMAL'), 10, "number", false, $this->values ["VOLUME_NB_DECIMAL"], $this->readO, 5);

        $this->setDefaultValueTo('VOLUME_MULTIPLIER', self::DEFAULT_VOLUME_MULTIPLIER);
        $form .= $this->form->createInput("NATIONAL_PARAMS[VOLUME_MULTIPLIER]", t('NDP_MULTIPLIER'), 10, "float", false, $this->values ["VOLUME_MULTIPLIER"], $this->readO, 5);
        $form .= $this->form->createLabel('', t('NDP_VOLUME_MULTIPLIER'));

        return $form;
    }

    /**
     *
     * @return string
     */
    public function getFormPayload()
    {
        $form = $this->form->createTitle(t('NDP_PAYLOAD'));
        $form .= $this->form->showSeparator("formsep");

        $this->setDefaultValueTo('PAYLOAD_MULTIPLIER', self::DEFAULT_PAYLOAD_MULTIPLIER);
        $form .= $this->form->createInput("NATIONAL_PARAMS[PAYLOAD_MULTIPLIER]", t('NDP_MULTIPLIER'), 10, "float", false, stripslashes($this->values ["PAYLOAD_MULTIPLIER"]), $this->readO, 5);
        $form .= $this->form->createLabel('', t('NDP_PAYLOAD_MULTIPLIER'));

        return $form;
    }

    /**
     *
     * @return string
     */
    public function getFormPeugeotFont()
    {
        $form = $this->form->createTitle(t('NDP_PEUGEOT_FONT'));
        $form .= $this->form->showSeparator("formsep");

        $useProperties = [
            self::YES => t('NDP_YES'),
            self::NO => t('NDP_NO'),
        ];

        $this->setDefaultValueTo('USE_PEUGEOT_FONT', self::YES);

        $form .= $this->form->createRadioFromList(
            "NATIONAL_PARAMS[USE_PEUGEOT_FONT]", t('NDP_USE_PEUGEOT_FONT'), $useProperties, $this->values['USE_PEUGEOT_FONT'], false, $this->readO, 'h');

        return $form;
    }

    /**
     *
     */
    public function saveAction()
    {
        self::init();
        $save = Pelican_Db::$values;
        Pelican_Db::$values['SITE_ID'] = $this->id;

        if (Pelican_Db::$values['NATIONAL_PARAMS']['VEHICULE_PRICE_DISPLAY'] == self::YES) {
            foreach (Pelican_Db::$values['NATIONAL_PARAMS']['CUSTOM'] as $key => $value) {
                Pelican_Db::$values['NATIONAL_PARAMS'][$key] = $value;
            }
        }
        if (Pelican_Db::$values['NATIONAL_PARAMS']['VEHICULE_PRICE_DISPLAY'] == self::NO) {
            foreach (Pelican_Db::$values['NATIONAL_PARAMS']['DEFAULT'] as $key => $value) {
                Pelican_Db::$values['NATIONAL_PARAMS'][$key] = $value;
            }
        }
        unset(Pelican_Db::$values['NATIONAL_PARAMS']['ENABLED']);
        unset(Pelican_Db::$values['NATIONAL_PARAMS']['DEFAULT']);

        Pelican_Db::$values['NATIONAL_PARAMS'] = json_encode(Pelican_Db::$values['NATIONAL_PARAMS'], JSON_UNESCAPED_UNICODE);

        $this->setFormValues();
        $connection = Pelican_Db::getInstance();

        if (!$this->values['SITE_ID']) {
            $connection->insertQuery('#pref#_'.$this->form_name);
        } else {
            $connection->updateQuery('#pref#_'.$this->form_name);
        }
        Pelican_Db::$values = $save;
    }

    /*
     * Retourne les parameters values d'un site (appelé depuis d'autres fonctionnalités)
     *
     * @return array tableau de données issue du json du champ
     */
    public static function getParametersValuesBySiteId($siteId)
    {
        $bind[':SITE_ID'] = (int) $siteId;

        $sql = "SELECT NATIONAL_PARAMS "
          ." FROM #pref#_site_national_param "
          ." WHERE SITE_ID=:SITE_ID";

        $connection = Pelican_Db::getInstance();
        $resultSql = $connection->queryItem($sql, $bind);
        if (!empty($resultSql)) {
            $result = self::formatParametersValues($resultSql, $siteId);
        }

        return $result;
    }
}
