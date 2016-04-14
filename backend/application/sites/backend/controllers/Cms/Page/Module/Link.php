<?php
class Cms_Page_Module_Link extends Cms_Page_Module
{
    public static function render(Pelican_Controller $controller)
    {
        $return = $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('FORM_LABEL'), 150, "", false, $controller->values["ZONE_TITRE"], $controller->readO, 50);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_PARAMETERS", t('LINK'), 255, "internallink", false, $controller->values["ZONE_PARAMETERS"], $controller->readO, 50, false);

        return $return;
    }
}
