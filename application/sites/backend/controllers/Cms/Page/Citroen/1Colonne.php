<?php

include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');

class Cms_Page_Citroen_1Colonne extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        //Vérification qu'il y a soit un média, soit un code HTML, soit un flash avec son alternative.
        $return = $controller->oForm->createJS("
            var image = document.getElementById('div".$controller->multi."MEDIA_ID');
            var flash = document.getElementById('div".$controller->multi."MEDIA_ID2');
            var html = document.getElementsByName('".$controller->multi."ZONE_TEXTE2');
            var altFlash = document.getElementsByName('".$controller->multi."ZONE_TEXTE5');
			var MediaVideo = document.getElementById('div".$controller->multi."MEDIA_ID11');
			
           if(image.innerHTML == '' && flash.innerHTML == '' && html[0].value == '' && MediaVideo.innerHTML ==''){
                alert('".t('CHOIX_IMG_FLASH_HTML', 'js')."');
				$('#FIELD_BLANKS').val(1);
               
            }
            else
            {
                 if((flash.innerHTML != '' && altFlash[0].value == '' )){
                    alert('".t('CHOIX_ALTER_FLASH', 'js')."');
					$('#FIELD_BLANKS').val(1);
                    
                }
            }

        ");
        
        $return .= Backoffice_Form_Helper::getForm($controller->zoneValues["ZONE_BO_PATH"], $controller);
        return $return;
    }

    /*Enregistrement complémentaires multi
     */
    public static function save()
    {
        if(isset(Pelican_Db::$values["MEDIA_ID5"])){

            if(preg_match('/[a-z]/', Pelican_Db::$values["MEDIA_ID5"])){
                Pelican_Db::$values["ZONE_TITRE20"] = Pelican_Db::$values["MEDIA_ID5"];
                Pelican_Db::$values["MEDIA_ID5"] = "NULL";
            }
        }
        if(isset(Pelican_Db::$values["MEDIA_ID9"])){
            if(preg_match('/[a-z]/', Pelican_Db::$values["MEDIA_ID9"])){
                Pelican_Db::$values["ZONE_TITRE21"] = Pelican_Db::$values["MEDIA_ID9"];
                Pelican_Db::$values["MEDIA_ID9"] = "NULL";
            }
        }
        Backoffice_Form_Helper::saveFormAffichage();
		if (Pelican_Db::$values["form_action"] != Pelican_Db::DATABASE_DELETE){
            Backoffice_Form_Helper::saveCta();
            Backoffice_Form_Helper::savePushGallery();
		}
        parent::save();

    }
}