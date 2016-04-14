<?php

class Cms_Page_Module_FlashPlayer extends Cms_Page_Module
{
    public static function render(Pelican_Controller $controller)
    {
        $return = $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITRE'), 150, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 70);
        $return .= $controller->oForm->createMedia($controller->multi."MEDIA_ID", t('POPUP_MEDIA_FLASH'), false, "flash", "", $controller->zoneValues["MEDIA_ID"], $controller->readO, true, false);

        if ($format) {
            $arr = array(
                1 => "petite",
                2 => "grande",
            );
            $return .= $controller->oForm->_createBox($controller->multi."ZONE_PARAMETERS", t('Player size'), $arr, ($controller->zoneValues["ZONE_PARAMETERS"] ? $controller->zoneValues["ZONE_PARAMETERS"] : 1), false, $controller->readO, "h", "radio");
        } else {
            $return .= $controller->oForm->createHidden($controller->multi."ZONE_PARAMETERS", 1);
        }

        return $return;
    }
}
