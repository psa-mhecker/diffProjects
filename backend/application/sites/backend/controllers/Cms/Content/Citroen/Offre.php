<?php
class Cms_Content_Citroen_Offre extends Cms_Content_Module
{
    public static function render(Pelican_Controller $controller)
    {
        $return = $controller->oForm->createMedia("MEDIA_ID", t('Image'), false, "image", "", $controller->values ["MEDIA_ID"], $controller->readO);
        $return .= $controller->oForm->createEditor("CONTENT_TEXT", t('Main text'), false, $controller->values ["CONTENT_TEXT"], $controller->readO, true, "", 650, 300);

        return $return;
    }
}
