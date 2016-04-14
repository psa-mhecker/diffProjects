<?php

include Pelican::$config['PLUGIN_ROOT'].'/configurator/conf/configurator.ini.php';
//include_once Pelican::$config['CONFIGURATOR_HELPERS'].'/Form.php';

/**
 * Ce controleur permet d'administrer les parametres back-office GENERAUX pour le module configurator
 *
 * @author Herve Lechevallier <herve.lechevallier@businessdecision.com>
 *
 * @since 15/12/2015
 */
class Configurator_Administration_Configurator_Controller extends Pelican_Controller_Back
{
    protected $administration = true;
    protected $defaultAction = 'edit';

    // Les parametres geres en back office
    const CONFIGURATOR_CHOOSE_PRICE_DISPLAY = 'CONFIGURATOR_CHOOSE_PRICE_DISPLAY';
    const CONFIGURATOR_CURRENCY = 'CONFIGURATOR_CURRENCY';

    private static function getTemplateId($path)
    {
        $oConnection = Pelican_Db::getInstance ();
        $aBind[':PATH'] = $oConnection->strToBind($path);
        return $oConnection->queryItem("SELECT TEMPLATE_ID FROM #pref#_template WHERE TEMPLATE_PATH = :PATH", $aBind);

    }

    public function indexAction()
    {
        parent::editAction();

        $head = $this->getView()->getHead();
        $head->setJs(Pelican_Plugin::getMediaPath('configurator') . 'DS/desktop/bower_components/jquery/dist/jquery.min.js');

        $form = $this->startStandardForm();

        if ($this->getParam('tosave') == 'true') {
            $this->doSaveForm();
            echo t('CONFIGURATOR_CONFIG_GAL_SAVED') . '<br/>';
        }

        // get all key values
        $tblKeyValues = self::getConfiguratorKeyValues();

        $form = '<form name="fFormExport" id="fFormExport" action="/_/module/configurator/Configurator_Administration_Configurator/save" method="post">';
        $form .= "<h2 colspan='2'>" . t('CONFIGURATOR_GENERAL_CONFIGURATION') . "</h2>";

        // configuration du prix
        $aPriceConfigValues[0] = t('CONFIGURATOR_COST_PRICE');
        $aPriceConfigValues[1] = t('CONFIGURATOR_MONTHLY_PAYMENT');

        $iPriceConfigSelectedValue = (isset($tblKeyValues[self::CONFIGURATOR_CHOOSE_PRICE_DISPLAY]) ? $tblKeyValues[self::CONFIGURATOR_CHOOSE_PRICE_DISPLAY]: '0');
        $form .= $this->oForm->createRadioFromList(self::CONFIGURATOR_CHOOSE_PRICE_DISPLAY, t('CONFIGURATOR_CHOOSE_PRICE_DISPLAY'), $aPriceConfigValues, $iPriceConfigSelectedValue, false, $_GET["readO"]);

        $form .= '<br/><br/>';

        $sConfigCurrency = (isset($tblKeyValues[self::CONFIGURATOR_CURRENCY]) ?  $tblKeyValues[self::CONFIGURATOR_CURRENCY] : 'EUR');
        $form .= $this->oForm->createInput(self::CONFIGURATOR_CURRENCY, t('CONFIGURATOR_CURRENCY'), 3, "", true, $sConfigCurrency, $this->readO, 10);

        $form .= ' (ex: USD)<br/><br/>';

        $form .= '<input name="submitFrm" id="submitFrm" type="button" class="button" value="' . t('CONFIGURATOR_CONFIGURATION_SAVE') . '"/><br />';
        $form .= $this->stopStandardForm();

        $this->aButton["add"] = "";
        $this->aButton["back"] = "";
        $this->aButton["save"] = "";
        Backoffice_Button_Helper::init($this->aButton);

        $form = formToString($this->oForm, $form);

        $templateId = self::getTemplateId('Configurator_Administration_Configurator');
        $url_display_forms = "/_/Index/child?tid=" . $templateId . "&SCREEN=1&tosave=true";

        $script = '<script type="text/javascript">
                    $( document ).ready(function() {
                    $("#submitFrm").on("click", function() {
                        formIsValid = true;
                        if ($("#CONFIGURATOR_CURRENCY").val() == "" || $("#CONFIGURATOR_CURRENCY").val().length != 3) {
                            formIsValid = false;
                        }

                        if (formIsValid) {
                            window.location.href = "' . $url_display_forms . '&' .
                                self::CONFIGURATOR_CHOOSE_PRICE_DISPLAY . '=" + $("input:radio[name=CONFIGURATOR_CHOOSE_PRICE_DISPLAY]:checked").val() + "&' .
                                self::CONFIGURATOR_CURRENCY . '=" + $("#CONFIGURATOR_CURRENCY").val().toUpperCase() ;
                        }
                    });
                    });
              </script>';

        $this->setResponse($form . $script);
    }

    private function doSaveForm()
    {
        $this->saveConfiguratorKeyValue(self::CONFIGURATOR_CHOOSE_PRICE_DISPLAY, $this->getParam(self::CONFIGURATOR_CHOOSE_PRICE_DISPLAY));
        $this->saveConfiguratorKeyValue(self::CONFIGURATOR_CURRENCY, $this->getParam(self::CONFIGURATOR_CURRENCY));
    }

    // ============================= save helper functions ================================

    public static function getConfiguratorKeyValues()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[':CONF_SITE_ID'] = $_SESSION[APP][SITE_ID];
        $itemValues = $oConnection->queryTab('select CONF_KEY, CONF_VALUE from #pref#_configurator_general_configuration where CONF_SITE_ID = :CONF_SITE_ID', $aBind);

        $tbl_result = array();
        for($i = 0; $i < count($itemValues); $i++) {
            $tbl_result[$itemValues[$i]['CONF_KEY']] = $itemValues[$i]['CONF_VALUE'];
        }
        return $tbl_result;
    }

    private function saveConfiguratorKeyValue($key_name, $key_value)
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[':CONF_SITE_ID'] = $_SESSION[APP][SITE_ID];
        $aBind[':CONF_KEY'] = $oConnection->strToBind($key_name);
        $aBind[':CONF_VALUE'] = $oConnection->strToBind($key_value);
        $oConnection->query('replace into #pref#_configurator_general_configuration (CONF_KEY, CONF_SITE_ID, CONF_VALUE) values (:CONF_KEY, :CONF_SITE_ID, :CONF_VALUE)', $aBind);
    }

}
