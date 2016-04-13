<?php  
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_EquipementCaracteristiqueTech extends Cms_Page_Citroen
{  
  
    public static function render(Pelican_Controller $controller)  
    {  
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        $return .= $controller->oForm->createInput($controller->multi . 'ZONE_TITRE', t('TITRE'), 255, '', true, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 75);
        $return .= $controller->oForm->createEditor($controller->multi . 'ZONE_TEXTE', t('INTRO'), true, $controller->zoneValues['ZONE_TEXTE'], $controller->readO, true);
        $return .= Backoffice_Form_Helper::getMentionsLegales($controller, false, 'cinemascope');
        $return .= $controller->oForm->showSeparator("formSep");
        $return .= $controller->oForm->createInput($controller->multi . 'ZONE_TITRE2', t('SHARER_TITLE'), 255, '', true, $controller->zoneValues['ZONE_TITRE2'], $controller->readO, 75);
        $return .= Backoffice_Form_Helper::getFormGroupeReseauxSociaux($controller, 'ZONE_CRITERIA_ID', 'PUBLIC');
        return $return;   
    }  
      
       public static function save(Pelican_Controller $controller)  
    {  
        $oConnection = Pelican_Db::getInstance();  
          
         // {CODE}    
          
         parent::save();  
    }  
}
?>
