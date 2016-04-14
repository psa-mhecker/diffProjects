<?php

class Cms_Page_Service_Twitter extends Cms_Page_Module
{
    public static function render(Pelican_Controller $controller)
    {
        //exemple $return = $controller->oForm->createEditor($controller->multi . "ZONE_TEXTE", t('Content'), false, $controller->zoneValues["ZONE_TEXTE"], $controller->readO, true, "", 500, 150);


        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        $oConnection = Pelican_Db::getInstance();

         // {CODE}

         parent::save();
    }
}
