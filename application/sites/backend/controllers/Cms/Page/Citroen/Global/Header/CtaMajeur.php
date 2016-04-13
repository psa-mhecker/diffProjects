<?php
include_once(Pelican::$config['CONTROLLERS_ROOT']."/Cms/Page/Module.php");
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_Global_Header_CtaMajeur extends Cms_Page_Citroen
{

    public static function render(Pelican_Controller $controller)
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':PAGE_ID'] = $controller->zoneValues['PAGE_ID'];
        $aBind[':LANGUE_ID'] = $controller->zoneValues['LANGUE_ID'];
        $aBind[':PAGE_VERSION'] = $controller->zoneValues['PAGE_VERSION'];
        $aBind[':ZONE_TEMPLATE_ID'] = $controller->zoneValues['ZONE_TEMPLATE_ID'];
        $sSQL = "select * from #pref#_page_zone_multi where PAGE_ID=:PAGE_ID and LANGUE_ID=:LANGUE_ID and PAGE_VERSION=:PAGE_VERSION and ZONE_TEMPLATE_ID=:ZONE_TEMPLATE_ID";
        $multiValues = $oConnection->queryTab($sSQL, $aBind);
        $return .= $controller->oForm->createMultiHmvc($controller->multi."CTA", t('CTA'), array(
                "path" => Pelican::$config["APPLICATION_CONTROLLERS"] . "/Cms/Page/Citroen/Global/Header/CtaMajeur.php",
                "class" => "Cms_Page_Citroen_Global_Header_CtaMajeur",
                "method" => "multiCTA"
            ), Backoffice_Form_Helper::getPageZoneMultiValues($controller, 'CTA'), $controller->multi."CTA", $controller->readO, 2, true, true, $controller->multi."CTA");
        return $return;
    }

    public static function multiCTA($oForm, $values, $readO, $multi)
    {
        $return .= $oForm->createInput($multi."PAGE_ZONE_MULTI_LABEL", t('LIBELLE'), 40, "", true, $values['PAGE_ZONE_MULTI_LABEL'], $readO, 40);
        $return .= $oForm->createInput($multi."PAGE_ZONE_MULTI_URL", t('URL'), 255, "internallink", true, $values['PAGE_ZONE_MULTI_URL'], $readO, 75);
        $return .= $oForm->createRadioFromList($multi."PAGE_ZONE_MULTI_OPTION", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $values['PAGE_ZONE_MULTI_OPTION'], true, $readO);
        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        parent::save();
        Backoffice_Form_Helper::savePageZoneMultiValues('CTA','CTA');
    }

}