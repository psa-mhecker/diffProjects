<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_Global_Header_LibHome extends Cms_Page_Citroen  
{  
    public static function render(Pelican_Controller $controller)  
    {  
        $return = Backoffice_Form_Helper::getFormAffichage($controller, false, true);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE", t ( 'LIBELLE_HOME' ), 50, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 50);
  
        return $return;   
    }  
      
    public static function save(Pelican_Controller $controller)  
    {      
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();  
    }  
}
?>