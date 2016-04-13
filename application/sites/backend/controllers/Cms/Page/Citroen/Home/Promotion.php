<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_Home_Promotion extends Cms_Page_Citroen
{
    public static $decachePublication = array(
        array(
            "Frontend/Citroen/MultiPromotions" ,
            array("PAGE_ID","LANGUE_ID","PAGE_VERSION")
        ) ,
        array(
            "Frontend/Citroen/Promotion" ,
            array("SITE_ID", "LANGUE_ID", "PAGE_ID")
        ) ,
        array(
            "Frontend/Citroen/OtherPromotions" ,
            array("SITE_ID", "LANGUE_ID")
        )
    );


    public static function render(Pelican_Controller $controller)
    {

        $prefix = $controller->multi . "Promotion";

        $oConnection = Pelican_Db::getInstance();
        $aBind[':PAGE_ID'] = $controller->zoneValues['PAGE_ID'];
        $aBind[':LANGUE_ID'] = $controller->zoneValues['LANGUE_ID'];
        $aBind[':PAGE_VERSION'] = $controller->zoneValues['PAGE_VERSION'];
        $aBind[':ZONE_TEMPLATE_ID'] = $controller->zoneValues['ZONE_TEMPLATE_ID'];

        $sSQL = "select * from #pref#_page_zone_multi where PAGE_ID=:PAGE_ID and LANGUE_ID=:LANGUE_ID and PAGE_VERSION=:PAGE_VERSION and ZONE_TEMPLATE_ID=:ZONE_TEMPLATE_ID";
        $multiValues = $oConnection->queryTab($sSQL, $aBind);

       $return .= $controller->oForm->createMultiHmvc($controller->multi."Promotion", t('PROMOTION'), array(
                "path" => Pelican::$config["APPLICATION_CONTROLLERS"] . "/Cms/Page/Citroen/Home/Promotion.php",
                "class" => "Cms_Page_Citroen_Home_Promotion",
                "method" => "Promotion"
            ), $multiValues, $controller->multi."Promotion", $controller->readO, 1, true, true, $controller->multi."Promotion");
        return $return;
    }

    public static function Promotion($oForm, $values, $readO, $multi)
    {
        $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $aBind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];

       $return .= Backoffice_Form_Helper::getFormModeAffichageMultiHmvc($oForm, $values,$readO,$multi,'PAGE_ZONE_MULTI_LABEL7');
        $return .= $oForm->createInput($multi."PAGE_ZONE_MULTI_LABEL", t('TITRE'), 255, "", false, $values['PAGE_ZONE_MULTI_LABEL'], $readO, 40);
        $return .= Backoffice_Form_Helper::getFormGroupeReseauxSociauxMultiHmvc($oForm, $values,$readO,$multi,'PAGE_ZONE_MULTI_LABEL6','PUBLIC',false,true);
        $return .= $oForm->createInput($multi."PAGE_ZONE_MULTI_LABEL8", t('TITREINTERNE'), 255, "", true, $values['PAGE_ZONE_MULTI_LABEL8'], $readO, 75);
        $return .= $oForm->createInput($multi."PAGE_ZONE_MULTI_LABEL2", t('SOUSTITRE'), 255, "", false, $values['PAGE_ZONE_MULTI_LABEL2'], $readO, 75);
        $return .= $oForm->createEditor($multi."PAGE_ZONE_MULTI_TEXT", t('TEXTE'), false, $values['PAGE_ZONE_MULTI_TEXT'], $controller->readO, true, "", 500, 200);
        $return .= $oForm->createMedia($multi."MEDIA_ID", t ('VISUEL'), false, "image", "", $values['MEDIA_ID'], $mReadO, true, false);
        $return .= $oForm->createMedia($multi."YOUTUBE_ID", t ('VIDEO'), false, "video", "", $values['YOUTUBE_ID'], $readO);
        $return .= $oForm->createMedia($multi."MEDIA_ID2", t ('FICHIER_SWF'), false, "flash", "", $values['MEDIA_ID2'], $readO);
        $return .= $oForm->createTextArea($multi . "PAGE_ZONE_MULTI_TEXT3", t('HTML'), false, $values["PAGE_ZONE_MULTI_TEXT3"], "", $readO, 2, 100, false, "", false);

        $sqlData = "select
            VEHICULE_ID, VEHICULE_LABEL
            from #pref#_vehicule
            where SITE_ID=:SITE_ID
            and LANGUE_ID=:LANGUE_ID
            order by VEHICULE_LABEL";
        $return .= $oForm->createComboFromSql(Pelican_Db::getInstance(), $multi . "PAGE_ZONE_MULTI_LABEL5", t("VEHICULE_SELECTION"), $sqlData, $values['PAGE_ZONE_MULTI_LABEL5'], false, $readO, "1", false, "", true, false, "", "", $aBind);

        $aModeAffichage = array("1"=>t("TYPE_VISUEL"),"2"=>t("TYPE_MIXTE_2_COLONNES"));

        $return .= $oForm->createComboFromList($multi."PAGE_ZONE_MULTI_LABEL3", t("DISPLAY_MODE_IN_LIST"), $aModeAffichage, $values['PAGE_ZONE_MULTI_LABEL3'], true, $readO);

        $return .= Backoffice_Form_Helper::getCtaHmvc($oForm, $values,$readO,$multi);
        $return .= Backoffice_Form_Helper::getMentionsLegalesHmvc($oForm, $values,$readO,$multi);

        return $return;
    }

       public static function save(Pelican_Controller $controller)
    {
        $oConnection = Pelican_Db::getInstance();
        parent::save();
        Backoffice_Form_Helper::savePageZoneMultiValuesHmvc('Promotion', 'Promotion');
        Pelican_Cache::clean('Frontend/Citroen/MultiPromotions');
        Pelican_Cache::clean('Frontend/Citroen/Promotions');
		Pelican_Cache::clean('Frontend/Page/ZoneMultiByPageId');
    }
}