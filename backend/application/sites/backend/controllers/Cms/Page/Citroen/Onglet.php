<?php

include_once Pelican::$config['CONTROLLERS_ROOT']."/Cms/Page/Module.php";
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_Onglet extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $return .= $controller->oForm->createJS("
                var onglet = document.getElementById('".$controller->multi."ONGLET0_PAGE_ZONE_MULTI_LABEL');
                var onglet1 = document.getElementById('".$controller->multi."ONGLET1_PAGE_ZONE_MULTI_LABEL');

                if((onglet == null || onglet.value ==0) || (onglet1 == null || onglet1.value ==0)){
                    alert('".t('SAISIE_ONGLET', 'js')."');
                    return false;
                }

            ");

        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 255, "", false, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 75);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('SOUS_TITRE'), 255, "", false, $controller->zoneValues['ZONE_TITRE2'], $controller->readO, 75);
        $return .= $controller->oForm->createEditor($controller->multi."ZONE_TEXTE", t('TEXTE'), false, $controller->zoneValues['ZONE_TEXTE'], $controller->readO, true, "", 500, 200);
            /* $oConnection = Pelican_Db::getInstance();
              $aBind[':PAGE_ID'] = $controller->zoneValues['PAGE_ID'];
              $aBind[':LANGUE_ID'] = $controller->zoneValues['LANGUE_ID'];
              $aBind[':PAGE_VERSION'] = $controller->zoneValues['PAGE_VERSION'];
              $aBind[':ZONE_TEMPLATE_ID'] = $controller->zoneValues['ZONE_TEMPLATE_ID'];
              $sSQL = "select * from #pref#_page_zone_multi where PAGE_ID=:PAGE_ID and LANGUE_ID=:LANGUE_ID and PAGE_VERSION=:PAGE_VERSION and ZONE_TEMPLATE_ID=:ZONE_TEMPLATE_ID";
              $multiValues = $oConnection->queryTab($sSQL, $aBind); */
            $return .= $controller->oForm->createMultiHmvc($controller->multi."ONGLET", t('ONGLET'), array(
                    "path" => Pelican::$config["APPLICATION_CONTROLLERS"]."/Cms/Page/Citroen/Onglet.php",
                    "class" => "Cms_Page_Citroen_Onglet",
                    "method" => "multiOnglet",
                    ), Backoffice_Form_Helper::getPageZoneMultiValues($controller, 'ONGLET'), $controller->multi."ONGLET", $controller->readO, 5, true, true, $controller->multi."ONGLET");

        return $return;
    }

    public static function multiOnglet($oForm, $values, $readO, $multi)
    {
        $return .= $oForm->createInput($multi."PAGE_ZONE_MULTI_LABEL", t('TITRE_ONGLET'), 40, "", true, $values['PAGE_ZONE_MULTI_LABEL'], $readO, 40);
        $return .= $oForm->createInput($multi."PAGE_ZONE_MULTI_OPTION", t('NOMBRE_DE_TRANCHE_ONGLET'), 2, "number", true, $values['PAGE_ZONE_MULTI_OPTION'], $readO, 2);

        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        $oConnection = Pelican_Db::getInstance();
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
        Backoffice_Form_Helper::savePageZoneMultiValues("ONGLET", "ONGLET");
    }
}
