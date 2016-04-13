<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_Verbatim extends Cms_Page_Citroen 
{  
    public static function render(Pelican_Controller $controller)  
    {  
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
		$return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 255, "", false, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 75);
        $return .= $controller->oForm->createMedia($controller->multi."MEDIA_ID", t('VISUEL'), true, "image", "", $controller->zoneValues["MEDIA_ID"], $controller->readO, true, false);
        $return .= $controller->oForm->createTextArea($controller->multi . "ZONE_TEXTE", t('VERBATIM'), true, $controller->zoneValues["ZONE_TEXTE"], "", $controller->readO, 5, 100, false, "", false);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TEXTE2", t('TEXTE'), 255, "", false, $controller->zoneValues["ZONE_TEXTE2"], $controller->readO, 50); 

        return $return;   
    }  
      
    public static function save(Pelican_Controller $controller)  
    {  
        $oConnection = Pelican_Db::getInstance();  
          
        Backoffice_Form_Helper::saveFormAffichage();   
          
         parent::save();  
    }  
}  
?>
