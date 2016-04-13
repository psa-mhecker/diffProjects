<?php
/**
 * Fichier de Pelican_Cache : ExpandGamme
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_ExpandGamme extends Pelican_Cache
{

    var $duration = DAY;
    
    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        $sVersion = ($this->params[2]) ? $this->params[2] : "CURRENT";
        $aBind[':ZONE_ID'] = Pelican::$config['ZONE']['EXPANDGAMME'];
        $sSQL = "
            select pz.*
            from psa_page p
            inner join psa_page_version pv
                on (p.PAGE_ID = pv.PAGE_ID
                    and p.LANGUE_ID = pv.LANGUE_ID
                    and p.PAGE_" . $sVersion . "_VERSION = pv.PAGE_VERSION)
            inner join psa_zone_template zt
                on (zt.TEMPLATE_PAGE_ID = pv.TEMPLATE_PAGE_ID)
            inner join psa_page_zone pz
                on (pz.PAGE_ID = pv.PAGE_ID
                    and pz.LANGUE_ID = pv.LANGUE_ID
                    and pz.PAGE_VERSION = pv.PAGE_VERSION
                    and pz.ZONE_TEMPLATE_ID = zt.ZONE_TEMPLATE_ID)
            where zt.ZONE_ID = :ZONE_ID
            and p.SITE_ID = :SITE_ID
            and p.PAGE_STATUS = 1
            and pv.STATE_ID = 4
            and p.LANGUE_ID = :LANGUE_ID";
        $aResults = $oConnection->queryRow($sSQL, $aBind);
        $aValues = array();
        if ($aResults) {
            $aValues['TITLE_TAB_C_AND_DS'] = $aResults['ZONE_TITRE'];
            $aValues['SHOW_PRICE'] = $aResults['ZONE_PARAMETERS'];
            $aValues['CACHER_DS'] = $aResults['ZONE_TITRE9'];
        }
        $this->value = $aValues;
    }
}
