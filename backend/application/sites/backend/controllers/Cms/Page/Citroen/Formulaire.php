<?php
include_once Pelican::$config['CONTROLLERS_ROOT']."/Cms/Page/Module.php";
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_Formulaire extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        //$return = Backoffice_Form_Helper::getFormAffichage($controller, true, true, true);
        $return = $controller->oForm->createComboFromList($controller->multi."ZONE_ATTRIBUT", t("ACTIVATION_FORMULAIRE"), Pelican::$config['TRANCHE_COL']['WEBMOB'], ($controller->zoneValues['PAGE_ID'] == -2) ? 0 : $controller->zoneValues['ZONE_ATTRIBUT'], true, $controller->readO);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 255, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->read0, 100);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('TITRE_INTERNE'), 255, "", true, $controller->zoneValues["ZONE_TITRE2"], $controller->read0, 100);
        //$return .= $controller->oForm->createTextArea($controller->multi . "ZONE_TEXTE", t('CHAPEAU'), false, $controller->zoneValues["ZONE_TEXTE"], "", $controller->readO, 2, 100, false, "", false);
        $return .= $controller->oForm->createEditor($controller->multi."ZONE_TEXTE", t('CHAPEAU'), false, $controller->zoneValues["ZONE_TEXTE"], $controller->readO, true, "", 650, 150);
        $oConnection = Pelican_Db::getInstance();
        if ($controller->zoneValues['TEMPLATE_PAGE_ID'] != Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION']) {
            $SQL = "
                SELECT
                    FORM_TYPE_ID,
                    FORM_TYPE_LABEL
                FROM
                    #pref#_form_type";
            $results = $oConnection->queryTab($SQL);
            $forms = array();
            foreach ($results as $OneValue) {
                $forms[$OneValue['FORM_TYPE_ID']] = $OneValue['FORM_TYPE_LABEL'];
            }
            $return .= $controller->oForm->createComboFromList($controller->multi."ZONE_TITRE3", t("TYPE_FORM"), $forms, $controller->zoneValues["ZONE_TITRE3"], true, $controller->readO);
            $tab = array('CHOIX' => t('CHOIX_FO'), 'PRO' => t('PRO'), 'IND' => t('PARTICULIER'));
            $return .= $controller->oForm->createComboFromList($controller->multi."ZONE_TITRE4", t("PRO_PARTICULIER"), $tab, $controller->zoneValues["ZONE_TITRE4"], true, $controller->readO);
        } else {
            $return .= $controller->oForm->createHidden($controller->multi."ZONE_ATTRIBUT2", 2);
            $return .= $controller->oForm->createHidden($controller->multi."ZONE_TITRE4", 'CHOIX');
        }
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi."ZONE_PARAMETERS", t('CTA_ACCUEIL'), array(1 => ""), $controller->zoneValues['ZONE_PARAMETERS'], false, $controller->readO);

        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE8", t('TITRE'), 255, "", false, $controller->zoneValues["ZONE_TITRE8"], $controller->read0, 100);
        $return .= $controller->oForm->createEditor($controller->multi."ZONE_TEXTE2", t('MESSAGE_REMERCIEMENT'), false, $controller->zoneValues["ZONE_TEXTE2"], $controller->readO, true, "", 650, 150);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE9", t('TITRE_SHARER'), 255, "", false, $controller->zoneValues['ZONE_TITRE9'], $controller->readO, 75);
        $return .= Backoffice_Form_Helper::getFormGroupeReseauxSociaux($controller, 'ZONE_LABEL2');
        $return .= Backoffice_Form_Helper::getMentionsLegales($controller, false, 'cinemascope');
        $return .= Backoffice_Form_Helper::getCta($controller, 5);

        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        //Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
        Backoffice_Form_Helper::saveCta();
    }
}
