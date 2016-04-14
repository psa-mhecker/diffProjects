<?php
class Cms_Page_Module_WebScrapping extends Cms_Page_Module
{
    public static function render(Pelican_Controller $controller)
    {
        $return = $controller->oForm->createInput($controller->multi."ZONE_PARAMETERS", t('LINK'), 255, "", false, $controller->values ["ZONE_PARAMETERS"], $controller->readO, 50, false);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", 'Alias', 255, "", false, $controller->values ["ZONE_TITRE"], $controller->readO, 50, false);
        $return .= $controller->oForm->createTextArea($controller->multi."ZONE_TEXTE", 'Contenu à supprimer', false, $controller->values ["ZONE_TEXTE"], "", $controller->readO, 10, 50);
        $return .= $controller->oForm->createLabel('Syntaxe', '#foo<br />.foo<br />img<br />a[title]');
        $return .= $controller->oForm->createTextArea($controller->multi."ZONE_TEXTE2", 'Surcharge CSS', false, $controller->values ["ZONE_TEXTE2"], "", $controller->readO, 10, 50);

        return $return;
    }
}
