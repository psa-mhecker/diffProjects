<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_EligibiliteLinkMyCitroen extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $return .= Backoffice_Form_Helper::getFormAffichage($controller, true, true);

        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 255, "", true, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 50);
        $return .= $controller->oForm->createTextArea($controller->multi."ZONE_TEXTE", t('CHAPEAU'), true, $controller->zoneValues["ZONE_TEXTE"], "", $controller->readO, 2, 100, false, "", false);
        $return .= $controller->oForm->createEditor($controller->multi."ZONE_TEXTE2", t('Content'), true, $controller->zoneValues["ZONE_TEXTE2"], $controller->readO, true, "", 500, 150);

        $return .= $controller->oForm->showSeparator();

        $return .= $controller->oForm->createEditor($controller->multi."ZONE_TEXTE3", t('MESSAGE_ELIGIBILITE'), true, $controller->zoneValues["ZONE_TEXTE3"], $controller->readO, true, "", 500, 150);
        $return .= $controller->oForm->createEditor($controller->multi."ZONE_TEXTE4", t('MESSAGE_INELIGIBILITE'), true, $controller->zoneValues["ZONE_TEXTE4"], $controller->readO, true, "", 500, 150);

        return $return;
    }

    public static function save()
    {
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
    }
}
