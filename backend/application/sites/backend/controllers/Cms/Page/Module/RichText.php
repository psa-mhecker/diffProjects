<?php

class Cms_Page_Module_RichText extends Cms_Page_Module
{
    public static function render(Pelican_Controller $controller)
    {
        $return = $controller->oForm
            ->createEditor($controller->multi."ZONE_TEXTE", t('Content'), false, $controller->zoneValues["ZONE_TEXTE"], $controller->readO, true, "", 500, 150);
        if ($controller->form_action == Pelican_Db::DATABASE_INSERT) {
            $controller->zoneValues['ZONE_PARAMETERS'] = 1;
        }
        /*$return .= $controller->oForm
            ->createRadioFromList($controller->multi . "ZONE_PARAMETERS", "Afficher le bloc en Front Office", array(
            "1" => "oui" ,
            "0" => "non"
        ), $controller->zoneValues['ZONE_PARAMETERS'], true, $controller->readO, "h");
        */
        return $return;
    }
}
