<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_ConceptCars_VisuelCinematique extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        return $controller->oForm->createMedia($controller->multi."MEDIA_ID", t('VISUEL'), true, "image", "", $controller->zoneValues['MEDIA_ID'], $controller->readO, true, false);
    }

    public static function save()
    {
        parent::save();
        Pelican_Cache::clean('Frontend/Citroen/ConceptCars/Galerie');
    }
}
