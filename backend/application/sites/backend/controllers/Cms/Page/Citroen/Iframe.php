<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_Iframe extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        //fonction qui gère le remplacement des onglets
        $return = "<script type='text/javascript'>
            function gestionChampsIframe(obj){
                if(obj.value == 'web' || obj == 'web' ){
                    hideMob();
                    showWeb();
                }else{
                    hideWeb();
                    showMob();
                }

            }

            function showWeb(){
                $('input[name=".$controller->multi."ZONE_TITRE9]').parent().parent().css('display', 'table-row');
                $('#".$controller->multi."ZONE_TITRE10').parent().parent().css('display', 'table-row');
                $('#".$controller->multi."ZONE_TITRE14').parent().parent().css('display', 'table-row');
                $('#".$controller->multi."ZONE_URL').parent().parent().css('display', 'table-row');
                $('textarea[name=".$controller->multi."ZONE_TEXTE2]').parent().parent().css('display', 'table-row');
                $('textarea[name=".$controller->multi."ZONE_TEXTE3]').parent().parent().css('display', 'table-row');
            }

            function hideWeb(){
                $('input[name=".$controller->multi."ZONE_TITRE9]').parent().parent().css('display', 'none');
                $('#".$controller->multi."ZONE_TITRE10').parent().parent().css('display', 'none');
                $('#".$controller->multi."ZONE_TITRE14').parent().parent().css('display', 'none');
                $('#".$controller->multi."ZONE_URL').parent().parent().css('display', 'none');
                $('textarea[name=".$controller->multi."ZONE_TEXTE2]').parent().parent().css('display', 'none');
                $('textarea[name=".$controller->multi."ZONE_TEXTE3]').parent().parent().css('display', 'none');
            }

            function showMob(){
                $('input[name=".$controller->multi."ZONE_TITRE11]').parent().parent().css('display', 'table-row');
                $('#".$controller->multi."ZONE_TITRE12').parent().parent().css('display', 'table-row');
                $('#".$controller->multi."ZONE_TITRE15').parent().parent().css('display', 'table-row');
                $('#".$controller->multi."ZONE_URL2').parent().parent().css('display', 'table-row');
                $('textarea[name=".$controller->multi."ZONE_TEXTE5]').parent().parent().css('display', 'table-row');
                $('textarea[name=".$controller->multi."ZONE_TEXTE6]').parent().parent().css('display', 'table-row');
            }

            function hideMob(){
                $('input[name=".$controller->multi."ZONE_TITRE11]').parent().parent().css('display', 'none');
                $('#".$controller->multi."ZONE_TITRE12').parent().parent().css('display', 'none');
                $('#".$controller->multi."ZONE_TITRE15').parent().parent().css('display', 'none');
                $('#".$controller->multi."ZONE_URL2').parent().parent().css('display', 'none');
                $('textarea[name=".$controller->multi."ZONE_TEXTE5]').parent().parent().css('display', 'none');
                $('textarea[name=".$controller->multi."ZONE_TEXTE6]').parent().parent().css('display', 'none');
            }
            $( document ).ready(function() {
                 gestionChampsIframe('web');
            });

        </script>";
        $aWebMob = array("web" => t("WEB"), "mob" => t("MOBILE"));
        $return .= Backoffice_Form_Helper::getFormCommunStart($controller);
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi."ZONE_TITRE8", t("IFRAME_MOBILE"), $aAffichageCheckboxMob, $controller->zoneValues["ZONE_TITRE8"], false, $controller->readO);
        $return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_ACTION", t('PARAMETRAGE'), $aWebMob, "web", false, false, "h", false, "onclick=(gestionChampsIframe(this));");

        /*Web*/
        $return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_TITRE9", t('UNITE_LARGEUR'), Pelican::$config['IFRAME']["UNITE_LARGEUR"], $controller->zoneValues["ZONE_TITRE9"], false, false, "h");
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE10", t('LARGEUR'), 4, "", true, $controller->zoneValues["ZONE_TITRE10"], $controller->readO, 100);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE14", t('HAUTEUR_PX'), 4, "", false, $controller->zoneValues["ZONE_TITRE14"], $controller->readO, 100);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_URL", t('URL'), 255, "internallink", true, $controller->zoneValues["ZONE_URL"], $controller->readO, 100);
        $return .= $controller->oForm->createTextArea($controller->multi."ZONE_TEXTE2", t('ALTERNATIVE'), true, $controller->zoneValues["ZONE_TEXTE2"], "", $controller->readO, 2, 100, false, "", false);
        $return .= $controller->oForm->createTextArea($controller->multi."ZONE_TEXTE3", t('DELETE_CONTENT').' (?)', false, $controller->zoneValues["ZONE_TEXTE3"], "", $controller->readO, 2, 100, false, "", false, "", t('CONTENU_DEL'));

        /*Mobile*/
        $return .= $controller->oForm->createRadioFromList($controller->multi."ZONE_TITRE11", t('UNITE_LARGEUR'), Pelican::$config['IFRAME']["UNITE_LARGEUR"], $controller->zoneValues["ZONE_TITRE11"], false, false, "h");
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE12", t('LARGEUR'), 4, "", false, $controller->zoneValues["ZONE_TITRE12"], $controller->readO, 100);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE15", t('HAUTEUR_PX'), 4, "", false, $controller->zoneValues["ZONE_TITRE15"], $controller->readO, 100);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_URL2", t('URL'), 255, "internallink", false, $controller->zoneValues["ZONE_URL2"], $controller->readO, 100);
        $return .= $controller->oForm->createTextArea($controller->multi."ZONE_TEXTE5", t('ALTERNATIVE'), false, $controller->zoneValues["ZONE_TEXTE5"], "", $controller->readO, 2, 100, false, "", false);
        $return .= $controller->oForm->createTextArea($controller->multi."ZONE_TEXTE6", t('DELETE_CONTENT').' (?)', false, $controller->zoneValues["ZONE_TEXTE6"], "", $controller->readO, 2, 100, false, "", false, "", t('CONTENU_DEL'));

        $return .= Backoffice_Form_Helper::getFormCommunEndMentionCta($controller);

        return $return;
    }

    /*Enregistrement complémentaires multi
     */
    public static function save()
    {
        //Pelican_Db::$values["ZONE_TEXTE2"] = htmlentities(Pelican_Db::$values["ZONE_TEXTE2"]);

        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
        Backoffice_Form_Helper::saveCta();
        Pelican_Cache::clean("Frontend/Citroen/ZoneMulti");
    }
}
