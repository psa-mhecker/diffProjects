<?php

include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Module.php');
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');


class Cms_Page_Citroen_2ColonneMixteEnrichi extends Cms_Page_Citroen
{

    public static function render(Pelican_Controller $controller)
    {
        //Vérification qu'il y a soit un média, soit un code HTML, soit un flash avec son alternative.
        $return = $controller->oForm->createJS("
            var image = document.getElementById('div".$controller->multi."MEDIA_ID');
            var video = document.getElementById('div".$controller->multi."MEDIA_ID2');
            
            if(image.innerHTML == '' && video.innerHTML != ''){
                alert('".t('MANQUE_IMG', 'js')."');
                return false;
            }
            
            var iVisuel = document.getElementById('count_" . $controller->multi . "ADD_VISUEL');
            var iGallerie = document.getElementById('count_" . $controller->multi . "GALLERYFORM');

            if(iGallerie.value != '-1' || iVisuel.value != '-1'){

                var x = 0;

                for(x = -1; x < iVisuel.value; x++)
                {
                    var displayVisuel = document.getElementById('" . $controller->multi . "ADD_VISUEL'+ (x + 1) +'_multi_display');

                    if(displayVisuel != null){
                        if(displayVisuel.value != 0)
                        {
                           var visuel = document.getElementById('div" . $controller->multi . "ADD_VISUEL'+ (x + 1) +'_MEDIA_ID');
                           if(visuel == null)
                           {
                                alert('" . t('CHOIX_VISUEL_OU_GALLERIE', 'js') . "');
                                return false;
                           }
                        }
                    }
                }

                for(x = -1; x < iGallerie.value; x++)
                {
                    var displayGallery = document.getElementById('" . $controller->multi . "GALLERYFORM'+ (x + 1) +'_multi_display');

                    if(displayGallery != null){
                        if(displayGallery.value != 0)
                        {
                            var gallerie = document.getElementById('div" . $controller->multi . "GALLERYFORM'+ (x + 1) +'_MEDIA_ID');
                            if(gallerie == null)
                            {
                                 alert('" . t('CHOIX_VISUEL_OU_GALLERIE', 'js') . "');
                                 return false;
                            }
                        }
                    }
                }
            }          
        ");
        
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi . "ZONE_PARAMETERS", t('PRODUIT_FINANCIER'), array(1 => ""), $controller->zoneValues['ZONE_PARAMETERS'], false, $controller->readO);
        $aSharer = array(1 => t('FACEBOOK_SEND'), 2 => t('MAIL_VIA_SHARETHIS'));
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi . "ZONE_TITRE4", t('SHARER'), $aSharer, explode('|', $controller->zoneValues['ZONE_TITRE4']), false, $controller->readO);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE", t('TITRE'), 255, "", false, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 75);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE2", t('SOUS_TITRE'), 255, "", false, $controller->zoneValues['ZONE_TITRE2'], $controller->readO, 75);
        $return .= $controller->oForm->createEditor($controller->multi . "ZONE_TEXTE", t('TEXTE'), false, $controller->zoneValues['ZONE_TEXTE'], $controller->readO, true, "", 500, 150);
        $return .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID", t('IMAGE'), false, 'image', '', $controller->zoneValues['MEDIA_ID'], $controller->ReadO, true, false, '16_9');
        $return .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID2", t('VIDEO'), false, 'video', '', $controller->zoneValues['MEDIA_ID2'], $controller->ReadO, true, false);
        $return .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID6", t ( 'VIGNETTE_GALLERY' ), false, "image", "", $controller->zoneValues["MEDIA_ID6"], $controller->readO, true, false, "16_9");
        
        $sMultiName = $controller->multi . 'ADD_VISUEL';
        $return .= $controller->oForm->createMultiHmvc(
                $sMultiName, t('VISUEL_TEXTE_FORM'), array(
                'path' => __FILE__,
                'class' => __CLASS__,
                'method' => 'addVisuelForm'
                ), Backoffice_Form_Helper::getPageZoneMultiValues($controller, 'VISUEL'), $sMultiName, $controller->readO, '', true, true, $sMultiName
        );
        $return .= $controller->oForm->showSeparator("formsep", false);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE3", t('SOUS_TITRE_POINT_FORT'), 255, "", false, $controller->zoneValues['ZONE_TITRE3'], $controller->readO, 75);
        $return .= $controller->oForm->createEditor($controller->multi . "ZONE_TEXTE2", t('POINT_FORT'), false, $controller->zoneValues['ZONE_TEXTE2'], $controller->readO, true, "", 500, 150);
        $return .= $controller->oForm->createTextArea($controller->multi . "ZONE_TEXTE3", t('VERBATIM'), false, $controller->zoneValues['ZONE_TEXTE3'], "", $controller->readO, 5, 75, false, "", false);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TEXTE6", t('TEXTE'), 255, "", false, $controller->zoneValues["ZONE_TEXTE6"], $controller->readO, 50);

        //$return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE4", t('SOUS_TITRE_VERBATIM'), 255, "", false, $controller->zoneValues['ZONE_TITRE4'], $controller->readO, 50);
        $return .= Backoffice_Form_Helper::getMentionsLegales($controller, false, 'cinemascope');
        $return .= Backoffice_Form_Helper::getPushMediaCommun($controller);
        $return .= Backoffice_Form_Helper::getCta($controller, 3);
        return $return;
    }

    public static function addVisuelForm($oForm, $aValues, $mReadO, $sMultiLabel)
    {
            $sMultiForm .= $oForm->createMedia($sMultiLabel . "MEDIA_ID", t('IMAGE'), false, "image", "", $aValues['MEDIA_ID'], $mReadO, true, false);

            return $sMultiForm;
    }

    public static function save()
    {
        Backoffice_Form_Helper::saveFormAffichage();
            if (Pelican_Db::$values['ZONE_TITRE4']) {
                    Pelican_Db::$values['ZONE_TITRE4'] = implode('|', Pelican_Db::$values['ZONE_TITRE4']);
            }
            parent::save();
            Backoffice_Form_Helper::savePageZoneMultiValues('ADD_VISUEL', 'VISUEL');
            Backoffice_Form_Helper::saveCta();
            Backoffice_Form_Helper::savePushGallery();
            //Pelican_Cache::clean("Frontend/Citroen/ZoneMulti");
            Pelican_Cache::clean("Frontend/Citroen/OutilAideChoixFinancement/ProduitFinancier");
    }

}
