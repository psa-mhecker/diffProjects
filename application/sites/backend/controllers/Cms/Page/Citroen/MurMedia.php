<?php
include_once(Pelican::$config['CONTROLLERS_ROOT']."/Cms/Page/Module.php");
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_MurMedia extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
       $oConnection = Pelican_Db::getInstance();

       $return .= '<script type="text/javascript">
        var mySelectId;
        var myMulti;
        function add_MurMedia(selectId, Multi){
            mySelectId = selectId;
            myMulti = Multi;
            var selectElmt = document.getElementById(selectId);
            var value = selectElmt.options[selectElmt.selectedIndex].value;

            $("." + Multi + "media_hide").hide();
            $("." + Multi + "media_"+value).show();
        }

    </script>';

        $warning = t("VOUS_DEVEZ_AJOUTER_UNE_GALLERIE", 'js2');
        $return .= $controller->oForm->createJS('
                if($("#count_' . $controller->multi . 'GALLERYFORM").val() < 0){
                        alert(\''.$warning.'\');
                        return false;
                }
        ');

        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE", t('TITRE'), 255, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 50);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE2", t('SOUS_TITRE'), "", "", false, $controller->zoneValues["ZONE_TITRE2"], $controller->readO, 50);
        $return .= Backoffice_Form_Helper::getFormGroupeReseauxSociaux($controller, "ZONE_TITRE3", "PUBLIC");
        $return .= $controller->oForm->createMultiHmvc(
                $controller->multi."GALLERYFORM",
                t('ADD_FORM_GALLERY'),
                array(
                    'path' => __FILE__,
                    'class' => __CLASS__,
                    'method' => 'galleryAddFormMedia'
                ),
                Backoffice_Form_Helper::getPageZoneMultiValues(
                        $controller,
                        'GALLERYFORM'
                        ),
                $controller->multi . "GALLERYFORM",
                $controller->readO,
                '',
                true,
                true,
                $controller->multi . "GALLERYFORM"
                );
				
		$return .= Backoffice_Form_Helper::getLanguette($controller);
		
        return $return;
    }

    public static function save()
    {
        $oConnection = Pelican_Db::getInstance();
		Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
		Backoffice_Form_Helper::savePageZoneMultiValues('GALLERYFORM', 'GALLERYFORM');
        Pelican_Cache::clean("Frontend/Citroen/ZoneMulti");
    }

    public static function galleryAddFormMedia($oForm, $values, $readO, $multi)
    {
       $medias .= $oForm->createComboFromList($multi . "PAGE_ZONE_MULTI_VALUE", t ( 'Type D\'affichage' ), array(
            1=>t ( '1_Visuel_Cinemascope' ),
            2=>t ( '2_Visuel_16/9' ),
            3=>t ( 'Visuel_Portrait_+_2 Visuels_16/9_Empilés' ),
            4=>t ( '2_Visuels_16/9_Empilés_+_1_Visuel_Portrait' ),
            5=>t ( '2_Visuels_Format_Carré' ),
            6=>t ( '2_Visuels_Formats_Portraits' ),
            7=>t ( '3_Visuel_Carrés' )
        ), $values["PAGE_ZONE_MULTI_VALUE"], true, $readO, 1, false, '', true, false, 'onchange="add_MurMedia(\'' . $multi . "PAGE_ZONE_MULTI_VALUE" . '\',\'' . $multi . '\')"');

            $form .= $oForm->createHidden($multi . "MEDIA_ID", $values["MEDIA_ID"]);
            $form .= $oForm->createHidden($multi . "MEDIA_ID2", $values["MEDIA_ID2"]);
            $form .= $oForm->createHidden($multi . "MEDIA_ID3", $values["MEDIA_ID3"]);
            $form .= $oForm->createHidden($multi . "MEDIA_ID4", $values["MEDIA_ID4"]);
            $form .= $oForm->createHidden($multi . "MEDIA_ID5", $values["MEDIA_ID5"]);
            $form .= $oForm->createHidden($multi . "MEDIA_ID6", $values["MEDIA_ID6"]);

       //ajout de la classe media_1 pour masquer tout les champs
       //ajout de la classe media_x pour afficher le champ voulu
       //cas 1
            $medias .= '<tr class="'.$multi.'media_vig_1 '.$multi.'media_1 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIGNETTE_VIDEO' ).' 1</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID_1", t ( 'VIGNETTE_VIDEO' ), false, "image", "", $values["MEDIA_ID"], $readO, true, true, 'cinemascope').'</td></tr>';
            $medias .= '<tr class="'.$multi.'media_vid_1 '.$multi.'media_1 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIDEO' ).' 1</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID2_1", t ( 'VIDEO' ), false, "video", "", $values["MEDIA_ID2"], $readO, true, true).'</td></tr>';

       //cas 2'.$multi.'
            $medias .= '<tr class="'.$multi.'media_vig_1 '.$multi.'media_2 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIGNETTE_VIDEO' ).' 1</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID_2", t ( 'VIGNETTE_VIDEO' ), false, "image", "", $values["MEDIA_ID"], $readO, true, true, '16_9').'</td></tr>';
            $medias .= '<tr class="'.$multi.'media_vid_1 '.$multi.'media_2 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIDEO' ).' 1</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID2_2", t ( 'VIDEO' ), false, "video", "", $values["MEDIA_ID2"], $readO, true, true).'</td></tr>';
            $medias .= '<tr class="'.$multi.'media_vig_2 '.$multi.'media_2 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIGNETTE_VIDEO' ).' 2</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID3_2", t ( 'VIGNETTE_VIDEO' ), false, "image", "", $values["MEDIA_ID3"], $readO, true, true,  '16_9').'</td></tr>';
            $medias .= '<tr class="'.$multi.'media_vid_2 '.$multi.'media_2 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIDEO' ).' 2</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID4_2", t ( 'VIDEO' ), false, "video", "", $values["MEDIA_ID4"], $readO, true, true).'</td></tr>';

       //cas 3'.$multi.'
            $medias .= '<tr class="'.$multi.'media_vig_1 '.$multi.'media_3 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIGNETTE_VIDEO' ).' 1</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID_3", t ( 'VIGNETTE_VIDEO' ), false, "image", "", $values["MEDIA_ID"], $readO, true, true, 'portrait').'</td></tr>';
            $medias .= '<tr class="'.$multi.'media_vid_1 '.$multi.'media_3 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIDEO' ).' 1</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID2_3", t ( 'VIDEO' ), false, "video", "", $values["MEDIA_ID2"], $readO, true, true).'</td></tr>';
            $medias .= '<tr class="'.$multi.'media_vig_2 '.$multi.'media_3 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIGNETTE_VIDEO' ).' 2</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID3_3", t ( 'VIGNETTE_VIDEO' ), false, "image", "", $values["MEDIA_ID3"], $readO, true, true,  '16_9').'</td></tr>';
            $medias .= '<tr class="'.$multi.'media_vid_2 '.$multi.'media_3 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIDEO' ).' 2</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID4_3", t ( 'VIDEO' ), false, "video", "", $values["MEDIA_ID4"], $readO, true, true).'</td></tr>';
            $medias .= '<tr class="'.$multi.'media_vig_3 '.$multi.'media_3 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIGNETTE_VIDEO' ).' 3</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID5_3", t ( 'VIGNETTE_VIDEO' ), false, "image", "", $values["MEDIA_ID5"], $readO, true, true,  '16_9').'</td></tr>';
            $medias .= '<tr class="'.$multi.'media_vid_3 '.$multi.'media_3 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIDEO' ).' 3</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID6_3", t ( 'VIDEO' ), false, "video", "", $values["MEDIA_ID6"], $readO, true, true).'</td></tr>';

       //cas 4'.$multi.'
            $medias .= '<tr class="'.$multi.'media_vig_1 '.$multi.'media_4 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIGNETTE_VIDEO' ).' 1</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID_4", t ( 'VIGNETTE_VIDEO' ), false, "image", "", $values["MEDIA_ID"], $readO, true, true, '16_9').'</td></tr>';
            $medias .= '<tr class="'.$multi.'media_vid_1 '.$multi.'media_4 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIDEO' ).' 1</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID2_4", t ( 'VIDEO' ), false, "video", "", $values["MEDIA_ID2"], $readO, true, true).'</td></tr>';
            $medias .= '<tr class="'.$multi.'media_vig_2 '.$multi.'media_4 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIGNETTE_VIDEO' ).' 2</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID3_4", t ( 'VIGNETTE_VIDEO' ), false, "image", "", $values["MEDIA_ID3"], $readO, true, true,  '16_9').'</td></tr>';
            $medias .= '<tr class="'.$multi.'media_vid_2 '.$multi.'media_4 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIDEO' ).' 2</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID4_4", t ( 'VIDEO' ), false, "video", "", $values["MEDIA_ID4"], $readO, true, true).'</td></tr>';
            $medias .= '<tr class="'.$multi.'media_vig_3 '.$multi.'media_4 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIGNETTE_VIDEO' ).' 3</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID5_4", t ( 'VIGNETTE_VIDEO' ), false, "image", "", $values["MEDIA_ID5"], $readO, true, true, 'portrait').'</td></tr>';
            $medias .= '<tr class="'.$multi.'media_vid_3 '.$multi.'media_4 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIDEO' ).' 3</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID6_4", t ( 'VIDEO' ), false, "video", "", $values["MEDIA_ID6"], $readO, true, true).'</td></tr>';

       //cas 5'.$multi.'
            $medias .= '<tr class="'.$multi.'media_vig_1 '.$multi.'media_5 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIGNETTE_VIDEO' ).' 1</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID_5", t ( 'VIGNETTE_VIDEO' ), false, "image", "", $values["MEDIA_ID"], $readO, true, true, 'carre').'</td></tr>';
            $medias .= '<tr class="'.$multi.'media_vid_1 '.$multi.'media_5 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIDEO' ).' 1</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID2_5", t ( 'VIDEO' ), false, "video", "", $values["MEDIA_ID2"], $readO, true, true).'</td></tr>';
            $medias .= '<tr class="'.$multi.'media_vig_2 '.$multi.'media_5 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIGNETTE_VIDEO' ).' 2</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID3_5", t ( 'VIGNETTE_VIDEO' ), false, "image", "", $values["MEDIA_ID3"], $readO, true, true,  'carre').'</td></tr>';
            $medias .= '<tr class="'.$multi.'media_vid_2 '.$multi.'media_5 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIDEO' ).' 2</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID4_5", t ( 'VIDEO' ), false, "video", "", $values["MEDIA_ID4"], $readO, true, true).'</td></tr>';

       //cas 6
            $medias .= '<tr class="'.$multi.'media_vig_1 '.$multi.'media_6 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIGNETTE_VIDEO' ).' 1</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID_6", t ( 'VIGNETTE_VIDEO' ), false, "image", "", $values["MEDIA_ID"], $readO, true, true, 'portrait').'</td></tr>';
            $medias .= '<tr class="'.$multi.'media_vid_1 '.$multi.'media_6 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIDEO' ).' 1</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID2_6", t ( 'VIDEO' ), false, "video", "", $values["MEDIA_ID2"], $readO, true, true).'</td></tr>';
            $medias .= '<tr class="'.$multi.'media_vig_2 '.$multi.'media_6 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIGNETTE_VIDEO' ).' 2</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID3_6", t ( 'VIGNETTE_VIDEO' ), false, "image", "", $values["MEDIA_ID3"], $readO, true, true, 'portrait').'</td></tr>';
            $medias .= '<tr class="'.$multi.'media_vid_2 '.$multi.'media_6 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIDEO' ).' 2</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID4_6", t ( 'VIDEO' ), false, "video", "", $values["MEDIA_ID4"], $readO, true, true).'</td></tr>';

       //cas 7
            $medias .= '<tr class="'.$multi.'media_vig_1 '.$multi.'media_7 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIGNETTE_VIDEO' ).' 1</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID_7", t ( 'VIGNETTE_VIDEO' ), false, "image", "", $values["MEDIA_ID"], $readO, true, true, 'carre').'</td></tr>';
            $medias .= '<tr class="'.$multi.'media_vid_1 '.$multi.'media_7 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIDEO' ).' 1</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID2_7", t ( 'VIDEO' ), false, "video", "", $values["MEDIA_ID2"], $readO, true, true).'</td></tr>';
            $medias .= '<tr class="'.$multi.'media_vig_2 '.$multi.'media_7 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIGNETTE_VIDEO' ).' 2</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID3_7", t ( 'VIGNETTE_VIDEO' ), false, "image", "", $values["MEDIA_ID3"], $readO, true, true,  'carre').'</td></tr>';
            $medias .= '<tr class="'.$multi.'media_vid_2 '.$multi.'media_7 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIDEO' ).' 2</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID4_7", t ( 'VIDEO' ), false, "video", "", $values["MEDIA_ID4"], $readO, true, true).'</td></tr>';
            $medias .= '<tr class="'.$multi.'media_vig_3 '.$multi.'media_7 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIGNETTE_VIDEO' ).' 3</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID5_7", t ( 'VIGNETTE_VIDEO' ), false, "image", "", $values["MEDIA_ID5"], $readO, true, true,  'carre').'</td></tr>';
            $medias .= '<tr class="'.$multi.'media_vid_3 '.$multi.'media_7 '.$multi.'media_hide"><td class="formlib">'.t ( 'VIDEO' ).' 3</td><td class="formval">'.$oForm->createMedia($multi . "MEDIA_ID6_7", t ( 'VIDEO' ), false, "video", "", $values["MEDIA_ID6"], $readO, true, true).'</td></tr>';


       $medias .= '
            <script type="text/javascript">
				add_MurMedia("'.$multi .'PAGE_ZONE_MULTI_VALUE", "'.$multi.'");
            </script>
			';

       $medias .= $oForm->createJS('

            var selectValue = $("#'.$multi.'PAGE_ZONE_MULTI_VALUE").val();

            var emptyVignette = false;
            //contrôle obligation vignette
            switch(selectValue)
            {
                //cas 1 seul vignette/video
                case "1" :
                    if($("#'.$multi.'MEDIA_ID_"+selectValue).val() == ""){
                        emptyVignette = true;
                    }
                break;
                //cas 2 vignette/video
                case "2" :
                case "5" :
                case "6" :
                    if($("#'.$multi.'MEDIA_ID_"+selectValue).val() == "" ||  $("#'.$multi.'MEDIA_ID3_"+selectValue).val() == ""){
                            emptyVignette = true;
                    }
                break;
                //cas 3 vignette/video
                default :
                    if($("#'.$multi.'MEDIA_ID_"+selectValue).val() == "" ||
                        $("#'.$multi.'MEDIA_ID3_"+selectValue).val() == ""
                            || $("#'.$multi.'MEDIA_ID5_"+selectValue).val() == ""){

                            emptyVignette = true;
                    }
                break;
            }

            if(emptyVignette == true){
                alert("'.t("ALL_VIGNETTE_GALLERY", 'js2').'");
                fwFocus("#'.$multi.'PAGE_ZONE_MULTI_VALUE");
                return false;
            }else{
                //rempli les champs hidden (valeur qui seront enregistré dans le multi)
                for (i=1; i<=selectValue; i++) {
                    switch(i)
                    {
                        //cas 1 seul vignette/video
                        case "1" :
                            $("#'.$multi.'MEDIA_ID").val($("#'.$multi.'MEDIA_ID_"+i).val());
                            $("#'.$multi.'MEDIA_ID2").val($("#'.$multi.'MEDIA_ID2_"+i).val());
                            break;
                        //cas 2 vignette/video
                        case "2" :
                        case "5" :
                        case "6" :
                            $("#'.$multi.'MEDIA_ID").val($("#'.$multi.'MEDIA_ID_"+i).val());
                            $("#'.$multi.'MEDIA_ID2").val($("#'.$multi.'MEDIA_ID2_"+i).val());
                            $("#'.$multi.'MEDIA_ID3").val($("#'.$multi.'MEDIA_ID3_"+i).val());
                            $("#'.$multi.'MEDIA_ID4").val($("#'.$multi.'MEDIA_ID4_"+i).val());
                            break;
                        //cas 3 vignette/video
                        default :
                            $("#'.$multi.'MEDIA_ID").val($("#'.$multi.'MEDIA_ID_"+i).val());
                            $("#'.$multi.'MEDIA_ID2").val($("#'.$multi.'MEDIA_ID2_"+i).val());
                            $("#'.$multi.'MEDIA_ID3").val($("#'.$multi.'MEDIA_ID3_"+i).val());
                            $("#'.$multi.'MEDIA_ID4").val($("#'.$multi.'MEDIA_ID4_"+i).val());
                            $("#'.$multi.'MEDIA_ID5").val($("#'.$multi.'MEDIA_ID5_"+i).val());
                            $("#'.$multi.'MEDIA_ID6").val($("#'.$multi.'MEDIA_ID6_"+i).val());
                            break;
                    }
                }
            }
       ');
       return $medias;
    }
}
?>