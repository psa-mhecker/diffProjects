<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Module.php');
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_AccountManagers extends Cms_Page_Citroen 
{  
    public static function render(Pelican_Controller $controller)  
    {  
        $return = $controller->oForm->createJS("
            var manager = document.getElementById('".$controller->multi."ADD_MANAGER0_multi_display');
            if(manager == null || manager.value ==0){
                alert('".t('MIN_ONE_MANAGER', 'js')." ".   $controller->zoneValues['ZONE_TEMPLATE_LABEL'] ."');
				return false;
            } 
        ");
        
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE", t ( 'TITRE' ), 255, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 50);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE2", t ( 'SOUS_TITRE' ), 255, "", false, $controller->zoneValues["ZONE_TITRE2"], $controller->readO, 50);        
        $return .= $controller->oForm->createEditor($controller->multi . "ZONE_TEXTE", t('CHAPEAU'), false, $controller->zoneValues["ZONE_TEXTE"], $controller->readO, true, "", 500, 150);

        
        $aListe = array('1'=>t('MAIL'),'2'=>t('MAIL_CITROEN'));        
        $return .= $controller->oForm->createComboFromList($controller->multi . "ZONE_ATTRIBUT", t("MODE_EMAIL"), $aListe, $controller->zoneValues["ZONE_ATTRIBUT"], true, $controller->readO);
        
        $sMultiName = $controller->multi .'ADD_MANAGER';
        $return .= $controller->oForm->createMultiHmvc(
                $sMultiName, 
                t('MANAGER'), 
                array(
                    'path' => __FILE__,
                    'class' => __CLASS__,
                    'method' => 'addManagerForm'
                 ), 
                Backoffice_Form_Helper::getPageZoneMultiValues($controller, 'MANAGER'), 
                $sMultiName, $controller->readO, '', true, true, $sMultiName
            );
  
        return $return;   
    } 
    
    public static function addManagerForm ($oForm, $values, $readO, $multi) 
    {
        $aListe = array('Mademoiselle'=>t ( 'MS' ),'Madame'=>t ( 'MRS' ),'Monsieur'=>t ( 'MR' ));        
        $return .= $oForm->createComboFromList($multi . "PAGE_ZONE_MULTI_LABEL7", t("CIVILITE"), $aListe, $values["PAGE_ZONE_MULTI_LABEL7"], true, $readO);
        $return .= $oForm->createInput ($multi . "PAGE_ZONE_MULTI_LABEL", t ( 'PRENOM' ), 255, "", true, $values["PAGE_ZONE_MULTI_LABEL"], $readO, 100);
        $return .= $oForm->createInput ($multi . "PAGE_ZONE_MULTI_LABEL2", t ( 'NOM' ), 255, "", true, $values["PAGE_ZONE_MULTI_LABEL2"], $readO, 100);
        $return .= $oForm->createMedia($multi."MEDIA_ID", t ('VISUEL'), true, "image", "", $values['MEDIA_ID'], $readO, true, false);
        $return .= $oForm->createInput ($multi . "PAGE_ZONE_MULTI_LABEL3", t ( 'TITLE' ), 255, "", true, $values["PAGE_ZONE_MULTI_LABEL3"], $readO, 100);
        //$return .= $oForm->createInput ($multi . "PAGE_ZONE_MULTI_LABEL4", t('REGION'), 500, "", false, $values["PAGE_ZONE_MULTI_LABEL4"], $readO, 100);        
        $return .= $oForm->createInput ($multi . "PAGE_ZONE_MULTI_TEXT", t('REGION'), 500, "", false, $values["PAGE_ZONE_MULTI_TEXT"], $readO, 100);        
        $return .= $oForm->createInput ($multi . "PAGE_ZONE_MULTI_LABEL5", t ( 'EMAIL' ), 255, "mail", false, $values["PAGE_ZONE_MULTI_LABEL5"], $readO, 100);
        $return .= $oForm->createInput ($multi . "PAGE_ZONE_MULTI_LABEL6", t ( 'TELEPHONE' ), 100, "", false, $values["PAGE_ZONE_MULTI_LABEL6"], $readO, 100);

        return $return;
    }
    
    public static function save()  
    {          
        Backoffice_Form_Helper::saveFormAffichage();
		parent::save();
        Backoffice_Form_Helper::savePageZoneMultiValues('ADD_MANAGER', 'MANAGER');        
        Pelican_Cache::clean("Frontend/Citroen/ZoneMulti");
    }  
}  
?>
