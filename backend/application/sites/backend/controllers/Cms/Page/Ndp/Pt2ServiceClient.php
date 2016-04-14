<?php

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';

/**
 *
 */
class Cms_Page_Ndp_Pt2ServiceClient extends Cms_Page_Ndp
{

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();
        $return = $controller->oForm->createCheckBoxFromList($controller->multi."ZONE_AFFICHAGE", t('AFFICHER'), array(1 => ""), $controller->zoneValues["ZONE_AFFICHAGE"]);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITLE'), 50, "", true, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 70, false, '', 'text', [], false, '', "50 ".t('NDP_MAX_CAR'));
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('PHONE'), 50, "", true, $controller->zoneValues["ZONE_TITRE2"], $controller->readO, 50);
        
        return $return;
    }

    /**
     *
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();        
        parent::save();
    }
}
