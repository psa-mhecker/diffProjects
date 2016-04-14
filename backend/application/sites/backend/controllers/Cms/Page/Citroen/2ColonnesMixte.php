<?php

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';

class Cms_Page_Citroen_2ColonnesMixte extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        //Vérification nb de colonne
        $return = $controller->oForm->createJS("

            var image = document.getElementById('div".$controller->multi."MEDIA_ID');
            var image_form_widget = document.getElementById('".$controller->multi."MEDIA_ID');
            var video = document.getElementById('div".$controller->multi."MEDIA_ID2');
            var galleri = document.getElementById('".$controller->multi."ADDVISUELFORM0_multi_display');
            var nogall = true;

            if(image.innerHTML == '' && video.innerHTML != ''){
                alert('".t('CHOIX_IMG', 'js')."');
                return false;
            }

            var vignette_galerie_form_widget = document.getElementById('".$controller->multi."MEDIA_ID6');


            var iVisuel = document.getElementById('count_".$controller->multi."ADDVISUELFORM');

            var iGallerie = document.getElementById('count_".$controller->multi."GALLERYFORM');

        if(((typeof vignette_galerie_form_widget != 'undefined') && vignette_galerie_form_widget.value.length)  && !parseInt(iVisuel.value)){
            alert('".t('VIGNETTE_SANS_GALERIE', 'js')."');
            return false;
        }

        if(iGallerie.value != '-1' || iVisuel.value != '-1'){

                var x = 0;

                for(x = -1; x < iVisuel.value; x++)
                {
                var displayVisuel = document.getElementById('".$controller->multi."ADDVISUELFORM'+ (x + 1) +'_multi_display');

                    if(displayVisuel != null){
                       nogall = false;
                        if(displayVisuel.value != 0)
                        {
                           var visuel = document.getElementById('div".$controller->multi."ADDVISUELFORM'+ (x + 1) +'_MEDIA_ID');
                           if(visuel == null)
                           {
                           alert('".t('CHOIX_VISUEL_OU_GALLERIE', 'js')."');
                           return false;
                           }
                        }
                    }

              }


            for(x = -1; x < iGallerie.value; x++)
                {
                var displayGallery = document.getElementById('".$controller->multi."GALLERYFORM'+ (x + 1) +'_multi_display');

                if(displayGallery != null){
                    if(displayGallery.value != 0)
                    {
                       var gallerie = document.getElementById('div".$controller->multi."GALLERYFORM'+ (x + 1) +'_MEDIA_ID');
                       if(gallerie == null)
                       {
                       alert('".t('CHOIX_VISUEL_OU_GALLERIE', 'js')."');
                       return false;
                       }
                    }
                }
              }


            }
             else
              {
                nogall = true;
              }

              if(image.innerHTML == '' && video.innerHTML == '' && nogall){
                alert('".t('CHOIX_MEDIA', 'js')."');
                return false;
            }


        ");
        $return .= Backoffice_Form_Helper::getForm(__CLASS__, $controller);

        return $return;
    }

    /*Enregistrement complémentaires multi
     */
    public static function save()
    {
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
        if (Pelican_Db::$values["form_action"] != Pelican_Db::DATABASE_DELETE) {
            Backoffice_Form_Helper::saveCta();
            Backoffice_Form_Helper::savePushGallery();
            Backoffice_Form_Helper::savePageZoneMultiValues('ADDVISUELFORM', 'VISUELFORM');
            Pelican_Cache::clean("Frontend/Citroen/ZoneMulti");
        }
    }
}
