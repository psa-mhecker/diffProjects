<?php

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';

/**
 *
 */
class Cms_Page_Ndp_Pt2PlanDuSite extends Cms_Page_Ndp
{

    /**
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();
        $return  = $controller->oForm->createCheckBoxFromList($controller->multi."ZONE_LANGUETTE", t('AFFICHER'), array(1 => ""), $controller->zoneValues["ZONE_LANGUETTE"]);
        $return .= $controller->oForm->createInput($controller->multi."ZONE_LANGUETTE_TEXTE", t('NDP_LABEL_LANGUETTE'), 50, "", true, $controller->zoneValues["ZONE_LANGUETTE_TEXTE"], $controller->readO, 70, false, '', 'text', [], false, '', "50 ".t('NDP_MAX_CAR'));
        $return .= $controller->oForm->createInput($controller->multi."ZONE_LABEL", t('NDP_LABEL_SITEMAP'), 50, "", true, $controller->zoneValues["ZONE_LABEL"], $controller->readO, 70, false, '', 'text', [], false, '', "50 ".t('NDP_MAX_CAR'));

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
