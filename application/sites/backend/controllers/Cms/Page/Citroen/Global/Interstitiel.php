<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_Global_Interstitiel extends Cms_Page_Citroen
{
	 public static function render(Pelican_Controller $controller)
    {
		$oConnection = Pelican_Db::getInstance();
		$readO = $controller->readO;

		$return = $controller->oForm->createJS("
			var isWeb = document.getElementsByName('".$controller->multi."ZONE_WEB');
		
            var image = document.getElementById('div".$controller->multi."MEDIA_ID');
            var video = document.getElementById('div".$controller->multi."MEDIA_ID6');
            var flash = document.getElementById('div".$controller->multi."MEDIA_ID3');
            var html = document.getElementsByName('".$controller->multi."ZONE_TEXTE2');
            var altFlash = document.getElementsByName('".$controller->multi."ZONE_TEXTE');
            var altFlashImage = document.getElementById('div".$controller->multi."MEDIA_ID5');
            var imageTimer =  document.getElementById('".$controller->multi."ZONE_TITRE2');
           if(isWeb[0].checked){
	           if(image.innerHTML == '' && flash.innerHTML == '' && html[0].value == '' && video.innerHTML == '' ){
	                alert('".t('CHOIX_IMG_FLASH_HTML', 'js')." ".t('Interstitiel', 'js')."');
	                 $('#FIELD_BLANKS').val(1);
	            }
	            else
	            {
	               if((flash.innerHTML != '' && ( altFlash[0].value == '' || altFlashImage.innerHTML == '') )){
	                    alert('".t('CHOIX_ALTER_FLASH', 'js')."');
	                    $('#FIELD_BLANKS').val(1);
	                }
                     if(image.innerHTML != '' && imageTimer.value == '')
                     {
                        alert('".t('Int_Duree_vide', 'js')."');
                         $('#FIELD_BLANKS').val(1);
                     }

	            }
        }

        ");
		$return .= Backoffice_Form_Helper::getFormAffichage($controller, true, false);
		$return .= $controller->oForm->createInput($controller->multi ."ZONE_TITRE", t('LIBELLE_BOUTON'), 255, "", true, $controller->zoneValues['ZONE_TITRE'], $readO, 40);
        $return .= $controller->oForm->createInput($controller->multi ."ZONE_URL", t('URL'), 255, "internallink", false, $controller->zoneValues['ZONE_URL'], $readO, 75);
        $return .= $controller->oForm->createRadioFromList($controller->multi ."ZONE_TOOL", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $controller->zoneValues['ZONE_TOOL'], false, $readO);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('AFFICHAGE').' (?) ', 255, "", false, $controller->zoneValues['ZONE_TITRE2'], $readO, 2, false, "", "", "", false, t('Int_Duree'));
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE3", t('duree_cookie'), 255, "", true, $controller->zoneValues['ZONE_TITRE3'], $readO, 2);
        //IMG
        $return .= $controller->oForm->showSeparator("formsep", false);
        $return .= $controller->oForm->createMedia($controller->multi  . 'MEDIA_ID', t ('BackgroundImage'), false, 'image', '', $controller->zoneValues['MEDIA_ID'], $readO, true, false, "INTERSTITIEL");
		//video
		$return .= $controller->oForm->showSeparator("formsep", false);
        $return .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID6", t('VIDEO'), false, "video", "", $controller->zoneValues['MEDIA_ID6'], $readO);
        //SWF
        $return .= $controller->oForm->showSeparator("formsep", false);
        $return .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID3", t('FICHIER_SWF'), false, "flash", "", $controller->zoneValues['MEDIA_ID3'], $readO);
        $return .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID4", t('FICHIER_XML'), false, "file", "", $controller->zoneValues['MEDIA_ID4'], $readO);
        $return .= $controller->oForm->createLabel("", t('VARIABLE_XML') . Pelican::$config['VARIABLE_XML_SLIDESHOW']);
        $return .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID5", t('ALTERNATIVE_IMAGE'), false, "image", "", $controller->zoneValues['MEDIA_ID5'], $readO);
        $return .= $controller->oForm->createTextArea($controller->multi . "ZONE_TEXTE", t('ALTERNATIVE_TEXT'), false, $controller->zoneValues["ZONE_TEXTE"], 1000, $readO, 2, 100, false, "", false);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_URL2", t('ALTERNATIVE_URL'), 255, "internallink", false, $controller->zoneValues["ZONE_URL2"], $readO, 50, false);

        //HTML5
        $return .= $controller->oForm->showSeparator("formsep", false);
        $return .= $controller->oForm->createTextArea($controller->multi . "ZONE_TEXTE2", t('CODE_HTML'), false, $controller->zoneValues["ZONE_TEXTE2"], "", $readO, 4, 100, false, "", false);

        return $return; 
	}

public static function save(Pelican_Controller $controller)
    {
    	Backoffice_Form_Helper::saveFormAffichage();
    	parent::save();
    }

}