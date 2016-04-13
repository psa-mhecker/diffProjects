<?php

include_once(Pelican::$config['CONTROLLERS_ROOT'] . "/Cms/Page/Module.php");
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');

class Cms_Page_Citroen_Javascript extends Cms_Page_Citroen {

    public static function render(Pelican_Controller $controller) {

        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= $controller->oForm->createTextArea($controller->multi . "ZONE_TEXTE", t('Tag http'), false, $controller->zoneValues["ZONE_TEXTE"], 50000, $controller->readO, 12, 90, false, "", false);
        $return .= $controller->oForm->createTextArea($controller->multi . "ZONE_TEXTE2", t('Tag https'), false, $controller->zoneValues["ZONE_TEXTE2"], 50000, $controller->readO, 12, 90, false, "", false);
        return $return;
    }

    public static function save() {
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
        Pelican_Cache::clean("Frontend_Citroen_Javascript");
    }

}

?>
