<?php  
  
class Cms_Page_Citroen_2ColonnesMixtePortrait extends Cms_Page_Citroen  
{  
  
    public static function render(Pelican_Controller $controller)  
    {  
        $return .= Backoffice_Form_Helper::getForm($controller->zoneValues["ZONE_BO_PATH"], $controller);
  
        return $return;   
    }  
      
       public static function save(Pelican_Controller $controller)
    {  
        Backoffice_Form_Helper::saveFormAffichage();
        if (Pelican_Db::$values["form_action"] != Pelican_Db::DATABASE_DELETE){
            Backoffice_Form_Helper::saveCta();
        }
          
         parent::save();  
    }  
}  