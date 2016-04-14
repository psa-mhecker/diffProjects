<?php

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';

/**
 *
 */
class Cms_Page_Ndp_Pt2ReseauxSociaux extends Cms_Page_Ndp
{
    const MAX_LIMIT = 4;

    /**
     *
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();
        $return = $controller->oForm->createCheckBoxFromList($controller->multi."ZONE_AFFICHAGE", t('NDP_SHOW_BLOC'), array(1 => ""), $controller->zoneValues["ZONE_AFFICHAGE"]);
        $sqlData = 'SELECT RESEAU_SOCIAL_ID, RESEAU_SOCIAL_LABEL FROM #pref#_reseau_social
            WHERE SITE_ID = '.$_SESSION[APP]['SITE_ID'].' AND LANGUE_ID = '.$_SESSION[APP]['LANGUE_ID'].'
            ORDER BY RESEAU_SOCIAL_LABEL';
        $selected = array();
        if ($controller->zoneValues['ZONE_PARAMETERS'] != '') {
            $selected = explode('#', $controller->zoneValues['ZONE_PARAMETERS']);
        } 
        $return .= $controller->oForm->createAssocFromSql(
            '',
            $controller->multi.'ZONE_PARAMETERS',
            t('NDP_RESEAUX_SOCIAUX'),
            $sqlData,
            $selected,
            false,
            true,
            $controller->readO,
            5,
            200,
            false,
            '',
            '',
            '',
            true, // not real field, just for access to order function
            false,
            0,
            false
        );

        $return .= $controller->oForm->createJS("
            var selected = $('#".$controller->multi."ZONE_PARAMETERS option').size();
            if (selected > ".self::MAX_LIMIT.") {
                alert('".t('NDP_4_SOCIAL_MAX','js')."');
                fwFocus(eval(src".$controller->multi."ZONE_PARAMETERS));

                return false;
            }
        ");

        // if show checked zone_parameter will be mandatory
        $return .= $controller->oForm->createJS("
            var show = $('input[name=".$controller->multi."ZONE_AFFICHAGE]').is(':checked');
            if (show) {
                if (selected == 0) {
                    alert('".t('NDP_SOCIAL_MANDATORY')."');
                    fwFocus(eval(src".$controller->multi."ZONE_PARAMETERS));

                    return false;
                }
            }
        ");
        
        return $return;
    }

    /**
     *
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();       
        if (!empty(Pelican_Db::$values['ZONE_PARAMETERS'])) {
            Pelican_Db::$values['ZONE_PARAMETERS'] = implode('#', Pelican_Db::$values['ZONE_PARAMETERS']);
        }
        parent::save();
    }
}
