<?php

include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');

class Cms_Page_Citroen_4Colonnes extends Cms_Page_Citroen
{

    public static function render(Pelican_Controller $controller)
    {
        //exemple $return = $controller->oForm->createEditor($controller->multi . "ZONE_TEXTE", t('Content'), false, $controller->zoneValues["ZONE_TEXTE"], $controller->readO, true, "", 500, 150);
        // {FORM}

        return $return;
    }

       public static function save(Pelican_Controller $controller)
    {
        $oConnection = Pelican_Db::getInstance();

         // {CODE}

         parent::save();
    }
}