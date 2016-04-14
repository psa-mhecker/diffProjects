<?php
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Cms/Page/Citroen.php';

class Cms_Page_Citroen_MultiColonnes extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $return .= Backoffice_Form_Helper::getForm($controller->zoneValues["ZONE_BO_PATH"], $controller);

        return $return;
    }
}
