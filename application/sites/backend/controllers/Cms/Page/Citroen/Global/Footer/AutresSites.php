<?php
include_once(Pelican::$config['CONTROLLERS_ROOT']."/Cms/Page/Module.php");
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_Global_Footer_AutresSites extends Cms_Page_Citroen
{

    public static function render(Pelican_Controller $controller)
    {
        
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi."ZONE_TITRE10", t('AFFICHER_AUTRES_SITES'), array(1 => ""), $controller->zoneValues['ZONE_TITRE10'], false, $controller->readO);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE_COLONNE'), 40, "", false, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 40);
        $oConnection = Pelican_Db::getInstance();
        $aBind[':PAGE_ID'] = $controller->zoneValues['PAGE_ID'];
        $aBind[':LANGUE_ID'] = $controller->zoneValues['LANGUE_ID'];
        $aBind[':PAGE_VERSION'] = $controller->zoneValues['PAGE_VERSION'];
        $aBind[':ZONE_TEMPLATE_ID'] = $controller->zoneValues['ZONE_TEMPLATE_ID'];
        $return .= $controller->oForm->createMultiHmvc($controller->multi."CTA", t('LIEN'), array(
                "path" => Pelican::$config["APPLICATION_CONTROLLERS"] . "/Cms/Page/Citroen/Global/Footer/AutresSites.php",
                "class" => "Cms_Page_Citroen_Global_Footer_AutresSites",
                "method" => "multiCTA"
            ), Backoffice_Form_Helper::getPageZoneMultiValues($controller, 'CTA'), $controller->multi."CTA", $controller->readO, 5, true, true, $controller->multi."CTA");
        $return .= $controller->oForm->showSeparator();
        return $return; 
    }

    public static function multiCTA($oForm, $values, $readO, $multi)
    {
        $return .= $oForm->createInput($multi."PAGE_ZONE_MULTI_LABEL", t('LIBELLE'), 255, "", true, $values['PAGE_ZONE_MULTI_LABEL'], $readO, 75);
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