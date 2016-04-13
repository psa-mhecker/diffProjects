<?php
include_once(Pelican::$config['CONTROLLERS_ROOT']."/Cms/Page/Module.php");
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_Global_Footer_Aides extends Cms_Page_Citroen
{

    public static function render(Pelican_Controller $controller)
    {
        
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi."ZONE_TITRE10", t('AFFICHER_BESOIN_AIDE'), array(1 => ""), $controller->zoneValues['ZONE_TITRE10'], false, $controller->readO);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE_COLONNE'), 40, "", false, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 75);
        $oConnection = Pelican_Db::getInstance();
        $aBind[':PAGE_ID'] = $controller->zoneValues['PAGE_ID'];
        $aBind[':LANGUE_ID'] = $controller->zoneValues['LANGUE_ID'];
        $aBind[':PAGE_VERSION'] = $controller->zoneValues['PAGE_VERSION'];
        $aBind[':ZONE_TEMPLATE_ID'] = $controller->zoneValues['ZONE_TEMPLATE_ID'];
        $return .= $controller->oForm->createMultiHmvc($controller->multi."CTA", t('LIEN'), array(
                "path" => Pelican::$config["APPLICATION_CONTROLLERS"] . "/Cms/Page/Citroen/Global/Footer/Aides.php",
                "class" => "Cms_Page_Citroen_Global_Footer_Aides",
                "method" => "multiCTA"
            ), Backoffice_Form_Helper::getPageZoneMultiValues($controller, 'CTA'), $controller->multi."CTA", $controller->readO, 6, true, true, $controller->multi."CTA");
        $return .= $controller->oForm->showSeparator();
        $return .= $controller->oForm->createCheckBoxFromList($controller->multi."ZONE_TITRE11", t('AFFICHER_ASSISTANCE_TELEPHONIQUE'), array(1 => ""), $controller->zoneValues['ZONE_TITRE11'], false, $controller->readO);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('TITRE'), 50, "", false, $controller->zoneValues['ZONE_TITRE2'], $controller->readO, 50);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE3", t('NUMERO_TELEPHONE'), 20, "", false, $controller->zoneValues['ZONE_TITRE3'], $controller->readO, 20);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE4", t('MENTIONS_LEGALES'), 20, "", false, $controller->zoneValues['ZONE_TITRE4'], $controller->readO, 20);
        
        //Footer bulle assistance tel
        $return .= $controller->oForm->createInput($controller->multi ."ZONE_TITRE5", t('TEXTE'), 150, "", false, $controller->zoneValues['ZONE_TITRE5'], $controller->readO, 75);
        $return .= $controller->oForm->createMedia($controller->multi . "MEDIA_ID", t('VISUEL'), false, "image", "", $controller->zoneValues["MEDIA_ID"], $readO, true, false, "carre");        
        $return .= $controller->oForm->createInput($controller->multi."ZONE_URL", t('URL'), 255, "internallink", false, $controller->zoneValues['ZONE_URL'], $controller->readO, 75);
        $return .= $controller->oForm->createRadioFromList($controller->multi ."ZONE_TITRE6", t('MODE_OUVERTURE'), array('1' => "_self", '2' => "_blank"), $controller->zoneValues['ZONE_TITRE6'], false, $readO);
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