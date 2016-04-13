<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_Global_Panoramique extends Cms_Page_Citroen
{
    public static function render(Pelican_Controller $controller)
    {
        $return = $controller->oForm->createMedia($controller->multi . "MEDIA_ID", t ( 'MEDIA' ), true, "image", "", $controller->zoneValues["MEDIA_ID"], $controller->readO, true, false, 'grand_visuel');
        
        //Pour le cas du gabarit pre-home
        if($controller->zoneValues["TEMPLATE_PAGE_ID"] == Pelican::$config['TEMPLATE_PRE_HOME']){
            $return .= $controller->oForm->createCheckBoxFromList($controller->multi . "ZONE_PARAMETERS", t('AFFICHER_TITRE_LONG'), array(1 => ""), $controller->zoneValues['ZONE_PARAMETERS'], false, $controller->readO);
        }
        
        return $return;
    }
}