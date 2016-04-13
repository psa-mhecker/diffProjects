<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_VehicleSelector extends Cms_Page_Citroen
{

    public static function render(Pelican_Controller $controller)
    {
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 255, "", true, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 75);
        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
    }
}