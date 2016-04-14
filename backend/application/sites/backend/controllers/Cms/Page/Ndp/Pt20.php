<?php

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Multi/Factory.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/Factory.php';

/**
 *
 */
class Cms_Page_Ndp_Pt20 extends Cms_Page_Ndp
{

    /**
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();

        $controller->zoneValues['ZONE_WEB'] = 1;
        $controller->zoneValues['ZONE_WEB_SHOW'] = false;
        $controller->zoneValues['ZONE_MOBILE'] = 1;
        $controller->zoneValues['ZONE_MOBILE_SHOW'] = false;

        // master page
        $return  = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        $return .= $controller->oForm->createLabel(t('NDP_MASTERPAGE'));

        return $return;
    }

}
