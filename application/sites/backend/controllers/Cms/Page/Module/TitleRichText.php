<?php

/**
 * Bloc : titre, contenu riche, image
 *
 * @package Pelican_BackOffice
 * @subpackage Bloc
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 17/02/2006
 */

class Cms_Page_Module_TitleRichText extends Cms_Page_Module
{

    public static function render(Pelican_Controller $controller)
    {
        $return = $controller->oForm->createInput($controller->multi . "ZONE_TITRE", t('TITRE'), 150, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 70);
        $return .= $controller->oForm->createEditor($controller->multi . "ZONE_TEXTE", t('Content'), false, $controller->zoneValues["ZONE_TEXTE"], $controller->readO, true, "", 500, 150);
        return $return;
    }
}
?>