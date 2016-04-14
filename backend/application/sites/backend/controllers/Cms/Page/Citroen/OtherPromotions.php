<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_OtherPromotions extends Cms_Page_Citroen
{
    public static $decachePublication = array(
        array(
            "Frontend/Citroen/OtherPromotions" ,
            array("SITE_ID","LANGUE_ID"),
        ),
    );

    public static function render(Pelican_Controller $controller)
    {
        $oConnection =  Pelican_Db::getInstance();

        $return .= Backoffice_Form_Helper::getFormAffichage($controller);

        $return .= $controller->oForm->createLabel("", t('WARNING_VEHICLE_NAME'));
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 255, "", true, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 75);

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
                        #pref#_page_zone_multi.LANGUE_ID= ".$controller->zoneValues['LANGUE_ID']."
                    AND
                        PAGE_ZONE_MULTI_TYPE = 'Promotion'
                    AND
                        SITE_ID = ".$_SESSION[APP]["SITE_ID"]."
                    AND
                        TEMPLATE_PAGE_ID = ".Pelican::$config['TEMPLATE_PAGE']['DETAIL_PROMOTION']."
                    ";
        if (!empty($controller->zoneValues["ZONE_TEXTE2"])) {
            //Explode puis implode pour pouvoir placer les quotes, sinon MySQL s'emboruille avec le concat
            $controller->zoneValues["ZONE_TEXTE2"] = explode(",", $controller->zoneValues["ZONE_TEXTE2"]);
            if (is_array($controller->zoneValues["ZONE_TEXTE2"]) && !empty($controller->zoneValues["ZONE_TEXTE2"])) {
                for ($i = 0;$i<count($controller->zoneValues["ZONE_TEXTE2"]);$i++) {
                    $controller->zoneValues["ZONE_TEXTE2"][$i] = $oConnection->strToBind($controller->zoneValues["ZONE_TEXTE2"][$i]);
                    $aCase[$controller->zoneValues["ZONE_TEXTE2"][$i]] = $i;
                }
                $controller->zoneValues["ZONE_TEXTE2"] = implode(',', $controller->zoneValues["ZONE_TEXTE2"]);

                $aBind[":LANGUE_ID"] = $controller->zoneValues['LANGUE_ID'];
                $aBind[":CLE_PROMOTION"] = $controller->zoneValues["ZONE_TEXTE2"];
                $aBind[":SITE_ID"] = $_SESSION[APP]["SITE_ID"];
                $aBind[":TEMPLATE_PAGE_ID"] = Pelican::$config['TEMPLATE_PAGE']['DETAIL_PROMOTION'];

                $sqlSelected = "
                        select
                            CONCAT(#pref#_page_zone_multi.PAGE_ID,'||',#pref#_page_zone_multi.LANGUE_ID,'||',ZONE_TEMPLATE_ID,'||',PAGE_ZONE_MULTI_ID) as id,
                            PAGE_ZONE_MULTI_LABEL8 as lib,
                            PAGE_ZONE_MULTI_ID
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
                            TEMPLATE_PAGE_ID = :TEMPLATE_PAGE_ID
                        ORDER BY ".$oConnection->getCaseClause("CONCAT(#pref#_page_zone_multi.PAGE_ID,'||',#pref#_page_zone_multi.LANGUE_ID,'||',ZONE_TEMPLATE_ID,'||',PAGE_ZONE_MULTI_ID)", $aCase, 1);
            }
        }

        $return .= $controller->oForm->createAssocFromSql(Pelican_Db::getInstance(), $controller->multi."ZONE_TEXTE2", t("PROMOTION"), $sqlData, $sqlSelected, false, true, $readO, 8, 200, false, "", "", $aBind, "PAGE_ZONE_MULTI_ID");
       /*$return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('LIBELLE'), 255, "", false, $controller->zoneValues['ZONE_TITRE2'], $controller->readO, 75);
       $return .= $controller->oForm->createInput($controller->multi."ZONE_URL", t('LIEN'), 255, "internallink", false, $controller->zoneValues['ZONE_URL'], $controller->readO, 75);
       $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE3", t('LIBELLE'), 255, "", false, $controller->zoneValues['ZONE_TITRE3'], $controller->readO, 75);
       $return .= $controller->oForm->createInput($controller->multi."ZONE_URL2", t('LIEN'), 255, "internallink", false, $controller->zoneValues['ZONE_URL2'], $controller->readO, 75);
        * */

        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        Backoffice_Form_Helper::saveFormAffichage();
        if (!empty(Pelican_Db::$values["ZONE_TEXTE2"]) && is_array(Pelican_Db::$values["ZONE_TEXTE2"])) {
            Pelican_Db::$values["ZONE_TEXTE2"] = implode(",", Pelican_Db::$values["ZONE_TEXTE2"]);
        }
        parent::save();
    }
}
