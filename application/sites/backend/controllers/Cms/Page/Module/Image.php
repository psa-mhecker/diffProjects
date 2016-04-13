<?php

class Cms_Page_Module_Image extends Cms_Page_Module
{

    public static function render(Pelican_Controller $controller)
    {
        $return = $controller->oForm->createMedia($controller->multi . "MEDIA_ID", t('Image'), false, "image", "", $controller->zoneValues["MEDIA_ID"], $controller->readO);
        return $return;
    }
}