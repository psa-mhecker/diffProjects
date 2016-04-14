<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_MasterPage_VehiculesN1 extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE_BLOC_AUTRES_VEHICULES'), 255, "", true, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 70);
        $return .= Backoffice_Form_Helper::getMentionsLegales($controller, false, 'cinemascope');

        return $return;
    }
}
