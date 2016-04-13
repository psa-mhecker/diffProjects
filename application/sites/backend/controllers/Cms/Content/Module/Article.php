<?php

class Cms_Content_Module_Article extends Cms_Content_Module
{

    /*public static $decacheBack = array(
        'test' , 
        'CONTENT_ID'
    );

    public static $decachePublication = array(
        'test' , 
        'CONTENT_ID'
    );*/

    public static function render (Pelican_Controller $controller)
    {
        $return = $controller->oForm->createEditor("CONTENT_TEXT", t('Main text'), false, $controller->values["CONTENT_TEXT"], $controller->readO, true, "", 650, 300);
        return $return;
    }

}