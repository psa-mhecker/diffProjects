<?php
include_once(Pelican::$config['CONTROLLERS_ROOT']."/Cms/Page/Module.php");
//include_once(dirname(__FILE__) . '../../../../Module/Navigation/1level.php');
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_Global_Header_NavigationPush extends Cms_Page_Citroen
//class Cms_Page_Citroen_Global_Header_NavigationPush extends Cms_Page_Module_Navigation_1level
{

    public static function render(Pelican_Controller $controller)
    {
        //self::$max = 3;
        //return parent::render($controller);
        $oConnection = Pelican_Db::getInstance();
        $aBind[':PAGE_ID'] = $controller->zoneValues['PAGE_ID'];
        $aBind[':LANGUE_ID'] = $controller->zoneValues['LANGUE_ID'];
        $aBind[':PAGE_VERSION'] = $controller->zoneValues['PAGE_VERSION'];
        $aBind[':ZONE_TEMPLATE_ID'] = $controller->zoneValues['ZONE_TEMPLATE_ID'];
        $return .= $controller->oForm->createMultiHmvc($controller->multi."PUSH", t('PUSH'), array(
            "path" => Pelican::$config["APPLICATION_CONTROLLERS"] . "/Cms/Page/Citroen/Global/Header/NavigationPush.php",
            "class" => "Cms_Page_Citroen_Global_Header_NavigationPush",
            "method" => "multiPush"
        ), Backoffice_Form_Helper::getPageZoneMultiValues($controller, 'PUSH'), $controller->multi."PUSH", $controller->readO, 3, true, true, $controller->multi."PUSH");
        $return .= $controller->oForm->createJS('
            if($("#count_'.$controller->multi.'PUSH ").val() < 0) {
                alert(\''.t('ALERT_MSG_PUSH_MIN', 'js2').'\');
                return false;
            }
       ');
        return $return;
    }

    public static function multiPush($oForm, $values, $readO, $multi)
    {
        $return .= $oForm->createInput($multi."PAGE_ZONE_MULTI_LABEL", t('LIBELLE'), 40, "", true, $values['PAGE_ZONE_MULTI_LABEL'], $readO, 40);
        $return .= $oForm->createMedia($multi."MEDIA_ID", t('IMAGE'), false, "image", "", $values['MEDIA_ID'], $readO, true, false, 'carre');
        $return .= $oForm->createInput($multi."PAGE_ZONE_MULTI_URL", t('URL'), 255, "internallink", true, $values['PAGE_ZONE_MULTI_URL'], $readO, 75);
        $return .= $oForm->createRadioFromList($multi."PAGE_ZONE_MULTI_OPTION", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $values['PAGE_ZONE_MULTI_OPTION'], true, $readO);
        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        parent::save();
        Backoffice_Form_Helper::savePageZoneMultiValues('PUSH', 'PUSH');
    }

}