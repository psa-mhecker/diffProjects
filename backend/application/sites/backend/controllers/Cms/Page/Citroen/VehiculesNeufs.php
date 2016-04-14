<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_VehiculesNeufs extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $oConnection = Pelican_Db::getInstance();
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 255, "", true, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 100);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('SOUS_TITRE'), 255, "", true, $controller->zoneValues["ZONE_TITRE2"], $controller->readO, 100);
        $return .= $controller->oForm->createEditor($controller->multi."ZONE_TEXTE", t('TEXTE'), true, $controller->zoneValues["ZONE_TEXTE"], $controller->readO, true, "", 500, 150);
        $aModeGestion = Pelican::$config['MODE_GESTION'];
        $return .= $controller->oForm->createComboFromList($controller->multi."ZONE_TITRE3", t("MODE_GESTION"), $aModeGestion, ($controller->zoneValues['PAGE_ID'] == -2) ? 0 : $controller->zoneValues['ZONE_TITRE3'], true, $controller->readO);
        $return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_CRITERIA_ID2", t('REGROUPEMENT'), array('1' => t("OUI"), '2' => t("NON")), ($controller->zoneValues['PAGE_ID'] == -2) ? '1' : $controller->zoneValues['ZONE_CRITERIA_ID2'], false, $controller->readO);
        $return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_CRITERIA_ID3", t('AUTOCOMPLETION'), array('1' => t("OUI"), '2' => t("NON")), ($controller->zoneValues['PAGE_ID'] == -2) ? '1' : $controller->zoneValues['ZONE_CRITERIA_ID3'], false, $controller->readO);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE13", t('NB_MAX_PDV'), 4, "", true, ($controller->zoneValues['PAGE_ID'] == -2 || $controller->zoneValues['ZONE_TITRE13'] == '') ? 300 : $controller->zoneValues['ZONE_TITRE13'], $controller->readO, 3);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_ATTRIBUT", t('RAYON'), 3, "", true, ($controller->zoneValues['PAGE_ID'] == -2  || $controller->zoneValues['ZONE_ATTRIBUT'] == '') ? 20 : $controller->zoneValues['ZONE_ATTRIBUT'], $controller->readO, 3);

        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
    }
}
