<?php

class Cms_Content_Module_News extends Cms_Content_Module
{
    public static function render(Pelican_Controller $controller)
    {
        //$controller->oForm->createHidden("form_module_name", "article");
        $return = $controller->oForm->createEditor("CONTENT_TEXT", t('Main text'), false, $controller->values["CONTENT_TEXT"], $controller->readO, true, "", 650, 300);

        return $return;
    }
}
