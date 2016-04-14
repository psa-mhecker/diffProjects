<?php

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';

class Cms_Page_Citroen_404 extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITLE'), 255, "", true, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 50);

        $return .= $controller->oForm->createEditor($controller->multi."ZONE_TEXTE", t('MESSAGE'), true, $controller->zoneValues["ZONE_TEXTE"], $controller->readO, true, "", 500, 150);

        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('LIB_SEARCH'), 255, "", true, $controller->zoneValues["ZONE_TITRE2"], $controller->readO, 50);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE3", t('LIB_HOME'), 40, "", true, $controller->zoneValues["ZONE_TITRE3"], $controller->readO, 50);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE4", t('LIB_PLAN_SITE'), 40, "", true, $controller->zoneValues["ZONE_TITRE4"], $controller->readO, 50);

        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        $oConnection = Pelican_Db::getInstance();

         // {CODE}

         parent::save();
    }
}
