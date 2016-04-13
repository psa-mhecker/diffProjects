<?php
include_once(Pelican::$config['CONTROLLERS_ROOT']."/Cms/Page/Module.php");
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_Global_Footer_ElementsLegaux extends Cms_Page_Citroen
{

    public static function render(Pelican_Controller $controller)
    {
        
        $oConnection = Pelican_Db::getInstance();
        $aBind[':PAGE_ID'] = $controller->zoneValues['PAGE_ID'];
        $aBind[':LANGUE_ID'] = $controller->zoneValues['LANGUE_ID'];
        $aBind[':PAGE_VERSION'] = $controller->zoneValues['PAGE_VERSION'];
        $aBind[':ZONE_TEMPLATE_ID'] = $controller->zoneValues['ZONE_TEMPLATE_ID'];
        $return .= $controller->oForm->createMultiHmvc($controller->multi."CTA", t('LIEN'), array(
                "path" => Pelican::$config["APPLICATION_CONTROLLERS"] . "/Cms/Page/Citroen/Global/Footer/ElementsLegaux.php",
                "class" => "Cms_Page_Citroen_Global_Footer_ElementsLegaux",
                "method" => "multiCTA"
            ), Backoffice_Form_Helper::getPageZoneMultiValues($controller, 'CTA'), $controller->multi."CTA", $controller->readO, 5, true, true, $controller->multi."CTA");
        $return .= $controller->oForm->showSeparator();
        $return .= $controller->oForm->createLabel(t('MENTIONS_LEGALES'), "");
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('LIBELLE'), 40, "", true, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 40);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_URL", t('URL'), 255, "internallink", true, $controller->zoneValues['ZONE_URL'], $controller->readO, 75);
        $return .= $controller->oForm->showSeparator();
        $return .= $controller->oForm->createLabel(t('COOKIES'), "");
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('LIBELLE'), 40, "", true, $controller->zoneValues['ZONE_TITRE2'], $controller->readO, 40);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_URL2", t('URL'), 255, "internallink", true, $controller->zoneValues['ZONE_URL2'], $controller->readO, 75);
        return $return; 
    }

    public static function multiCTA($oForm, $values, $readO, $multi)
    {
        if ($values['PAGE_ZONE_MULTI_URL']) {
            $temp['URL'] = $values['PAGE_ZONE_MULTI_URL'];
            $temp['WEB_MOBILE'][] = "1";
        }
        if ($values['PAGE_ZONE_MULTI_URL2']) {
            $temp['URL'] = $values['PAGE_ZONE_MULTI_URL2'];
            $temp['WEB_MOBILE'][] = "2";
        }
        $return .= $oForm->createInput($multi."PAGE_ZONE_MULTI_LABEL", t('LIBELLE'), 255, "", true, $values['PAGE_ZONE_MULTI_LABEL'], $readO, 75);
        $return .= $oForm->createInput($multi."URL", t('URL'), 255, "internallink", true, $temp['URL'], $readO, 75);
        $return .= $oForm->createRadioFromList($multi."PAGE_ZONE_MULTI_OPTION", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $values['PAGE_ZONE_MULTI_OPTION'], true, $readO);
        $return .= $oForm->createCheckBoxFromList($multi."WEB_MOBILE", t('AFFICHAGE_WEB_MOBILE'), array('1' => t('WEB'), '2' => t('MOBILE')), $temp['WEB_MOBILE'], true, $readO);
        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        $oConnection = Pelican_Db::getInstance();
        parent::save();
        readMulti("CTA", "CTA");
        $aBind[':PAGE_ID'] = Pelican_Db::$values['PAGE_ID'];
        $aBind[':LANGUE_ID'] = Pelican_Db::$values['LANGUE_ID'];
        $aBind[':PAGE_VERSION'] = Pelican_Db::$values['PAGE_VERSION'];
        $aBind[':ZONE_TEMPLATE_ID'] = Pelican_Db::$values['ZONE_TEMPLATE_ID'];
        $sSQL = "delete from #pref#_page_zone_multi where PAGE_ID=:PAGE_ID and LANGUE_ID=:LANGUE_ID and PAGE_VERSION=:PAGE_VERSION and ZONE_TEMPLATE_ID=:ZONE_TEMPLATE_ID";
        $oConnection->query($sSQL, $aBind);
        if (Pelican_Db::$values['CTA']) {
            Pelican_Db::$values['PAGE_ZONE_MULTI_TYPE'] = 'CTA';
            foreach (Pelican_Db::$values['CTA'] as $i => $item) {
                if ($item['multi_display'] == 1) {
                    $id++;
                    $DBVALUES_SAVE = Pelican_Db::$values;
                    Pelican_Db::$values['PAGE_ZONE_MULTI_ID'] = $id;
                    Pelican_Db::$values['PAGE_ZONE_MULTI_LABEL'] = $item['PAGE_ZONE_MULTI_LABEL'];
                    if (in_array(1, $item['WEB_MOBILE'])) {
                        Pelican_Db::$values['PAGE_ZONE_MULTI_URL'] = $item['URL'];
                    }
                    if (in_array(2, $item['WEB_MOBILE'])) {
                        Pelican_Db::$values['PAGE_ZONE_MULTI_URL2'] = $item['URL'];
                    }
                    Pelican_Db::$values['PAGE_ZONE_MULTI_OPTION'] = $item['PAGE_ZONE_MULTI_OPTION'];
                    $oConnection->updateTable(Pelican::$config['DATABASE_INSERT'], "#pref#_page_zone_multi");
                    Pelican_Db::$values = $DBVALUES_SAVE;
                }
            }
        }
    }

}