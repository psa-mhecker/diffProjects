<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_ContenuTexteCTA extends Cms_Page_Citroen
{

    public static function render(Pelican_Controller $controller)
    {
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 255, "", false, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 75);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('SOUS_TITRE'), 255, "", false, $controller->zoneValues['ZONE_TITRE2'], $controller->readO, 75);
        $return .= $controller->oForm->createEditor($controller->multi."ZONE_TEXTE", t('TEXTE'), true, $controller->zoneValues['ZONE_TEXTE'], $controller->readO, true, "", 500, 200);
        $return .= Backoffice_Form_Helper::getCta($controller, 2);
        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
        Backoffice_Form_Helper::saveCta();
    }

}