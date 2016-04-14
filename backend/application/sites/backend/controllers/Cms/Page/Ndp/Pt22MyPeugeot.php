<?php

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';


/**
 *
 */
class Cms_Page_Ndp_Pt22MyPeugeot extends Cms_Page_Ndp
{

    const CONT_MY_PEUGEOT        = "cont_my_peugeot";
    const ENABLE                 = 1;
    const DISABLE                = -2;
    /**
     * @param Cms_Page_Controller $controller
     *
     * @return string
     */
    public static function render(Cms_Page_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();
        $return  = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));

        $return .= self::createMyPeugeot($controller);

        return $return;
    }

    /**
     *
     * return string
     * @param Cms_Page_Controller $controller
     *
     * @return string
     */
    public function createMyPeugeot(Cms_Page_Controller $controller)
    {
        $type = self::CONT_MY_PEUGEOT;
        $targetsAffichage = array(self::DISABLE => t('NDP_DESACTIVE'), self::ENABLE => t('NDP_ACTIVE'));
        $jsContainerAffichage = self::addJsContainerRadio($type);
        self::setDefaultValueTo($controller->zoneValues, 'ZONE_PARAMETERS', self::DISABLE);
        $form = $controller->oForm->createRadioFromList(
            $controller->multi."ZONE_PARAMETERS",
            t('NDP_MY_PEUGEOT'),
            $targetsAffichage,
            $controller->zoneValues['ZONE_PARAMETERS'],
            true,
            $controller->readO,
            'h',
            false,
            $jsContainerAffichage
        );
        $form .= self::addHeadContainer(self::ENABLE, $controller->zoneValues['ZONE_PARAMETERS'], $type);

        $form .= $controller->oForm->createInput(
            $controller->multi."ZONE_URL",
            t('NDP_URL_WEB_ACCUEIL'),
            255,
            "internallink",
            true,
            $controller->zoneValues["ZONE_URL"],
            $controller->readO,
            100
        );
        $form .= self::addFootContainer();


        return $form;
    }


    /**
     * @param Cms_Page_Controller $controller
     */
    public static function save(Cms_Page_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();
        $saved = Pelican_Db::$values;
        if (Pelican_Db::$values['ZONE_PARAMETERS'] == self::DISABLE) {
            unset(Pelican_Db::$values['ZONE_URL']);
        }
        parent::save();
        Pelican_Db::$values = $saved;
    }
}
