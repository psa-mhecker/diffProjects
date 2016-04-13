<?php

class Cms_Page_Module_ImageRichText extends Cms_Page_Module
{

    public static function render(Pelican_Controller $controller)
    {
        $return = $controller->oForm->createMedia($controller->multi . "MEDIA_ID", t('Image'), false, "image", "", $controller->zoneValues["MEDIA_ID"], $controller->readO);
        $return .= $controller->oForm->createEditor($controller->multi . "ZONE_TEXTE", t('Content'), false, $controller->zoneValues["ZONE_TEXTE"], $controller->readO, true, "", 500, 150);
        return $return;
    }
}
?>