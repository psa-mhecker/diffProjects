<?php

include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';


/**
 *
 */
class Cms_Page_Ndp_Pc52Apv extends Cms_Page_Ndp
{

    /**
     * @param Pelican_Controller $controller
     *
     * @return string
     */
    public static function render(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();

        self::jsCheckFilterOrApv($controller);

        $return  = $controller->oForm->createCheckboxAffichage(self::getConfigAffichage($controller));
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE", t('TITLE'), 120, "", false, $controller->zoneValues["ZONE_TITRE"], $controller->readO, 80, false, '', 'text', [], false, '');
        $return .= $controller->oForm->createInput($controller->multi."ZONE_TITRE2", t('NDP_FILTERS_TITLE'), 120, "", false, $controller->zoneValues["ZONE_TITRE2"], $controller->readO, 80, false, '', 'text', [], false, '');
        $return .= $controller->oForm->createEditor($controller->multi."ZONE_TEXTE", t('NDP_TERMS_CONDITIONS'), true, $controller->zoneValues['ZONE_TEXTE'], $controller->readO, true, "", 650, 150);

        return $return;
    }

    /**
     * @param $bind
     *
     * @return array
     */
    public static function jsCheckFilterOrApv($controller)
    {
        $listApv = self::getListApv();

        $listFilter = self::getListFilter();

        if(!self::getWithoutFiltre() && count($listFilter) < 1){
            $controller->oForm->createJS("
                alert('".t('NDP_ERROR_NO_FILTER')."');
           ");
        }

        if(count($listApv) < 1){
            $controller->oForm->createJS("
                alert('".t('NDP_APV_NO_APV')."');
           ");
        }

        if((!self::getWithoutFiltre() && count($listFilter) < 1) || count($listApv) < 1){
            $controller->oForm->createJS("
                return false;
           ");
        }

        return $listApv;
    }

    public static function getListApv()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
        $aBind[":LANGUE_ID"] = $_SESSION[APP]['LANGUE_ID'];

        $query = "SELECT
				ID as \"id\",
				LABEL as \"lib\"
				FROM
				#pref#_after_sale_services
				where
				SITE_ID = :SITE_ID
				AND LANGUE_ID = :LANGUE_ID
				ORDER BY ID";

        return $oConnection->queryTab($query, $aBind);
    }

    public function getListFilter()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
        $aBind[":LANGUE_ID"] = $_SESSION[APP]['LANGUE_ID'];

        $query = "SELECT
				ID as \"id\",
				LABEL as \"lib\"
				FROM
				#pref#_filter_after_sale_services
				where
				SITE_ID = :SITE_ID
				AND LANGUE_ID = :LANGUE_ID";

        return $oConnection->queryTab($query, $aBind);
    }

    /**
     * @param $bind
     *
     * @return array
     */
    public static function getWithoutFiltre()
    {
        self::$con = Pelican_Db::getInstance();

        $aBind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];

        $query = "SELECT
				FILTER_AFTER_SALE_SERVICE
				FROM
				#pref#_site
				where
				SITE_ID = :SITE_ID";

        return self::$con->queryItem($query, $aBind);
    }
    /**
     * @param Pelican_Controller $controller
     */
    public static function save(Pelican_Controller $controller)
    {
        self::$con = Pelican_Db::getInstance();
        parent::save();
    }
}
