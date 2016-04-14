<?php
include_once Pelican::$config['CONTROLLERS_ROOT']."/Cms/Page/Module/Navigation/2levels.php";

class Cms_Page_Citroen_Global_Header_ShoppingTools extends Cms_Page_Module_Navigation_2levels
{
    public static function render(Pelican_Controller $controller)
    {
        $return .= t("MINIMUM_LINK_SHOP");
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 40, "", false, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 50);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('LIBELLE'), 255, "", false, $controller->zoneValues['ZONE_TITRE2'], $controller->readO, 75);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_URL", t('URL'), 255, "internallink", false, $controller->zoneValues['ZONE_URL'], $controller->readO, 75);
        //$return .= $controller->oForm->createInput ( $controller->multi."ZONE_URL", t ( 'Url URL' ), 255, "internallink", false, $controller->zoneValues ["SITE_MAINTENANCE_URL"], $controller->readO, 75, false, "", "text", array(), false, "" );
        $return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_TITRE3", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $controller->zoneValues['ZONE_TITRE3'], false, $controller->readO);

        return $return;
    }
}
