<?php
include_once Pelican::$config['CONTROLLERS_ROOT']."/Cms/Page/Module.php";
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_Liens extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[':PAGE_ID'] = $controller->zoneValues['PAGE_ID'];
        $aBind[':LANGUE_ID'] = $controller->zoneValues['LANGUE_ID'];
        $aBind[':PAGE_VERSION'] = $controller->zoneValues['PAGE_VERSION'];
        $aBind[':ZONE_TEMPLATE_ID'] = $controller->zoneValues['ZONE_TEMPLATE_ID'];
        $sSQL = "
            SELECT
                    *
            FROM
                    #pref#_page_zone_multi
            WHERE
                    PAGE_ID=:PAGE_ID
            AND LANGUE_ID=:LANGUE_ID
            AND PAGE_VERSION=:PAGE_VERSION
            AND ZONE_TEMPLATE_ID=:ZONE_TEMPLATE_ID";
        $multiValues = $oConnection->queryTab($sSQL, $aBind);

        $return .= Backoffice_Form_Helper::getFormAffichage($controller, false, true);
        $return .= $controller->oForm->createLabel(t("POUR_AFFICHER_TRANCHE_VOUS_DEVEZ_AJOUTER_4_LIENS_MINIMUMS"), "");
        $return .= $controller->oForm->createMultiHmvc($controller->multi."LIENFORM", t('ADD_LIEN'), array(
            "path" => __FILE__,
            "class" => __CLASS__,
            "method" => "addLienForm",
        ), $multiValues, $controller->multi."LIENFORM", $controller->readO, "6", true, true, $controller->multi."LIENFORM");

        return $return;
    }

    public static function addLienForm($oForm, $values, $readO, $multi)
    {
        $link .= $oForm->createInput($multi."PAGE_ZONE_MULTI_TITRE", t('LIBELLE'), 255, "", true, $values["PAGE_ZONE_MULTI_TITRE"], $readO, 50);
        $link .= $oForm->createInput($multi."PAGE_ZONE_MULTI_URL", t('LINK'), 255, "internallink", true, $values["PAGE_ZONE_MULTI_URL"], $readO, 50);
        $link .= $oForm->createRadioFromList($multi.'PAGE_ZONE_MULTI_ATTRIBUT', t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $values['PAGE_ZONE_MULTI_ATTRIBUT'], false, $readO);

        return $link;
    }

    public static function save()
    {
        $oConnection = Pelican_Db::getInstance();
        Backoffice_Form_Helper::saveFormAffichage();
        $id = 0;
        if (Pelican_Db::$values["form_action"] != Pelican_Db::DATABASE_DELETE) {
            //Push media gallery
            $aSiteAddMulti        =    Backoffice_Form_Helper::myReadMulti(Pelican_Db::$values, "LIENFORM");
            $aBind[":LANGUE_ID"] = Pelican_Db::$values['LANGUE_ID'];
            $aBind[":PAGE_VERSION"] = Pelican_Db::$values['PAGE_VERSION'];
            $aBind[":PAGE_ID"] = Pelican_Db::$values['PAGE_ID'];
            $aBind[":ZONE_TEMPLATE_ID"] = Pelican_Db::$values['ZONE_TEMPLATE_ID'];
            $aBind[":PAGE_ZONE_MULTI_TYPE"] = $oConnection->strToBind('LIENFORM');

            $sqlDelete = "DELETE FROM #pref#_page_zone_multi
                                    WHERE
                                    LANGUE_ID = :LANGUE_ID
                                    and PAGE_VERSION = :PAGE_VERSION
                                    and PAGE_ID = :PAGE_ID
                                    and ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
                                    and PAGE_ZONE_MULTI_TYPE = :PAGE_ZONE_MULTI_TYPE
                                    ";
            $oConnection->query($sqlDelete, $aBind);

            foreach ($aSiteAddMulti as $aSiteInfos) {
                //Vérification de la prise en compte du le multi
                if ($aSiteInfos['multi_display'] == 1) {
                    $aBind[":PAGE_ZONE_MULTI_TITRE"] = $oConnection->strToBind($aSiteInfos['PAGE_ZONE_MULTI_TITRE']);
                    $aBind[":PAGE_ZONE_MULTI_URL"] = $oConnection->strToBind($aSiteInfos['PAGE_ZONE_MULTI_URL']);
                    $aBind[":PAGE_ZONE_MULTI_ATTRIBUT"] = $oConnection->strToBind($aSiteInfos['PAGE_ZONE_MULTI_ATTRIBUT']);
                    $aBind[":PAGE_ZONE_MULTI_VALUE"] = $oConnection->strToBind($aSiteInfos['PAGE_ZONE_MULTI_VALUE']);

                    $id++;
                    $aBind[":PAGE_ZONE_MULTI_ID"] = $id;

                    $sqlInsert = "INSERT into #pref#_page_zone_multi (
                        PAGE_ID,
                        LANGUE_ID,
                        PAGE_VERSION,
                        ZONE_TEMPLATE_ID,
                        PAGE_ZONE_MULTI_ID,
                        PAGE_ZONE_MULTI_TYPE,
                        PAGE_ZONE_MULTI_TITRE,
                        PAGE_ZONE_MULTI_VALUE,
                        PAGE_ZONE_MULTI_URL,
                        PAGE_ZONE_MULTI_ATTRIBUT
                        )
                        VALUES(
                        :PAGE_ID,
                        :LANGUE_ID,
                        :PAGE_VERSION,
                        :ZONE_TEMPLATE_ID,
                        :PAGE_ZONE_MULTI_ID,
                        :PAGE_ZONE_MULTI_TYPE,
                        :PAGE_ZONE_MULTI_TITRE,
                        :PAGE_ZONE_MULTI_VALUE,
                        :PAGE_ZONE_MULTI_URL,
                        :PAGE_ZONE_MULTI_ATTRIBUT
                        )";
                    $oConnection->query($sqlInsert, $aBind);
                }
            }
        }
        parent::save();
        Pelican_Cache::clean("Frontend/Citroen/ZoneMulti");
    }
}
