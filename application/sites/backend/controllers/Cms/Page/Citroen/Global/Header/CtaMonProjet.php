<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_Global_Header_CtaMonProjet extends Cms_Page_Citroen
{

    public static function render(Pelican_Controller $controller)
    {
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('LIBELLE'), 40, "", true, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 50);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_URL", t('LIEN'), 255, "internallink", true, $controller->zoneValues['ZONE_URL'], $controller->readO, 75);
        return $return; 
    }

    public static function save(Pelican_Controller $controller)
    {
        parent::save();
    }

}