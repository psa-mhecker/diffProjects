<?php

class Cms_Page_Module_Text extends Cms_Page_Module
{

    public static function render(Pelican_Controller $controller)
    {
        $return = $controller->oForm->createInput($controller->multi . "ZONE_TITRE", t('TITRE'), 150, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 70);
        $return .= $controller->oForm->createInput($controller->multi . "ZONE_TITRE2", t('Subtitle'), 150, "", false, $controller->zoneValues["ZONE_TITRE2"], $controller->readO, 70);
        return $return;
    }
}