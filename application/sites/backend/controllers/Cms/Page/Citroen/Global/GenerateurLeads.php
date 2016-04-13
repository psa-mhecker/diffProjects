<?php
include_once(Pelican::$config['CONTROLLERS_ROOT'] . '/Cms/Page/Citroen.php');
class Cms_Page_Citroen_Global_GenerateurLeads extends Cms_Page_Citroen
{

    public static function render(Pelican_Controller $controller)
    {
        $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $sSQL = "
            select
                BARRE_OUTILS_ID as id,
                BARRE_OUTILS_LABEL as lib
            from #pref#_barre_outils
            where SITE_ID = " . $_SESSION[APP]['SITE_ID'] . "
			and LANGUE_ID  = " . $_SESSION[APP]['LANGUE_ID'] . "
            order by BARRE_OUTILS_ID asc";
        
        $aSelectedValues  = explode('|', $controller->zoneValues['ZONE_PARAMETERS']);
        //$return .= $controller->oForm->createComboFromSql($oConnection, $controller->multi."RESEAUX_SOCIAUX", t('LEADS'), $sSQL, $aSelectedValues, true, $controller->readO, 5, true, 200, false, false, "", "", $aBind);
        $return = $controller->oForm->createAssocFromSql($oConnection, $controller->multi."ZONE_PARAMETERS", t('LEADS'), $sSQL, $aSelectedValues, true, true, $controller->readO, 5, 200, false, false, "", $aBind, 'ordre', false, false);
        
        $sJS = "
            if ($('select#" . $controller->multi . "RESEAUX_SOCIAUX option').length>5) {
                alert('" . t('RESEAUX_SOCIAUX_MAX', 'js') . "');
                return false;
            }
        "; 
        $return .= $controller->oForm->createJS($sJS);
        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        if (Pelican_Db::$values['ZONE_PARAMETERS']) {
            Pelican_Db::$values['ZONE_PARAMETERS'] = implode('|', Pelican_Db::$values['ZONE_PARAMETERS']);
        }
        parent::save();
    }

}