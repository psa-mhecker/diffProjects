<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');

class Cms_Page_Citroen_AnimationPointsForts extends Cms_Page_Citroen
{

    public static function render(Pelican_Controller $controller)
    {
        $return .= Backoffice_Form_Helper::getFormAffichage($controller);
        $return .= Backoffice_Form_Helper::getFormModeAffichage($controller);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 255, "", false, $controller->zoneValues['ZONE_TITRE'], $controller->readO, 75);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('SOUS_TITRE'), 255, "", false, $controller->zoneValues['ZONE_TITRE2'], $controller->readO, 75);
		$return .= $controller->oForm->createTextArea($controller->multi . "ZONE_TEXTE", t('CODE_HTML'), true, $controller->zoneValues["ZONE_TEXTE"], "", $controller->readO, 30, 100, false, "", false);
		
		$return .= Backoffice_Form_Helper::getLanguette($controller);
		 
        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
		
        Backoffice_Form_Helper::saveFormAffichage();
        parent::save();
    }

}