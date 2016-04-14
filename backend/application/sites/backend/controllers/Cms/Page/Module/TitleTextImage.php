<?php

/**
 * Bloc : titre, contenu riche, image.
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 17/02/2006
 */
class Cms_Page_Module_TitleTextImage extends Cms_Page_Module
{
    public static function render(Pelican_Controller $controller)
    {
        $return = $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 150, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 70);
        $return .= $controller->oForm->createTextArea($controller->multi."ZONE_TEXTE", t('TEXTE'), false, $controller->zoneValues["ZONE_TEXTE"], 16000, $controller->readO, 3, 75);
        $return .= $controller->oForm->createMedia($controller->multi."MEDIA_ID", t('Image'), false, "image", "", $controller->zoneValues["MEDIA_ID"], $controller->readO);

        return $return;
    }
}
