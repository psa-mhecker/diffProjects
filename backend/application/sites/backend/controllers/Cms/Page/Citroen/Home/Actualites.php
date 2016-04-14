<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_Home_Actualites extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 255, "", true, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 75);
        $return .= $controller->oForm->showSeparator();
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('LIBELLE'), 40, "", true, $controller->zoneValues['ZONE_TITRE2'], $controller->readO, 40);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_URL", t('LIEN_WEB'), 255, "internallink", true, $controller->zoneValues['ZONE_URL'], $controller->readO, 75);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_URL2", t('LIEN_MOBILE'), 255, "internallink", false, $controller->zoneValues['ZONE_URL2'], $controller->readO, 75);
        $return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_TITRE3", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $controller->zoneValues['ZONE_TITRE3'], true, $controller->readO);

        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
    }
}
