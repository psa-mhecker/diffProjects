<?php
include_once (dirname(__FILE__) . '/Article.php');

class Cms_Content_Module_Geo extends Cms_Content_Module_Article
{

    public static function render (Pelican_Controller $controller)
    {
        $return = parent::render($controller);
        $googleKey = Pelican::$config['SITE']['INFOS']['DNS'][Pelican::$config['SITE']['INFOS']['SITE_URL']]['map_google'];
        $return .= $controller->oForm->createMap("CONTENT", t('Geolocation'), false, $googleKey, "", $controller->values['CONTENT_LATITUDE'], $controller->values['CONTENT_LONGITUDE']);
        $return .= $controller->oForm->createEditor("CONTENT_TEXT2", 'Info-bulle', false, $controller->values["CONTENT_TEXT2"], $controller->readO, true, "", 500, 200);
        return $return;
    }
}