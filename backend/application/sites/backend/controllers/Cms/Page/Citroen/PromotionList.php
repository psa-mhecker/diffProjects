<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_PromotionList extends Cms_Page_Citroen
{
    public static $decachePublication = array(
        array(
            "Frontend/Citroen/MultiPromotions" ,
            array("PAGE_ID","LANGUE_ID","PAGE_VERSION"),
        ),
    );

    public static function render(Pelican_Controller $controller)
    {
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);

        $oConnection = Pelican_Db::getInstance();
        $aBind[':PAGE_ID'] = $controller->zoneValues['PAGE_ID'];
        $aBind[':LANGUE_ID'] = $controller->zoneValues['LANGUE_ID'];
        $aBind[':PAGE_VERSION'] = $controller->zoneValues['PAGE_VERSION'];
        $aBind[':ZONE_TEMPLATE_ID'] = $controller->zoneValues['ZONE_TEMPLATE_ID'];
        $sSQL = "select * from #pref#_page_zone_multi where PAGE_ID=:PAGE_ID and LANGUE_ID=:LANGUE_ID and PAGE_VERSION=:PAGE_VERSION and ZONE_TEMPLATE_ID=:ZONE_TEMPLATE_ID";
        $multiValues = $oConnection->queryTab($sSQL, $aBind);
        $return .= $controller->oForm->createMultiHmvc($controller->multi."multiPromotion", t('PROMOTION_LIST'), array(
                "path" => Pelican::$config["APPLICATION_CONTROLLERS"]."/Cms/Page/Citroen/PromotionList.php",
                "class" => "Cms_Page_Citroen_PromotionList",
                "method" => "multiPromotion",
            ), $multiValues, $controller->multi."multiPromotion", $controller->readO, "", true, true, $controller->multi."multiPromotion");
        //$return .= Backoffice_Form_Helper::getMentionsLegales($controller);
        return $return;
    }

    public static function multiPromotion($oForm, $values, $readO, $multi)
    {
        $iPageid = $_GET['id'];
        $oConnection = Pelican_Db::getInstance();
        $return .= $oForm->createInput($multi."PAGE_ZONE_MULTI_LABEL", t('TITRE'), 255, "", true, $values['PAGE_ZONE_MULTI_LABEL'], $readO, 40);
        $return .= $oForm->createInput($multi."PAGE_ZONE_MULTI_LABEL2", t('SOUS_TITRE'), 255, "", false, $values['PAGE_ZONE_MULTI_LABEL2'], $readO, 75);
        $return .= $oForm->createEditor($multi."PAGE_ZONE_MULTI_TEXT", t('TEXTE'), false, $values['PAGE_ZONE_MULTI_TEXT'], $readO, true, "", 500, 200);
        $return .= $oForm->createMedia($multi."MEDIA_ID", t('VISUEL'), false, "image", "", $values['MEDIA_ID'], $readO, true, false, '16_9');
        $return .= $oForm->createMedia($multi."YOUTUBE_ID", t('VIDEO'), false, "video", "", $values['YOUTUBE_ID'], $readO);
        $return .= $oForm->createMedia($multi."MEDIA_ID2", t('FICHIER_SWF'), false, "flash", "", $values['MEDIA_ID2'], $readO);
        $return .= $oForm->createTextArea($multi."PAGE_ZONE_MULTI_TEXT3", t('HTML'), false, $values["PAGE_ZONE_MULTI_TEXT3"], "", $readO, 2, 100, false, "", false);
        $sqlData = "select
                        CONCAT(#pref#_page_zone_multi.PAGE_ID,'||',#pref#_page_zone_multi.LANGUE_ID,'||',ZONE_TEMPLATE_ID,'||',PAGE_ZONE_MULTI_ID) as id,
                        PAGE_ZONE_MULTI_LABEL8 as lib
                    from
                        #pref#_page_zone_multi
                    INNER JOIN
                        #pref#_page
                            ON (#pref#_page.PAGE_ID = #pref#_page_zone_multi.PAGE_ID AND #pref#_page.PAGE_DRAFT_VERSION = #pref#_page_zone_multi.PAGE_VERSION AND #pref#_page.LANGUE_ID = #pref#_page_zone_multi.LANGUE_ID)
                    INNER JOIN
                        #pref#_page_version
                            ON (#pref#_page_version.PAGE_ID = #pref#_page_zone_multi.PAGE_ID AND #pref#_page_version.PAGE_VERSION = #pref#_page_zone_multi.PAGE_VERSION AND #pref#_page_version.LANGUE_ID = #pref#_page_zone_multi.LANGUE_ID)
                    where
                        #pref#_page_zone_multi.LANGUE_ID= ".$_SESSION[APP]['LANGUE_ID']."
                    AND
                        PAGE_ZONE_MULTI_TYPE = 'Promotion'
                    AND
                        SITE_ID = ".$_SESSION[APP]["SITE_ID"]."
                    AND
                        TEMPLATE_PAGE_ID = ".Pelican::$config['TEMPLATE_PAGE']['DETAIL_PROMOTION']."
                    AND PAGE_PARENT_ID =".$iPageid;

        if (!empty($values["PAGE_ZONE_MULTI_TEXT2"])) {
            //Explode puis implode pour pouvoir placer les quotes, sinon MySQL s'emboruille avec le concat
            $values["PAGE_ZONE_MULTI_TEXT2"] = explode(",", $values["PAGE_ZONE_MULTI_TEXT2"]);
            if (is_array($values["PAGE_ZONE_MULTI_TEXT2"]) && !empty($values["PAGE_ZONE_MULTI_TEXT2"])) {
                for ($i = 0;$i<count($values["PAGE_ZONE_MULTI_TEXT2"]);$i++) {
                    $values["PAGE_ZONE_MULTI_TEXT2"][$i] = $oConnection->strToBind($values["PAGE_ZONE_MULTI_TEXT2"][$i]);
                }
                $values["PAGE_ZONE_MULTI_TEXT2"] = implode(',', $values["PAGE_ZONE_MULTI_TEXT2"]);

                $aBind[":LANGUE_ID"] = $_SESSION[APP]['LANGUE_ID'];
                $aBind[":CLE_PROMOTION"] = $values["PAGE_ZONE_MULTI_TEXT2"];
                $aBind[":SITE_ID"] = $_SESSION[APP]["SITE_ID"];
                $aBind[":TEMPLATE_PAGE_ID"] = Pelican::$config['TEMPLATE_PAGE']['DETAIL_PROMOTION'];

                $sqlSelected = "
                        select
                            CONCAT(#pref#_page_zone_multi.PAGE_ID,'||',#pref#_page_zone_multi.LANGUE_ID,'||',ZONE_TEMPLATE_ID,'||',PAGE_ZONE_MULTI_ID) as id,
                            PAGE_ZONE_MULTI_LABEL2 as lib
                        from
                            #pref#_page_zone_multi
                        INNER JOIN
                            #pref#_page
                                ON (#pref#_page.PAGE_ID = #pref#_page_zone_multi.PAGE_ID AND #pref#_page.PAGE_DRAFT_VERSION = #pref#_page_zone_multi.PAGE_VERSION AND #pref#_page.LANGUE_ID = #pref#_page_zone_multi.LANGUE_ID)
                        INNER JOIN
                            #pref#_page_version
                                ON (#pref#_page_version.PAGE_ID = #pref#_page_zone_multi.PAGE_ID AND #pref#_page_version.PAGE_VERSION = #pref#_page_zone_multi.PAGE_VERSION AND #pref#_page_version.LANGUE_ID = #pref#_page_zone_multi.LANGUE_ID)
                        where
                            #pref#_page_zone_multi.LANGUE_ID= :LANGUE_ID
                        AND
                            PAGE_ZONE_MULTI_TYPE = 'Promotion'
                        AND
                            CONCAT(#pref#_page_zone_multi.PAGE_ID,'||',#pref#_page_zone_multi.LANGUE_ID,'||',ZONE_TEMPLATE_ID,'||',PAGE_ZONE_MULTI_ID) IN(:CLE_PROMOTION)
                        AND
                            SITE_ID = :SITE_ID
                        AND
                            TEMPLATE_PAGE_ID = :TEMPLATE_PAGE_ID";
            }
        }

        $return .= $oForm->createAssocFromSql(Pelican_Db::getInstance(), $multi."PAGE_ZONE_MULTI_TEXT2", t("PROMOTION"), $sqlData, $sqlSelected, false, true, $readO, 8, 200, false, "", "", $aBind);

        $aModeAffichage = array("1" => t("TYPE_VISUEL"),"2" => t("TYPE_MIXTE_2_COLONNES"));

        $return .= $oForm->createComboFromList($multi."PAGE_ZONE_MULTI_LABEL3", t("DISPLAY_MODE_IN_LIST"), $aModeAffichage, $values['PAGE_ZONE_MULTI_LABEL3'], true, $readO);

        $return .= Backoffice_Form_Helper::getCtaHmvc($oForm, $values, $readO, $multi);
        $return .= Backoffice_Form_Helper::getMentionsLegalesHmvc($oForm, $values, $readO, $multi);

        $return .= $oForm->createJS('

            if($("#'.$multi.'MEDIA_ID").val() == "" && $("#'.$multi.'YOUTUBE_ID").val() == "" && $("#'.$multi.'MEDIA_ID2").val() == "" && $("#'.$multi.'PAGE_ZONE_MULTI_TEXT3").val() == ""){
                alert(\''.t('NO_MEDIA_ALERT', 'js2').'\');
                return false;
            }
        ');

        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
        Backoffice_Form_Helper::savePageZoneMultiValuesHmvc('multiPromotion', 'multiPromotion');
        Pelican_Cache::clean('Frontend/Citroen/MultiPromotions');
        Pelican_Cache::clean('Frontend/Citroen/Promotions');
    }
}
