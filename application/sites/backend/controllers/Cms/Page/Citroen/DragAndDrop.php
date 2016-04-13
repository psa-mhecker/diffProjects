<?php
include_once (dirname(__FILE__) . '/../Module.php');
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_DragAndDrop extends Cms_Page_Citroen
{

    public static function render(Pelican_Controller $controller)
    {
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        $return .= $controller->oForm->showSeparator();
        $return .= $controller->oForm->createLabel(t('VISUEL')." 1", "");
        $return .= $controller->oForm->createMedia($controller->multi."MEDIA_ID", t('VISUEL'), true, "image", "", $controller->zoneValues['MEDIA_ID'], $controller->readO, true, false, "cinemascope");
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 255, "", false, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 75);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('SOUS_TITRE'), 255, "", false, $controller->zoneValues['ZONE_TITRE2'], $controller->readO, 75);
        $return .= $controller->oForm->showSeparator();
        $return .= $controller->oForm->createLabel(t('VISUEL')." 2", "");
        $return .= $controller->oForm->createMedia($controller->multi."MEDIA_ID2", t('VISUEL'), true, "image", "", $controller->zoneValues['MEDIA_ID2'], $controller->readO, true, false, "cinemascope");
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE3", t('TITRE'), 255, "", false, $controller->zoneValues['ZONE_TITRE3'], $controller->readO, 75);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE4", t('SOUS_TITRE'), 255, "", false, $controller->zoneValues['ZONE_TITRE4'], $controller->readO, 75);
        return $return;
    }

}