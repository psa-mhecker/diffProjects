<?php
/**
 * Fichier de Pelican_Cache : CTA
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_Cta extends Pelican_Cache {

    var $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance ();
        //$aBind[':PAGE_ID'] = $this->params[0];
        $aBind[':ZONE_TEMPLATE_ID'] = $this->params[0];
        $aBind[':SITE_ID'] = $this->params[1];
        $aBind[':LANGUE_ID'] = $this->params[2];
        $sSQL = "
            select *
            from #pref#_page_zone_multi pzm
            inner join #pref#_page p
            on (p.PAGE_ID = pzm.PAGE_ID
                and p.PAGE_CURRENT_VERSION = pzm.PAGE_VERSION
            )
            where p.SITE_ID = :SITE_ID
            and p.LANGUE_ID = :LANGUE_ID
            and pzm.ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
            order by PAGE_ZONE_MULTI_ID asc
        ";
        $aResults = $oConnection->queryTab($sSQL, $aBind);
        $this->value = $aResults;
    }
}