<?php

include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');

class Cms_Page_Citroen_Accessoires extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {	
        $oConnection = Pelican_Db::getInstance ();
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        $return .= $controller->oForm->createEditor($controller->multi . "ZONE_TEXTE", t('TEXTE'), false, $controller->zoneValues["ZONE_TEXTE"], $controller->readO, true, "", 500, 150);
        $return .= Backoffice_Form_Helper::getVehicule($controller, false);
        return $return;
    }
		
    public static function save(Pelican_Controller $controller)
    {
        parent::save();	
    }

}