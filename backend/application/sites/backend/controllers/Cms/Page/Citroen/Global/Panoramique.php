<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_Global_Panoramique extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $return = $controller->oForm->createMedia($controller->multi."MEDIA_ID", t('MEDIA'), true, "image", "", $controller->zoneValues["MEDIA_ID"], $controller->readO, true, false, 'grand_visuel');

        return $return;
    }
}
