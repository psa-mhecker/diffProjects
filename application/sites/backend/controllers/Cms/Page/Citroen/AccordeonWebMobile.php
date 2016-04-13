<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Module.php');
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_AccordeonWebMobile extends Cms_Page_Citroen  
{  
    public static function render(Pelican_Controller $controller)  
    {  
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        
        $aModeAff = array(1=>t("Fermer_tous_les_toggles"),2=>t("Ouvrir_tous_les_toggles"));
        
        if($controller->zoneValues["ZONE_ATTRIBUT"] == ''){
            $ZONE_ATTRIBUT = 1;
        }
        else{
            $ZONE_ATTRIBUT = $controller->zoneValues["ZONE_ATTRIBUT"];
        }          
        
        $return .= $controller->oForm->createComboFromList($controller->multi . "ZONE_ATTRIBUT", t("MODE_OUVERTURE"), $aModeAff, $ZONE_ATTRIBUT, true, $controller->readO);
        
        $sMultiName = $controller->multi .'ADDTOGGLE';
        $return .= $controller->oForm->createMultiHmvc(
                $sMultiName, 
                t('TOGGLE_FORM'), 
                array(
                    'path' => __FILE__,
                    'class' => __CLASS__,
                    'method' => 'addToggleForm'
                 ), 
                Backoffice_Form_Helper::getPageZoneMultiValues($controller, 'TOGGLE'), 
                $sMultiName, $controller->readO, '12', true, true, $sMultiName
            );
        
        return $return;
    }  
    
    public static function addToggleForm ($oForm, $values, $readO, $multi)
    {
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_TITRE", t ( 'TITLE' ), 100, "", true, $values["PAGE_ZONE_MULTI_TITRE"], $readO, 100);
        $return .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_TITRE2", t ( 'SOUS_TITRE' ), 255, "", true, $values["PAGE_ZONE_MULTI_TITRE2"], $readO, 100);
        $return .= $oForm->createMedia($multi."MEDIA_ID", t ('VISUEL'), false, "image", "", $values['MEDIA_ID'], $readO, true, false, '16_9');
        $return .= $oForm->createInput ($multi . "PAGE_ZONE_MULTI_MODE", t ( 'NB_TRANCHE' ), 2, "reel", true, $values["PAGE_ZONE_MULTI_MODE"], $readO, 5);   
        
        return $return;
    }
    
    public static function save()  
    {
        Pelican_Db::$values["ZONE_LANGUETTE"] = 2;
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();       
        Backoffice_Form_Helper::savePageZoneMultiValues('ADDTOGGLE', 'TOGGLE');   
        Pelican_Cache::clean("Frontend/Citroen/ZoneMulti");
    }  
}
?>