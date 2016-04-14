<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_Technologie_Detail extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 255, "", true, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 100);
        $return .= $controller->oForm->createMedia($controller->multi."MEDIA_ID", t('MEDIA'), true, "image", "", $controller->zoneValues["MEDIA_ID"], $controller->readO, true, false, 'concept_car');
        // SHARER todo
        $return .= Backoffice_Form_Helper::getFormGroupeReseauxSociaux($controller, "ZONE_LABEL2", "PUBLIC");
        $return .= $controller->oForm->createTextArea($controller->multi."ZONE_TEXTE", t('CHAPEAU'), true, $controller->zoneValues["ZONE_TEXTE"], "", $controller->readO, 2, 100, false, "", false);
        $return .= $controller->oForm->createEditor($controller->multi."ZONE_TEXTE2", t('Content'), true, $controller->zoneValues["ZONE_TEXTE2"], $controller->readO, true, "", 500, 150);

        $aTheme = Pelican_Cache::fetch("Frontend/Citroen/Technologie/Theme", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]["LANGUE_ID"],
        ));

        $return .= $controller->oForm->createComboFromList($controller->multi."ZONE_TOOL", t('THEME'), $aTheme, $controller->zoneValues['ZONE_TOOL'], true, $controller->readO);

        $return .= Backoffice_Form_Helper::getCta($controller, 3);

        $return .= Backoffice_Form_Helper::getMentionsLegales($controller, false, 'cinemascope');
        $return .= Backoffice_Form_Helper::getPushMediaCommun($controller);

        return $return;
    }

    public static function save()
    {
        Backoffice_Form_Helper::saveFormAffichage();
        Backoffice_Form_Helper::saveCta();
        parent::save();
        Backoffice_Form_Helper::savePushGallery();
        Pelican_Cache::clean("Frontend/Citroen/ZoneMulti");
        Pelican_Cache::clean("Frontend/Citroen/Technologie/Gallerie");
    }
}
