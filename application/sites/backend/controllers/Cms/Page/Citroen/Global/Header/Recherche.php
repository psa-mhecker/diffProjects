<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_Global_Header_Recherche extends Cms_Page_Citroen
{

    public static function render(Pelican_Controller $controller)
    {
        $return .= $controller->oForm->createComboFromList($controller->multi."ZONE_TITRE2", t("ACTIVATION_CHAMP_RECHERCHE"), Pelican::$config['TRANCHE_COL']['WEBMOB'], ($controller->zoneValues['PAGE_ID'] == -2)?0:$controller->zoneValues['ZONE_TITRE2'], true, $controller->readO);
        $return .= $controller->oForm->createComboFromList($controller->multi."ZONE_TITRE3", t("ACTIVATION_AUTOCOMPLETION"), Pelican::$config['TRANCHE_COL']["WEBMOB"], ($controller->zoneValues['PAGE_ID'] == -2)?0:$controller->zoneValues['ZONE_TITRE3'], true, $controller->readO);
        return $return;
    }

}