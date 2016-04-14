<?php

class Cms_Page_Module_ImageTitle extends Cms_Page_Module
{
    public static function render(Pelican_Controller $controller)
    {
        $return = $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 150, "", true, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 70);

        return $return;
    }
}
