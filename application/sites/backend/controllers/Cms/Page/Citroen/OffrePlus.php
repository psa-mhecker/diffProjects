<?php
include_once(Pelican::$config['CONTROLLERS_ROOT']."/Cms/Page/Module.php");
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_OffrePlus extends Cms_Page_Citroen 
{  
  
    public static function render(Pelican_Controller $controller)  
    {  
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        $return .= $controller->oForm->createInput ($controller->multi . "ZONE_TITRE3", t ( 'TITRE' ), 255, "", false, $controller->zoneValues["ZONE_TITRE3"], $controller->readO, 100);
        $return .= $controller->oForm->createInput ($controller->multi . "ZONE_TITRE4", t ( 'SOUS_TITRE' ), 255, "", false, $controller->zoneValues["ZONE_TITRE4"], $controller->readO, 100);
        $return .= $controller->oForm->createEditor($controller->multi . "ZONE_TEXTE", t('CHAPEAU'), false, $controller->zoneValues["ZONE_TEXTE"],  $controller->readO, true, "", 500, 150);
        $return .= $controller->oForm->createJS(" 
            
          var i = 0;
          var vide = 0;
          var plein = 0;
          var avant = 0;
          
          for(i=0; i < 13; i++)
          {
            if(document.getElementById('".$controller->multi."SLIDEOFFREADDFORM' + i + '_PAGE_ZONE_MULTI_TITRE') && document.getElementById('".$controller->multi."SLIDEOFFREADDFORM'+ i +'_multi_display').value == 1 )
            {
                if(document.getElementById('".$controller->multi."SLIDEOFFREADDFORM' + i + '_PAGE_ZONE_MULTI_TITRE').value == '')
                {
                    vide = 1; 
                    avant = i;
                    if(plein == 1)
                    {
                      alert('".t('ALERT_MSG_CT', 'js')."');
                     fwFocus(eval('".$controller->multi."SLIDEOFFREADDFORM'+ avant +'_PAGE_ZONE_MULTI_TITRE'));
                     return false;
                    }
                }
                else
                {
                    plein = 1;
                    if(vide == 1)
                    {
                     
                    alert('".t('ALERT_MSG_CT', 'js')."');
                    fwFocus(eval('".$controller->multi."SLIDEOFFREADDFORM'+ avant +'_PAGE_ZONE_MULTI_TITRE'));
                    return false;
                    }
                }
            }
          }
        ");
        
        $return .= $controller->oForm->createMultiHmvc($controller->multi."SLIDEOFFREADDFORM", t('OFFRE_MORE'), array(
	 "path" => __FILE__,
         "class" => __CLASS__,
         "method" => "slideOffreAddForm"
        ), Backoffice_Form_Helper::getPageZoneMultiValues($controller, 'SLIDEOFFREADDFORM'), $controller->multi . "SLIDEOFFREADDFORM", $controller->readO, 12, true, true, $controller->multi . "SLIDEOFFREADDFORM");
        
        $return .= Backoffice_Form_Helper::getMentionsLegales($controller, false, 'cinemascope');
        $return .= Backoffice_Form_Helper::getCta($controller, 3);
        
        return $return; 
    }  
      
    public static function slideOffreAddForm($oForm, $values, $readO, $multi)
    {   
       $offre .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_TITRE", t('TITRE'), 255, "", false, $values["PAGE_ZONE_MULTI_TITRE"], $readO, 50);
       $offre .= $oForm->createMedia($multi . "MEDIA_ID", t ('IMAGE_WEB'), true, "image", "", $values["MEDIA_ID"], $readO, true, false, "offre");
       $offre .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_URL", t('URL_WEB'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL"], $readO, 50);
       $offre .= $oForm->createInput($multi . "PAGE_ZONE_MULTI_URL2", t('URL_MOBILE'), 255, "internallink", false, $values["PAGE_ZONE_MULTI_URL2"], $readO, 50);
       $offre .= $oForm->createRadioFromList($multi . 'PAGE_ZONE_MULTI_ATTRIBUT', t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $values['PAGE_ZONE_MULTI_ATTRIBUT'], true, $readO);
       
       return $offre;
    }
    
    public static function save()  
    {  
        Backoffice_Form_Helper::saveFormAffichage();
		readMulti('SLIDEOFFREADDFORM','SLIDEOFFREADDFORM');
        $aSiteAddMulti = Pelican_Db::$values["SLIDEOFFREADDFORM"];

        parent::save();
        Backoffice_Form_Helper::saveCta();
        Backoffice_Form_Helper::savePageZoneMultiValues('SLIDEOFFREADDFORM', 'SLIDEOFFREADDFORM'); 
        Pelican_Cache::clean("Frontend/Citroen/ZoneMulti");
    }  
}  
?>
