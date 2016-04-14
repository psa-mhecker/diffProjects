<?php

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';


/**
 *
 */
class Cms_Page_Ndp_Pc95InterestedBy extends Cms_Page_Ndp
{
    
    /**
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();
        $return = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        $infoBulle = [
            'isIcon'  => true,
            'message' => t('NDP_MSG_PRECO_UPPERCASE_TITLE')
        ];
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITLE'), 60, "", true, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 60, false, '', 'text', [], false, $infoBulle, array('message'=> '60'.t('NDP_MAX_CAR')));
        
        $return .= $controller->oForm->createComment(t('NDP_MSG_ERROR_NO_SILHOUETTE'), array('noBold' => true, "idForLabel" => $controller->multi.self::idForComment));
        $return .= $controller->oForm->createComment(t('NDP_MSG_CHANGE_CAR_MOBILE'));
        $infoBulle = [
            'isIcon'  => true,
            'message' => t('NDP_MSG_REDIRECT_CAR_SELECTOR')
        ];
        $return .= $controller->oForm->createInput($controller->multi."ZONE_URL", t('URL'), 255, "internallink", true, $controller->zoneValues["ZONE_URL"], $controller->readO, 50, false, '', 'text', [], false, $infoBulle);
        $js = self::getJsForCheckingModel($controller);

        return $return.$js;
    }
}
