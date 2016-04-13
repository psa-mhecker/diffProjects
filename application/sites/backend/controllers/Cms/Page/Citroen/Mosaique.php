<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Module.php');
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_Mosaique extends Cms_Page_Citroen  
{  
    public static function render(Pelican_Controller $controller)  
    {  
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE", t ( 'TITRE' ), 150, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 50);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE2", t ( 'SOUS_TITRE' ), 255, "", false, $controller->zoneValues["ZONE_TITRE2"], $controller->readO, 50);
       // $return .= $controller->oForm->createTextArea($controller->multi . "ZONE_TEXTE", t('CHAPEAU'), false, $controller->zoneValues["ZONE_TEXTE"], "", $controller->readO, 2, 100, false, "", false);
        $return .= $controller->oForm->createEditor($controller->multi . "ZONE_TEXTE", t('CHAPEAU'), false, $controller->zoneValues["ZONE_TEXTE"], $controller->readO, true, "", 500, 150);
        $sMultiName = $controller->multi .'ADD_MOSAIQUE';
        $return .= $controller->oForm->createMultiHmvc(
                $sMultiName, 
                t('VISUEL_MOSAIQUE_FORM'), 
                array(
                    'path' => __FILE__,
                    'class' => __CLASS__,
                    'method' => 'addVisuelMosaique'
                 ), 
                Backoffice_Form_Helper::getPageZoneMultiValues($controller, 'MOSAIQUE'), 
                $sMultiName, $controller->readO, '', true, true, $sMultiName
            );

        $return .= $controller->oForm->createJS("
            // Check nombre de toggle (bloc mosaique)
            //var multicount = jQuery('#".$controller->multi."ADD_MOSAIQUE_td').length;
            var multicount = jQuery('.".$controller->multi."ADD_MOSAIQUE_subForm').length-1;
            if( multicount < 4 || multicount > 16 ){
                alert('".t('BO_MOSAIQUE_MIN_4_MAX_16', 'js')."');
                return false;
            }
        ");

        $return .= Backoffice_Form_Helper::getMentionsLegales($controller, false, 'cinemascope');
        $return .= Backoffice_Form_Helper::getCta($controller, 3);
        $return .= Backoffice_Form_Helper::getLanguette($controller);
        
        return $return;   
    }  
    
    public static function addVisuelMosaique($oForm, $values, $readO, $multi)
    {
        $return .= $oForm->createTextArea($multi . "PAGE_ZONE_MULTI_TEXT", t('TEXTE_INITIAL'), true, $values["PAGE_ZONE_MULTI_TEXT"], 150, $readO, 5, 100, false, "", true);
        $return .= $oForm->createEditor($multi . "PAGE_ZONE_MULTI_TEXT2", t('TEXTE_OUVERT'), false, $values["PAGE_ZONE_MULTI_TEXT2"], $readO, true, "", 500, 200);

        return $return;
    }
    
    public static function save()  
    {  
        Backoffice_Form_Helper::saveFormAffichage();
		parent::save();
        Backoffice_Form_Helper::savePageZoneMultiValues('ADD_MOSAIQUE', 'MOSAIQUE'); 
        Backoffice_Form_Helper::saveCta();              
        Pelican_Cache::clean("Frontend/Citroen/ZoneMulti");
    }  
} 
?>
