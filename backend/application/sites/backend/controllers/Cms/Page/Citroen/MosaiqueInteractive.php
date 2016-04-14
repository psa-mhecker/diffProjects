<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Module.php';
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_MosaiqueInteractive extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $return .= '
        <script type="text/javascript">
        var cpt = -2;
        var mySelectId;
        var myMulti;

        function add_VisuelMosaique(selectId, Multi){
            mySelectId = selectId;
            myMulti = Multi;
            var selectElmt = document.getElementById(selectId);
            var value = selectElmt.options[selectElmt.selectedIndex].value;

            $("." + Multi + "visu_hide").hide();

            // Mise à jour des libellé aide format attendu
            try{
                $("." + Multi + "visu_1 span.ratioHelp").html(" '.addslashes(t('FORMAT_ATTENDU')).' " + window.ratioHelpData[value][1]);
                $("." + Multi + "visu_2 span.ratioHelp").html(" '.addslashes(t('FORMAT_ATTENDU')).' " + window.ratioHelpData[value][2]);
                $("." + Multi + "visu_3 span.ratioHelp").html(" '.addslashes(t('FORMAT_ATTENDU')).' " + window.ratioHelpData[value][3]);
                $("." + Multi + "visu_4 span.ratioHelp").html(" '.addslashes(t('FORMAT_ATTENDU')).' " + window.ratioHelpData[value][4]);
            } catch(ex){}

            switch (value) {
                case "1":
                    $("." + Multi + "visu_" + value).show();

                    var lien = $("." + Multi + "visu_" + value + " input.button")[0];
                    lien.onclick = function onclick(event) { popupMediaRatio("image", "'.$controller->_sLibPath.Pelican::$config['LIB_MEDIA'].'", this.form.elements[""+ Multi + "MEDIA_ID"], "div"+ Multi + "MEDIA_ID", "","'.$controller->_sUploadHttpPath.'","",true,"'.Pelican::$config['RECHERCHE_RATIO_DETAIL']['300x395']['value'].'"); };

                    value++;

                    $("." + Multi + "visu_" + value).show();

                     lien = $("." + Multi + "visu_" + value + " input.button")[0];
                    lien.onclick = function onclick(event) { popupMediaRatio("image", "'.$controller->_sLibPath.Pelican::$config['LIB_MEDIA'].'", this.form.elements[""+ Multi + "MEDIA_ID2"], "div"+ Multi + "MEDIA_ID2", "","'.$controller->_sUploadHttpPath.'","",true,"'.Pelican::$config['RECHERCHE_RATIO_DETAIL']['600x395']['value'].'"); };

                    value++;

                    $("." + Multi + "visu_" + value).show();

                      lien = $("." + Multi + "visu_" + value + " input.button")[0];
                    lien.onclick = function onclick(event) { popupMediaRatio("image", "'.$controller->_sLibPath.Pelican::$config['LIB_MEDIA'].'", this.form.elements[""+ Multi + "MEDIA_ID3"], "div"+ Multi + "MEDIA_ID3", "","'.$controller->_sUploadHttpPath.'","",true,"'.Pelican::$config['RECHERCHE_RATIO_DETAIL']['300x395']['value'].'"); };

                    break;

                case "2":
                    $("." + Multi + "visu_" + (value - 1)).show();
                    var lien = $("." + Multi + "visu_" + (value - 1) + " input.button")[0];
                    lien.onclick = function onclick(event) { popupMediaRatio("image", "'.$controller->_sLibPath.Pelican::$config['LIB_MEDIA'].'", this.form.elements[""+ Multi + "MEDIA_ID"], "div"+ Multi + "MEDIA_ID", "","'.$controller->_sUploadHttpPath.'","",true,"'.Pelican::$config['RECHERCHE_RATIO_DETAIL']['600x395']['value'].'"); };


                    $("." + Multi + "visu_" + value).show();
                    lien = $("." + Multi + "visu_" + value  + " input.button")[0];
                    lien.onclick = function onclick(event) { popupMediaRatio("image", "'.$controller->_sLibPath.Pelican::$config['LIB_MEDIA'].'", this.form.elements[""+ Multi + "MEDIA_ID2"], "div"+ Multi + "MEDIA_ID2", "","'.$controller->_sUploadHttpPath.'","",true,"'.Pelican::$config['RECHERCHE_RATIO_DETAIL']['600x395']['value'].'"); };


                    break;

                case "3":
                    $("." + Multi + "visu_" + (value - 2)).show();

                    var lien = $("." + Multi + "visu_" + (value - 2) + " input.button")[0];
                    lien.onclick = function onclick(event) { popupMediaRatio("image", "'.$controller->_sLibPath.Pelican::$config['LIB_MEDIA'].'", this.form.elements[""+ Multi + "MEDIA_ID"], "div"+ Multi + "MEDIA_ID", "","'.$controller->_sUploadHttpPath.'","",true,"'.Pelican::$config['RECHERCHE_RATIO_DETAIL']['600x395']['value'].'"); };


                    $("." + Multi + "visu_" + (value - 1)).show();

                    lien = $("." + Multi + "visu_" + (value - 1) + " input.button")[0];
                    lien.onclick = function onclick(event) { popupMediaRatio("image", "'.$controller->_sLibPath.Pelican::$config['LIB_MEDIA'].'", this.form.elements[""+ Multi + "MEDIA_ID2"], "div"+ Multi + "MEDIA_ID2", "","'.$controller->_sUploadHttpPath.'","",true,"'.Pelican::$config['RECHERCHE_RATIO_DETAIL']['300x395']['value'].'"); };

                    $("." + Multi + "visu_" + value).show()
                        lien = $("." + Multi + "visu_" + value + " input.button")[0];
                    lien.onclick = function onclick(event) { popupMediaRatio("image", "'.$controller->_sLibPath.Pelican::$config['LIB_MEDIA'].'", this.form.elements[""+ Multi + "MEDIA_ID3"], "div"+ Multi + "MEDIA_ID3", "","'.$controller->_sUploadHttpPath.'","",true,"'.Pelican::$config['RECHERCHE_RATIO_DETAIL']['300x395']['value'].'"); };

                    break;

                case "4":
                    $("." + Multi + "visu_" + (value - 3)).show();
                    var lien = $("." + Multi + "visu_" + (value - 3) + " input.button")[0];
                    lien.onclick = function onclick(event) { popupMediaRatio("image", "'.$controller->_sLibPath.Pelican::$config['LIB_MEDIA'].'", this.form.elements[""+ Multi + "MEDIA_ID"], "div"+ Multi + "MEDIA_ID", "","'.$controller->_sUploadHttpPath.'","",true,"'.Pelican::$config['RECHERCHE_RATIO_DETAIL']['600x790']['value'].'"); };

                    $("." + Multi + "visu_" + (value - 2)).show();
                     lien = $("." + Multi + "visu_" + (value - 2) + " input.button")[0];
                    lien.onclick = function onclick(event) { popupMediaRatio("image", "'.$controller->_sLibPath.Pelican::$config['LIB_MEDIA'].'", this.form.elements[""+ Multi + "MEDIA_ID2"], "div"+ Multi + "MEDIA_ID2", "","'.$controller->_sUploadHttpPath.'","",true,"'.Pelican::$config['RECHERCHE_RATIO_DETAIL']['300x395']['value'].'"); };

                    $("." + Multi + "visu_" + (value - 1)).show();
                     lien = $("." + Multi + "visu_" + (value - 1) + " input.button")[0];
                    lien.onclick = function onclick(event) { popupMediaRatio("image", "'.$controller->_sLibPath.Pelican::$config['LIB_MEDIA'].'", this.form.elements[""+ Multi + "MEDIA_ID3"], "div"+ Multi + "MEDIA_ID3", "","'.$controller->_sUploadHttpPath.'","",true,"'.Pelican::$config['RECHERCHE_RATIO_DETAIL']['300x395']['value'].'"); };

                    $("." + Multi + "visu_" + value).show()
                     lien = $("." + Multi + "visu_" + value + " input.button")[0];
                    lien.onclick = function onclick(event) { popupMediaRatio("image", "'.$controller->_sLibPath.Pelican::$config['LIB_MEDIA'].'", this.form.elements[""+ Multi + "MEDIA_ID4"], "div"+ Multi + "MEDIA_ID4", "","'.$controller->_sUploadHttpPath.'","",true,"'.Pelican::$config['RECHERCHE_RATIO_DETAIL']['300x790']['value'].'"); };

                    break;
            }
        }
        </script>';

        $return .= $controller->oForm->createJS('

            for(var i=0 ; i <= cpt ; i++)
            {

                if($("#'.$controller->multi.'ADD_VISUEL_MOSAIQUE"+ i +"_multi_display").val())
                {

                     if($("#'.$controller->multi.'ADD_VISUEL_MOSAIQUE"+ i +"_PAGE_ZONE_MULTI_ATTRIBUT").val() == 1 || $("#'.$controller->multi.'ADD_VISUEL_MOSAIQUE"+ i +"_PAGE_ZONE_MULTI_ATTRIBUT").val() == 3)
                    {

                         var mediastring3 = document.getElementById("imgdiv'.$controller->multi.'ADD_VISUEL_MOSAIQUE"+ i +"_MEDIA_ID3");
                            if(!mediastring3)
                            {
                            alert("'.t('FORM_MSG_VALUE_FILE').' '.t('VISUEL').'");
                            return false;
                            }

                         var htmlstring3 = document.getElementsByName("'.$controller->multi.'ADD_VISUEL_MOSAIQUE"+ i +"_PAGE_ZONE_MULTI_TEXT3")[0].value;

                            if(htmlstring3 == "")
                            {
                            alert("'.t('FORM_MSG_VALUE_REQUIRE').' '.t('TEXTE').'");
                            return false;
                            }
                    }

                     if($("#'.$controller->multi.'ADD_VISUEL_MOSAIQUE"+ i +"_PAGE_ZONE_MULTI_ATTRIBUT").val() == 4)
                    {
                    var mediastring = document.getElementById("imgdiv'.$controller->multi.'ADD_VISUEL_MOSAIQUE"+ i +"_MEDIA_ID3");
                            if(!mediastring)
                            {
                            alert("'.t('FORM_MSG_VALUE_FILE').' '.t('VISUEL').'");
                            return false;
                            }

                            var mediastring2 = document.getElementById("imgdiv'.$controller->multi.'ADD_VISUEL_MOSAIQUE"+ i +"_MEDIA_ID4");
                            if(!mediastring2)
                            {
                            alert("'.t('FORM_MSG_VALUE_FILE').' '.t('VISUEL').'");
                            return false;
                            }
                     var htmlstring = document.getElementsByName("'.$controller->multi.'ADD_VISUEL_MOSAIQUE"+ i +"_PAGE_ZONE_MULTI_TEXT3")[0].value;

                        if(htmlstring == ""){
                            alert("'.t('FORM_MSG_VALUE_REQUIRE').' '.t('TEXTE').'");
                            return false;
                            }

                    var htmlstring2 = document.getElementsByName("'.$controller->multi.'ADD_VISUEL_MOSAIQUE"+ i +"_PAGE_ZONE_MULTI_TEXT4")[0].value;

                         if(htmlstring2 == ""){
                            alert("'.t('FORM_MSG_VALUE_REQUIRE').' '.t('TEXTE').'");
                            return false;
                            }
                    }

                }

            }



        ');

        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 255, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 50);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('SOUS_TITRE'), 255, "", false, $controller->zoneValues["ZONE_TITRE2"], $controller->readO, 50);
        $return .= $controller->oForm->createEditor($controller->multi."ZONE_TEXTE", t('CHAPEAU'), false, $controller->zoneValues["ZONE_TEXTE"], $controller->readO, true, "", 500, 200);

        $sMultiName = $controller->multi.'ADD_VISUEL_MOSAIQUE';
        $return .= $controller->oForm->createMultiHmvc(
                $sMultiName,
                t('VISUEL_MOSAIQUE_FORM'),
                array(
                    'path' => __FILE__,
                    'class' => __CLASS__,
                    'method' => 'addVisuelMosaiqueForm',
                 ),
                Backoffice_Form_Helper::getPageZoneMultiValues($controller, 'VISUEL_MOSAIQUE'),
                $sMultiName, $controller->readO, '', true, true, $sMultiName
            );

        $return .= Backoffice_Form_Helper::getMentionsLegales($controller, false, 'cinemascope');

        return $return;
    }

    public static function addVisuelMosaiqueForm($oForm, $values, $readO, $multi)
    {
        $aModeOUVERTURE = array('1' => "_self", '2' => "_blank");
        $aListe = array(
            '1' => '25 – 50 - 25',
            '2' => '50 - 50',
            '3' => '50 – 25 - 25',
            '4' => '50 – 2x25 - 25',
        );

        // Liste des libellé de format pour les 4 multi, pour chaque mode mosaique
        $ratioHelpData = array(
            '1' => array(1 => t("300x395"), 2 => t("600x395"), 3 => t("300x395"), 4 => null),
            '2' => array(1 => t("600x395"), 2 => t("600x395"), 3 => null, 4 => null),
            '3' => array(1 => t("600x395"), 2 => t("300x395"), 3 => t("300x395"), 4 => null),
            '4' => array(1 => t("600x790"), 2 => t("300x395"), 3 => t("300x395"), 4 => t("300x790")),
        );

        $medias .= '
        <script type="text/javascript">
            cpt = cpt + 1;
            window.ratioHelpData = '.json_encode($ratioHelpData).';
        </script>
        ';
        $typeRatio = "";
        switch ($values["PAGE_ZONE_MULTI_ATTRIBUT"]) {

            case 1:
            $typeRatio = array(0 => "300x395", 1 => "600x395", 2 => "300x395");
            break;
            case 2:
            $typeRatio = array(0 => "600x395", 1 => "600x395", 2 => "NULL");
            break;
            case 3:
            $typeRatio = array(0 => "600x395", 1 => "300x395", 2 => "300x395");
            break;
            case 4:
            $typeRatio = array(0 => "600x790", 1 => "300x395", 2 => "300x395");
            break;
            default:
            $typeRatio = array(0 => "600x790", 1 => "300x395", 2 => "300x395");
            break;

        }

        $medias .= $oForm->createComboFromList($multi."PAGE_ZONE_MULTI_ATTRIBUT", t("MODE_MOSAIQUE"), $aListe, $values["PAGE_ZONE_MULTI_ATTRIBUT"], true, $readO, 1, false, '', true, false, 'onchange="add_VisuelMosaique(\''.$multi."PAGE_ZONE_MULTI_ATTRIBUT".'\',\''.$multi.'\')"');

        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_TITRE_1", $values["PAGE_ZONE_MULTI_TITRE"]);
        $medias .= $oForm->createHidden($multi."MEDIA_ID_1", $values["MEDIA_ID"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_TEXT_1", $values["PAGE_ZONE_MULTI_TEXT"]);

        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_LABEL_1", $values["PAGE_ZONE_MULTI_LABEL"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_URL_1", $values["PAGE_ZONE_MULTI_URL"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_URL2_1", $values["PAGE_ZONE_MULTI_URL2"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_VALUE_1", $values["PAGE_ZONE_MULTI_VALUE"]);

        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_LABEL2_1", $values["PAGE_ZONE_MULTI_LABEL2"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_URL3_1", $values["PAGE_ZONE_MULTI_URL3"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_URL4_1", $values["PAGE_ZONE_MULTI_URL4"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_MODE_1", $values["PAGE_ZONE_MULTI_MODE"]);

        $medias .= '<tr class="'.$multi.'lab_1 '.$multi.'visu_1 '.$multi.'visu_hide"><td class="formlib ">1</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMTI_1 '.$multi.'visu_1 '.$multi.'visu_hide"><td class="formlib">'.t('TITRE').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_TITRE", t('TITRE'), 50, "", false, $values["PAGE_ZONE_MULTI_TITRE"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'media_1 '.$multi.'visu_1 '.$multi.'visu_hide"><td class="formlib">'.t('VISUEL').' *</td><td class="formval">'.$oForm->createMedia($multi."MEDIA_ID", t('VISUEL'), false, "image", "", $values['MEDIA_ID'], $readO, true, true, $typeRatio[0], '<span class="ratioHelp"></span>').'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMTT_1 '.$multi.'visu_1 '.$multi.'visu_hide"><td class="formlib">'.t('TEXTE').' *</td><td class="formval" id="bob">'.$oForm->createTextArea($multi."PAGE_ZONE_MULTI_TEXT", t('TEXTE'), false, $values["PAGE_ZONE_MULTI_TEXT"], 255, $readO, 5, 100, true, "", true).'</td></tr>';

        $medias .= '<tr class="'.$multi.'LINK_1 '.$multi.'visu_1 '.$multi.'visu_hide"><td class="formlib ">'.t('LINK').'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZML_1 '.$multi.'visu_1 '.$multi.'visu_hide"><td class="formlib">'.t('LIBELLE').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_LABEL", t('LIBELLE'), 50, "", false, $values["PAGE_ZONE_MULTI_LABEL"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMU_1 '.$multi.'visu_1 '.$multi.'visu_hide"><td class="formlib">'.t('URL_WEB').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_URL", t('URL_WEB'), 50, "internallink", false, $values["PAGE_ZONE_MULTI_URL"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMU2_1 '.$multi.'visu_1 '.$multi.'visu_hide"><td class="formlib">'.t('URL_MOB').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_URL2", t('URL_MOB'), 50, "internallink", false, $values["PAGE_ZONE_MULTI_URL2"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMV_1 '.$multi.'visu_1 '.$multi.'visu_hide"><td class="formlib">'.t('MODE_OUVERTURE').' </td><td class="formval">'.$oForm->createComboFromList($multi."PAGE_ZONE_MULTI_VALUE", t("MODE_OUVERTURE"), $aModeOUVERTURE, $values["PAGE_ZONE_MULTI_VALUE"], true, $readO, "", "", "", "", true).'</td></tr>';

        $medias .= '<tr class="'.$multi.'CTA_1 '.$multi.'visu_1 '.$multi.'visu_hide"><td class="formlib ">'.t('CTA').'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZML_1 '.$multi.'visu_1 '.$multi.'visu_hide"><td class="formlib">'.t('LIBELLE').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_LABEL2", t('LIBELLE'), 50, "", false, $values["PAGE_ZONE_MULTI_LABEL2"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMU3_1 '.$multi.'visu_1 '.$multi.'visu_hide"><td class="formlib">'.t('URL_WEB').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_URL3", t('URL_WEB'), 50, "internallink", false, $values["PAGE_ZONE_MULTI_URL3"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMU4_1 '.$multi.'visu_1 '.$multi.'visu_hide"><td class="formlib">'.t('URL_MOB').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_URL4", t('URL_MOB'), 50, "internallink", false, $values["PAGE_ZONE_MULTI_URL4"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMM_1 '.$multi.'visu_1 '.$multi.'visu_hide"><td class="formlib">'.t('MODE_OUVERTURE').' </td><td class="formval">'.$oForm->createComboFromList($multi."PAGE_ZONE_MULTI_MODE", t("MODE_OUVERTURE"), $aModeOUVERTURE, $values["PAGE_ZONE_MULTI_MODE"], true, $readO, "", "", "", "", true).'</td></tr>';

        $medias .= '<tr class="'.$multi.'sep_1 '.$multi.'visu_2 '.$multi.'visu_hide"><td class="formSep" colspan="2"></td></tr>';

        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_TITRE2_2", $values["PAGE_ZONE_MULTI_TITRE2"]);
        $medias .= $oForm->createHidden($multi."MEDIA_ID2_2", $values["MEDIA_ID2"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_TEXT2_2", $values["PAGE_ZONE_MULTI_TEXT2"]);

        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_LABEL3_2", $values["PAGE_ZONE_MULTI_LABEL3"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_URL5_2", $values["PAGE_ZONE_MULTI_URL5"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_URL6_2", $values["PAGE_ZONE_MULTI_URL6"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_VALUE2_2", $values["PAGE_ZONE_MULTI_VALUE2"]);

        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_LABEL4_2", $values["PAGE_ZONE_MULTI_LABEL4"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_URL7_2", $values["PAGE_ZONE_MULTI_URL7"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_URL8_2", $values["PAGE_ZONE_MULTI_URL8"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_MODE2_2", $values["PAGE_ZONE_MULTI_MODE2"]);

        $medias .= '<tr class="'.$multi.'lab_2 '.$multi.'visu_2 '.$multi.'visu_hide"><td class="formlib ">2</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMTI_2 '.$multi.'visu_2 '.$multi.'visu_hide"><td class="formlib">'.t('TITRE').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_TITRE2", t('TITRE'), 50, "", false, $values["PAGE_ZONE_MULTI_TITRE2"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'media_2 '.$multi.'visu_2 '.$multi.'visu_hide"><td class="formlib">'.t('VISUEL').' *</td><td class="formval">'.$oForm->createMedia($multi."MEDIA_ID2", t('VISUEL'), false, "image", "", $values['MEDIA_ID2'], $readO, true, true, $typeRatio[1], '<span class="ratioHelp"></span>').'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMTT_2 '.$multi.'visu_2 '.$multi.'visu_hide"><td class="formlib">'.t('TEXTE').' *</td><td class="formval">'.$oForm->createTextArea($multi."PAGE_ZONE_MULTI_TEXT2", t('TEXTE'), false, $values["PAGE_ZONE_MULTI_TEXT2"], 255, $readO, 5, 100, true, "", true).'</td></tr>';

        $medias .= '<tr class="'.$multi.'LINK_2 '.$multi.'visu_2 '.$multi.'visu_hide"><td class="formlib ">'.t('LINK').'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZML_2 '.$multi.'visu_2 '.$multi.'visu_hide"><td class="formlib">'.t('LIBELLE').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_LABEL3", t('LIBELLE'), 50, "", false, $values["PAGE_ZONE_MULTI_LABEL3"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMU_2 '.$multi.'visu_2 '.$multi.'visu_hide"><td class="formlib">'.t('URL_WEB').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_URL5", t('URL_WEB'), 50, "internallink", false, $values["PAGE_ZONE_MULTI_URL5"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMU2_2 '.$multi.'visu_2 '.$multi.'visu_hide"><td class="formlib">'.t('URL_MOB').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_URL6", t('URL_MOB'), 50, "internallink", false, $values["PAGE_ZONE_MULTI_URL6"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMV_2 '.$multi.'visu_2 '.$multi.'visu_hide"><td class="formlib">'.t('MODE_OUVERTURE').' </td><td class="formval">'.$oForm->createComboFromList($multi."PAGE_ZONE_MULTI_VALUE2", t("MODE_OUVERTURE"), $aModeOUVERTURE, $values["PAGE_ZONE_MULTI_VALUE2"], true, $readO, "", "", "", "", true).'</td></tr>';

        $medias .= '<tr class="'.$multi.'CTA_2 '.$multi.'visu_2 '.$multi.'visu_hide"><td class="formlib ">'.t('CTA').'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZML_2 '.$multi.'visu_2 '.$multi.'visu_hide"><td class="formlib">'.t('LIBELLE').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_LABEL4", t('LIBELLE'), 50, "", false, $values["PAGE_ZONE_MULTI_LABEL4"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMU3_2 '.$multi.'visu_2 '.$multi.'visu_hide"><td class="formlib">'.t('URL_WEB').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_URL7", t('URL_WEB'), 50, "internallink", false, $values["PAGE_ZONE_MULTI_URL7"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMU4_2 '.$multi.'visu_2 '.$multi.'visu_hide"><td class="formlib">'.t('URL_MOB').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_URL8", t('URL_MOB'), 50, "internallink", false, $values["PAGE_ZONE_MULTI_URL8"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMM_2 '.$multi.'visu_2 '.$multi.'visu_hide"><td class="formlib">'.t('MODE_OUVERTURE').' </td><td class="formval">'.$oForm->createComboFromList($multi."PAGE_ZONE_MULTI_MODE2", t("MODE_OUVERTURE"), $aModeOUVERTURE, $values["PAGE_ZONE_MULTI_MODE2"], true, $readO, "", "", "", "", true).'</td></tr>';

        $medias .= '<tr class="'.$multi.'sep_2 '.$multi.'visu_3 '.$multi.'visu_hide"><td class="formSep" colspan="2"></td></tr>';

        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_TITRE3_3", $values["PAGE_ZONE_MULTI_TITRE3"]);
        $medias .= $oForm->createHidden($multi."MEDIA_ID3_3", $values["MEDIA_ID3"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_TEXT3_3", $values["PAGE_ZONE_MULTI_TEXT3"]);

        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_LABEL5_3", $values["PAGE_ZONE_MULTI_LABEL5"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_URL9_3", $values["PAGE_ZONE_MULTI_URL9"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_URL10_3", $values["PAGE_ZONE_MULTI_URL10"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_VALUE3_3", $values["PAGE_ZONE_MULTI_VALUE3"]);

        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_LABEL6_3", $values["PAGE_ZONE_MULTI_LABEL6"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_URL11_3", $values["PAGE_ZONE_MULTI_URL11"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_URL12_3", $values["PAGE_ZONE_MULTI_URL12"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_MODE3_3", $values["PAGE_ZONE_MULTI_MODE3"]);

        $medias .= '<tr class="'.$multi.'lab_3 '.$multi.'visu_3 '.$multi.'visu_hide"><td class="formlib ">3</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMTI_3 '.$multi.'visu_3 '.$multi.'visu_hide"><td class="formlib">'.t('TITRE').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_TITRE3", t('TITRE'), 50, "", false, $values["PAGE_ZONE_MULTI_TITRE3"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'media_3 '.$multi.'visu_3 '.$multi.'visu_hide"><td class="formlib">'.t('VISUEL').' *</td><td class="formval">'.$oForm->createMedia($multi."MEDIA_ID3", t('VISUEL'), false, "image", "", $values['MEDIA_ID3'], $readO, true, true, $typeRatio[2], '<span class="ratioHelp"></span>').'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMTT_3 '.$multi.'visu_3 '.$multi.'visu_hide"><td class="formlib">'.t('TEXTE').' *</td><td class="formval">'.$oForm->createTextArea($multi."PAGE_ZONE_MULTI_TEXT3", t('TEXTE'), false, $values["PAGE_ZONE_MULTI_TEXT3"], 255, $readO, 5, 100, true, "", true).'</td></tr>';

        $medias .= '<tr class="'.$multi.'LINK_3 '.$multi.'visu_3 '.$multi.'visu_hide"><td class="formlib ">'.t('LINK').'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZML_3 '.$multi.'visu_3 '.$multi.'visu_hide"><td class="formlib">'.t('LIBELLE').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_LABEL5", t('LIBELLE'), 50, "", false, $values["PAGE_ZONE_MULTI_LABEL5"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMU_3 '.$multi.'visu_3 '.$multi.'visu_hide"><td class="formlib">'.t('URL_WEB').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_URL9", t('URL_WEB'), 50, "internallink", false, $values["PAGE_ZONE_MULTI_URL9"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMU2_3 '.$multi.'visu_3 '.$multi.'visu_hide"><td class="formlib">'.t('URL_MOB').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_URL10", t('URL_MOB'), 50, "internallink", false, $values["PAGE_ZONE_MULTI_URL10"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMV_3 '.$multi.'visu_3 '.$multi.'visu_hide"><td class="formlib">'.t('MODE_OUVERTURE').' </td><td class="formval">'.$oForm->createComboFromList($multi."PAGE_ZONE_MULTI_VALUE3", t("MODE_OUVERTURE"), $aModeOUVERTURE, $values["PAGE_ZONE_MULTI_VALUE3"], true, $readO, "", "", "", "", true).'</td></tr>';

        $medias .= '<tr class="'.$multi.'CTA_3 '.$multi.'visu_3 '.$multi.'visu_hide"><td class="formlib ">'.t('CTA').'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZML_3 '.$multi.'visu_3 '.$multi.'visu_hide"><td class="formlib">'.t('LIBELLE').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_LABEL6", t('LIBELLE'), 50, "", false, $values["PAGE_ZONE_MULTI_LABEL6"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMU3_3 '.$multi.'visu_3 '.$multi.'visu_hide"><td class="formlib">'.t('URL_WEB').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_URL11", t('URL_WEB'), 50, "internallink", false, $values["PAGE_ZONE_MULTI_URL11"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMU4_3 '.$multi.'visu_3 '.$multi.'visu_hide"><td class="formlib">'.t('URL_MOB').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_URL12", t('URL_MOB'), 50, "internallink", false, $values["PAGE_ZONE_MULTI_URL12"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMM_3 '.$multi.'visu_3 '.$multi.'visu_hide"><td class="formlib">'.t('MODE_OUVERTURE').' </td><td class="formval">'.$oForm->createComboFromList($multi."PAGE_ZONE_MULTI_MODE3", t("MODE_OUVERTURE"), $aModeOUVERTURE, $values["PAGE_ZONE_MULTI_MODE3"], true, $readO, "", "", "", "", true).'</td></tr>';

        $medias .= '<tr class="'.$multi.'sep_3 '.$multi.'visu_4 '.$multi.'visu_hide"><td class="formSep" colspan="2"></td></tr>';

        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_TITRE4_4", $values["PAGE_ZONE_MULTI_TITRE4"]);
        $medias .= $oForm->createHidden($multi."MEDIA_ID4_4", $values["MEDIA_ID4"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_TEXT4_4", $values["PAGE_ZONE_MULTI_TEXT4"]);

        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_LABEL7_4", $values["PAGE_ZONE_MULTI_LABEL7"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_URL13_4", $values["PAGE_ZONE_MULTI_URL13"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_URL14_4", $values["PAGE_ZONE_MULTI_URL14"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_VALUE4_4", $values["PAGE_ZONE_MULTI_VALUE4"]);

        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_LABEL8_4", $values["PAGE_ZONE_MULTI_LABEL8"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_URL15_4", $values["PAGE_ZONE_MULTI_URL15"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_URL16_4", $values["PAGE_ZONE_MULTI_URL16"]);
        $medias .= $oForm->createHidden($multi."PAGE_ZONE_MULTI_MODE4_4", $values["PAGE_ZONE_MULTI_MODE4"]);

        $medias .= '<tr class="'.$multi.'lab_4 '.$multi.'visu_4 '.$multi.'visu_hide"><td class="formlib ">4</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMTI_4 '.$multi.'visu_4 '.$multi.'visu_hide"><td class="formlib">'.t('TITRE').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_TITRE4", t('TITRE'), 50, "", false, $values["PAGE_ZONE_MULTI_TITRE4"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'media_4 '.$multi.'visu_4 '.$multi.'visu_hide"><td class="formlib">'.t('VISUEL').' *</td><td class="formval">'.$oForm->createMedia($multi."MEDIA_ID4", t('VISUEL'), false, "image", "", $values['MEDIA_ID4'], $readO, true, true, "300x790", '<span class="ratioHelp"></span>').'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMTT_4 '.$multi.'visu_4 '.$multi.'visu_hide"><td class="formlib">'.t('TEXTE').' *</td><td class="formval">'.$oForm->createTextArea($multi."PAGE_ZONE_MULTI_TEXT4", t('TEXTE'), false, $values["PAGE_ZONE_MULTI_TEXT4"], 255, $readO, 5, 100, true, "", true).'</td></tr>';

        $medias .= '<tr class="'.$multi.'LINK_4 '.$multi.'visu_4 '.$multi.'visu_hide"><td class="formlib ">'.t('LINK').'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZML_4 '.$multi.'visu_4 '.$multi.'visu_hide"><td class="formlib">'.t('LIBELLE').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_LABEL7", t('LIBELLE'), 50, "", false, $values["PAGE_ZONE_MULTI_LABEL7"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMU_4 '.$multi.'visu_4 '.$multi.'visu_hide"><td class="formlib">'.t('URL_WEB').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_URL13", t('URL_WEB'), 50, "internallink", false, $values["PAGE_ZONE_MULTI_URL13"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMU2_4 '.$multi.'visu_4 '.$multi.'visu_hide"><td class="formlib">'.t('URL_MOB').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_URL14", t('URL_MOB'), 50, "internallink", false, $values["PAGE_ZONE_MULTI_URL14"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMV_4 '.$multi.'visu_4 '.$multi.'visu_hide"><td class="formlib">'.t('MODE_OUVERTURE').' </td><td class="formval">'.$oForm->createComboFromList($multi."PAGE_ZONE_MULTI_VALUE4", t("MODE_OUVERTURE"), $aModeOUVERTURE, $values["PAGE_ZONE_MULTI_VALUE4"], true, $readO, "", "", "", "", true).'</td></tr>';

        $medias .= '<tr class="'.$multi.'CTA_4 '.$multi.'visu_4 '.$multi.'visu_hide"><td class="formlib ">'.t('CTA').'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZML_4 '.$multi.'visu_4 '.$multi.'visu_hide"><td class="formlib">'.t('LIBELLE').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_LABEL8", t('LIBELLE'), 50, "", false, $values["PAGE_ZONE_MULTI_LABEL8"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMU3_4 '.$multi.'visu_4 '.$multi.'visu_hide"><td class="formlib">'.t('URL_WEB').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_URL15", t('URL_WEB'), 50, "internallink", false, $values["PAGE_ZONE_MULTI_URL15"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMU4_4 '.$multi.'visu_4 '.$multi.'visu_hide"><td class="formlib">'.t('URL_MOB').' </td><td class="formval">'.$oForm->createInput($multi."PAGE_ZONE_MULTI_URL16", t('URL_MOB'), 50, "internallink", false, $values["PAGE_ZONE_MULTI_URL16"], $readO, 50, true).'</td></tr>';
        $medias .= '<tr class="'.$multi.'PZMM_4 '.$multi.'visu_4 '.$multi.'visu_hide"><td class="formlib">'.t('MODE_OUVERTURE').' </td><td class="formval">'.$oForm->createComboFromList($multi."PAGE_ZONE_MULTI_MODE4", t("MODE_OUVERTURE"), $aModeOUVERTURE, $values["PAGE_ZONE_MULTI_MODE4"], true, $readO, "", "", "", "", true).'</td></tr>';

        $medias .= '
            <script type="text/javascript">

				add_VisuelMosaique("'.$multi.'PAGE_ZONE_MULTI_ATTRIBUT", "'.$multi.'");

            </script>
			';

        $medias .= $oForm->createJS('
                 var selectValue = $("#'.$multi.'PAGE_ZONE_MULTI_ATTRIBUT").val();
                 var emptyVignette = false;

                     	    $("#'.$multi.'PAGE_ZONE_MULTI_TITRE_1").val($("#'.$multi.'PAGE_ZONE_MULTI_TITRE").val());
                            $("#'.$multi.'MEDIA_ID_1").val($("#'.$multi.'MEDIA_ID").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_TEXT_1").val($("#'.$multi.'PAGE_ZONE_MULTI_TEXT").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_LABEL_1").val($("#'.$multi.'PAGE_ZONE_MULTI_LABEL").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_URL_1").val($("#'.$multi.'PAGE_ZONE_MULTI_URL").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_URL2_1").val($("#'.$multi.'PAGE_ZONE_MULTI_URL2").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_VALUE_1").val($("#'.$multi.'PAGE_ZONE_MULTI_VALUE").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_LABEL2_1").val($("#'.$multi.'PAGE_ZONE_MULTI_LABEL2").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_URL3_1").val($("#'.$multi.'PAGE_ZONE_MULTI_URL3").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_URL4_1").val($("#'.$multi.'PAGE_ZONE_MULTI_URL4").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_MODE_1").val($("#'.$multi.'PAGE_ZONE_MULTI_MODE").val());


                            $("#'.$multi.'PAGE_ZONE_MULTI_TITRE2_2").val($("#'.$multi.'PAGE_ZONE_MULTI_TITRE2").val());
                            $("#'.$multi.'MEDIA_ID2_2").val($("#'.$multi.'MEDIA_ID2").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_TEXT2_2").val($("#'.$multi.'PAGE_ZONE_MULTI_TEXT2").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_LABEL3_2").val($("#'.$multi.'PAGE_ZONE_MULTI_LABEL3").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_URL5_2").val($("#'.$multi.'PAGE_ZONE_MULTI_URL5").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_URL6_2").val($("#'.$multi.'PAGE_ZONE_MULTI_URL6").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_VALUE2_2").val($("#'.$multi.'PAGE_ZONE_MULTI_VALUE2").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_LABEL4_2").val($("#'.$multi.'PAGE_ZONE_MULTI_LABEL4").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_URL7_2").val($("#'.$multi.'PAGE_ZONE_MULTI_URL7").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_URL8_2").val($("#'.$multi.'PAGE_ZONE_MULTI_URL8").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_MODE2_2").val($("#'.$multi.'PAGE_ZONE_MULTI_MODE2").val());


                            $("#'.$multi.'PAGE_ZONE_MULTI_TITRE3_3").val($("#'.$multi.'PAGE_ZONE_MULTI_TITRE3").val());
                            $("#'.$multi.'MEDIA_ID3_3").val($("#'.$multi.'MEDIA_ID3").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_TEXT3_3").val($("#'.$multi.'PAGE_ZONE_MULTI_TEXT3").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_LABEL5_3").val($("#'.$multi.'PAGE_ZONE_MULTI_LABEL5").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_URL9_3").val($("#'.$multi.'PAGE_ZONE_MULTI_URL9").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_URL10_3").val($("#'.$multi.'PAGE_ZONE_MULTI_URL10").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_VALUE3_3").val($("#'.$multi.'PAGE_ZONE_MULTI_VALUE3").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_LABEL6_3").val($("#'.$multi.'PAGE_ZONE_MULTI_LABEL6").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_URL11_3").val($("#'.$multi.'PAGE_ZONE_MULTI_URL11").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_URL12_3").val($("#'.$multi.'PAGE_ZONE_MULTI_URL12").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_MODE3_3").val($("#'.$multi.'PAGE_ZONE_MULTI_MODE3").val());

                            $("#'.$multi.'PAGE_ZONE_MULTI_TITRE4_4").val($("#'.$multi.'PAGE_ZONE_MULTI_TITRE4").val());
                            $("#'.$multi.'MEDIA_ID4_4").val($("#'.$multi.'MEDIA_ID4").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_TEXT4_4").val($("#'.$multi.'PAGE_ZONE_MULTI_TEXT4").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_LABEL7_4").val($("#'.$multi.'PAGE_ZONE_MULTI_LABEL7").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_URL13_4").val($("#'.$multi.'PAGE_ZONE_MULTI_URL13").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_URL14_4").val($("#'.$multi.'PAGE_ZONE_MULTI_URL14").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_VALUE4_4").val($("#'.$multi.'PAGE_ZONE_MULTI_VALUE4").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_LABEL8_4").val($("#'.$multi.'PAGE_ZONE_MULTI_LABEL8").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_URL15_4").val($("#'.$multi.'PAGE_ZONE_MULTI_URL15").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_URL16_4").val($("#'.$multi.'PAGE_ZONE_MULTI_URL16").val());
                            $("#'.$multi.'PAGE_ZONE_MULTI_MODE4_4").val($("#'.$multi.'PAGE_ZONE_MULTI_MODE4").val());



');

        return $medias;
    }

    public static function save()
    {
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
        Backoffice_Form_Helper::savePageZoneMultiValues('ADD_VISUEL_MOSAIQUE', 'VISUEL_MOSAIQUE');
        Pelican_Cache::clean("Frontend/Citroen/ZoneMulti");
    }
}
