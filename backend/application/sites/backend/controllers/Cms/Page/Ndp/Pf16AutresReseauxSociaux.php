<?php
/**
 * Tranche PC - Autres Reseaux Sociaux
 *
 * @package Pelican_BackOffice
 * @subpackage Tranche
 * @author Kevin Vignon <kevin.vignon@businessdecision.com>
 * @since 13/03/2015
 */
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';

class Cms_Page_Ndp_Pf16AutresReseauxSociaux extends Cms_Page_Ndp
{
    const MAX_OTHER_SOCIAL = 6;

    public static function render(Pelican_Controller $controller)
    {
        $return = '';

        $controller->zoneValues['ZONE_WEB']         = 1;
        $controller->zoneValues['ZONE_WEB_READO']   = true;
        $controller->zoneValues['ZONE_MOBILE_SHOW'] = false;
        $return .= $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));

        $return .= $controller->oForm->createInput(
            $controller->multi."ZONE_TITRE", t('TITLE'), 50, "", true,
            $controller->zoneValues["ZONE_TITRE"], $controller->read0, 100, false, '', 'text', [], false, '', "50 ".t('NDP_MAX_CAR')
        );

        $return .= $controller->oForm->createTextArea(
            $controller->multi.'ZONE_TEXTE', t('NDP_COLONNE').' 1', true,
            $controller->zoneValues['ZONE_TEXTE'], 250, $controller->readO, 3,
            70, false
        );

        $return .= $controller->oForm->createTextArea(
            $controller->multi.'ZONE_TEXTE2', t('NDP_COLONNE').' 2', true,
            $controller->zoneValues['ZONE_TEXTE2'], 250, $controller->readO, 3,
            70, false
        );

        $sqlData   = 'SELECT RESEAU_SOCIAL_ID, RESEAU_SOCIAL_LABEL FROM #pref#_reseau_social
            WHERE SITE_ID = '.$_SESSION[APP]['SITE_ID'].' AND LANGUE_ID = '.$_SESSION[APP]['LANGUE_ID'].'
            ORDER BY RESEAU_SOCIAL_LABEL';
        $aSelected = array();
        if ($controller->zoneValues['ZONE_PARAMETERS'] != '') {
            $aSelected = explode('#', $controller->zoneValues['ZONE_PARAMETERS']);
        }

        $return .= $controller->oForm->createAssocFromSql(
            '', $controller->multi.'ZONE_PARAMETERS', t('NDP_RESEAUX_SOCIAUX'),
            $sqlData, $aSelected, true, true, $controller->readO, 5, 200, false,
            '', '', '',
            true, // not real field, just for access to order function
            false, 0, self::MAX_OTHER_SOCIAL
        );

        return $return;
    }

    /**
     * Save.
     *
     * @return none
     */
    public static function save()
    {
        $oldValues = Pelican_Db::$values;
        Pelican_Db::$values['ZONE_WEB'] = 1;
        Pelican_Db::$values['ZONE_MOBILE'] = 0;
        if (!empty(Pelican_Db::$values['ZONE_PARAMETERS'])) {
            Pelican_Db::$values['ZONE_PARAMETERS'] = implode('#', Pelican_Db::$values['ZONE_PARAMETERS']);
        }
        parent::save();
        Pelican_Db::$values = $oldValues;
    }
}
