<?php

class Cms_Page_Module_ImageLink extends Cms_Page_Module
{
    public static function render(Pelican_Controller $controller)
    {
        $return = $controller->oForm->createMedia($controller->multi."MEDIA_ID", t('Image'), false, "image", "", $controller->zoneValues["MEDIA_ID"], $controller->readO);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('LINK'), 255, "internallink", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 50, false);

        return $return;
    }
}
