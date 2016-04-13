<?php

include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');

class Cms_Page_Citroen_2ColonneSansVisuel extends Cms_Page_Citroen  
{  
  
    public static function render(Pelican_Controller $controller)  
    {  
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE", t ( 'TITRE' ), 255, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 50);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE2", t ( 'SOUS_TITRE' ), 255, "", false, $controller->zoneValues["ZONE_TITRE2"], $controller->readO, 50);
        $return .= $controller->oForm->createEditor($controller->multi . "ZONE_TEXTE", t('CHAPEAU'), false, $controller->zoneValues["ZONE_TEXTE"], $controller->readO, true, "", 500, 150);
	
        // Premier Colonne
        $return .= $controller->oForm->createLabel(t('CONTENT_1'),'');
        $return .= $controller->oForm->createInput ($controller->multi . "ZONE_TITRE3", t ( 'TITRE_COLUMN' ), 255, "", false, $controller->zoneValues["ZONE_TITRE3"], $controller->readO, 50);
        $return .= $controller->oForm->createEditor($controller->multi . "ZONE_TEXTE2", t('Content'), true, $controller->zoneValues["ZONE_TEXTE2"], $controller->readO, true, "", 500, 150);  
        $return .= $controller->oForm->createInput ($controller->multi . "ZONE_TITRE8", t ( 'LIBELLE' ), 255, "", false, $controller->zoneValues["ZONE_TITRE8"], $readO, 100);
        $return .= $controller->oForm->createInput ($controller->multi . "ZONE_TEXTE3", t ( 'LINK' ), 255, "internallink", false, $controller->zoneValues["ZONE_TEXTE3"], $controller->readO, 100);
        $return .= $controller->oForm->createInput ($controller->multi . "ZONE_TITRE11", t ( 'LINK_MOBILE' ), 255, "internallink", false, $controller->zoneValues["ZONE_TITRE11"], $controller->readO, 100);
        
        $return .= $controller->oForm->createRadioFromList($controller->multi . 'ZONE_TITRE16', t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $controller->zoneValues['ZONE_TITRE16'], true, $controller->readO);
                
        // Deuxieme Colonne
        $return .= $controller->oForm->createLabel(t('CONTENT_2'),'');
        $return .= $controller->oForm->createInput ($controller->multi . "ZONE_TITRE4", t ( 'TITRE_COLUMN' ), 255, "", false, $controller->zoneValues["ZONE_TITRE4"], $controller->readO, 50);
        $return .= $controller->oForm->createEditor($controller->multi . "ZONE_TEXTE5", t('Content'), true, $controller->zoneValues["ZONE_TEXTE5"], $controller->readO, true, "", 500, 150);  
        $return .= $controller->oForm->createInput ($controller->multi . "ZONE_TITRE9", t ( 'LIBELLE' ), 255, "", false, $controller->zoneValues["ZONE_TITRE9"], $readO, 100);
        $return .= $controller->oForm->createInput ($controller->multi . "ZONE_TEXTE6", t ( 'LINK' ), 255, "internallink", false, $controller->zoneValues["ZONE_TEXTE6"], $controller->readO, 100);
        $return .= $controller->oForm->createInput ($controller->multi . "ZONE_TITRE12", t ( 'LINK_MOBILE' ), 255, "internallink", false, $controller->zoneValues["ZONE_TITRE12"], $controller->readO, 100);
        
        
        $return .= $controller->oForm->createRadioFromList($controller->multi . 'ZONE_TITRE18', t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $controller->zoneValues['ZONE_TITRE18'], true, $controller->readO);
                
        $return .= Backoffice_Form_Helper::getMentionsLegales($controller, false, 'cinemascope');        
        $return .= Backoffice_Form_Helper::getPushMediaCommun($controller);
        $return .= Backoffice_Form_Helper::getCta($controller, 3);        
        $return .= Backoffice_Form_Helper::getLanguette($controller);
        
        return $return;   
    }  
      
    public static function save()  
    {  
        Backoffice_Form_Helper::saveFormAffichage();
		parent::save();  
        Backoffice_Form_Helper::saveCta();
        Backoffice_Form_Helper::savePushGallery(); 
         
        Pelican_Cache::clean("Frontend/Citroen/ZoneMulti");
    }  
}
?>
