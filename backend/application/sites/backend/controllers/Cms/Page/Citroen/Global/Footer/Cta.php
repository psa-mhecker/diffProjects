<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_Global_Footer_Cta extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $return .= $controller->oForm->showSeparator();
        $return .= $controller->oForm->createLabel(t('SITES_APPLICATIONS_MOBILES'), "");
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('LIBELLE'), 50, "", true, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 50);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_URL", t('URL'), 255, "internallink", true, $controller->zoneValues['ZONE_URL'], $controller->readO, 75);
        $return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_TITRE3", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $controller->zoneValues['ZONE_TITRE3'], true, $controller->readO);
        $return .= $controller->oForm->showSeparator();
        $return .= $controller->oForm->createLabel(t('BESOIN_AIDE'), "");
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('LIBELLE'), 50, "", true, $controller->zoneValues['ZONE_TITRE2'], $controller->readO, 50);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_URL2", t('URL'), 255, "internallink", true, $controller->zoneValues['ZONE_URL2'], $controller->readO, 75);
        $return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_TITRE4", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $controller->zoneValues['ZONE_TITRE4'], true, $controller->readO);

        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        parent::save();
    }
}
