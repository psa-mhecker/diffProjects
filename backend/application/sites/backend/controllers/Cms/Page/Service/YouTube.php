<?php

class Cms_Page_Service_YouTube extends Cms_Page_Module
{
    public static function render(Pelican_Controller $controller)
    {
        $return = $controller->oForm->createInput($controller->multi."ZONE_TITRE", "Id d'une vidéo", 150, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 70);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", "Vidéo d'un utilisateur", 150, "", false, $controller->zoneValues["ZONE_TITRE2"], $controller->readO, 70);

        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        $oConnection = Pelican_Db::getInstance();

         // {CODE}

         parent::save();
    }
}
