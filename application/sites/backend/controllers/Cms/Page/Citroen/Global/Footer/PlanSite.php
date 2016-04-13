<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_Global_Footer_PlanSite extends Cms_Page_Citroen
{

    public static function render(Pelican_Controller $controller)
    {
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 50, "", false, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 50);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE3", t('URL_PLAN_SITE'), 255, "internallink", false, $controller->zoneValues['ZONE_TITRE3'], $controller->readO, 90);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('LIBELLE_LANGUETTE'), 20, "", true, $controller->zoneValues['ZONE_TITRE2'], $controller->readO, 20);
        return $return; 
    }

    public static function save(Pelican_Controller $controller)
    {
        parent::save();
    }

}