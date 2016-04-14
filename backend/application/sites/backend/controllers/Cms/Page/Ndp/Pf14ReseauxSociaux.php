<?php
/**
 * Tranche PF14 - Reseaux Sociaux.
 *
 * @author Laurent Franchomme <laurent.franchomme@businessdecision.com>
 *
 * @since 19/02/2012
 */
require_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';

/**
 * Cms_Page_Ndp_Pf14ReseauxSociaux.
 */
class Cms_Page_Ndp_Pf14ReseauxSociaux extends Cms_Page_Ndp
{
    const MIN_LIMIT = 3;

    /**
     * Render.
     *
     * @param Pelican_Controller $controller
     *
     * @return string $return
     */
    public static function render(Pelican_Controller $controller)
    {
        $controller->zoneValues['ZONE_MOBILE_SHOW'] = false;
        $return  = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        $return .= $controller->oForm->createInput(
            $controller->multi.'ZONE_TITRE',
            t('TITRE'),
            120,
            '',
            false,
            $controller->zoneValues['ZONE_TITRE'],
            $controller->readO,
            75
        );

        $sqlData = 'SELECT RESEAU_SOCIAL_ID, RESEAU_SOCIAL_LABEL FROM #pref#_reseau_social
            WHERE SITE_ID = '.$_SESSION[APP]['SITE_ID'].' AND LANGUE_ID = '.$_SESSION[APP]['LANGUE_ID'].'
            ORDER BY RESEAU_SOCIAL_LABEL';
        $aSelected = array();
        if ($controller->zoneValues['ZONE_PARAMETERS'] != '') {
            $aSelected = explode('#', $controller->zoneValues['ZONE_PARAMETERS']);
        }

        $return .= $controller->oForm->createAssocFromSql(
            '',
            $controller->multi.'ZONE_PARAMETERS',
            t('NDP_RESEAUX_SOCIAUX'),
            $sqlData,
            $aSelected,
            true,
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
            false
        );
        $return .= $controller->oForm->createJS("
           var selected = $('#".$controller->multi."ZONE_PARAMETERS option').size();
           if (selected != ".self::MIN_LIMIT.") {
               alert('".t('NDP_3_SOCIAL_NEEDED')."');
               fwFocus(eval(src".$controller->multi."ZONE_PARAMETERS));

               return false;
           }
       ");

        return $return;
    }

    /**
     * Save.
     *
     * @return none
     */
    public static function save()
    {
        $values = Pelican_Db::$values;
        Pelican_Db::$values['ZONE_MOBILE'] = 0;
        if (!empty(Pelican_Db::$values['ZONE_PARAMETERS'])) {
            Pelican_Db::$values['ZONE_PARAMETERS'] = implode('#', Pelican_Db::$values['ZONE_PARAMETERS']);
        }
        parent::save();
        Pelican_Db::$values = $values;
    }
}
