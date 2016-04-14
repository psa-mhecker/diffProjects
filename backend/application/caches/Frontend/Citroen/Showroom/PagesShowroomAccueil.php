<?php
/**
 * Fichier de Pelican_Cache : PagesShowroomAccueil.
 */
class Frontend_Citroen_Showroom_PagesShowroomAccueil extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        //$aBind[':PAGE_ID'] = $this->params[0];
        $aBind[':ZONE_TEMPLATE_ID'] = Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL'];
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        $sSQL = "
				SELECT p.PAGE_ID,
				pv.PAGE_TITLE_BO,
				p.PAGE_ORDER,
				(select distinct PAGE_ORDER from #pref#_page where PAGE_ID = p.PAGE_PARENT_ID limit 1) as ORDER_PAGE_PARENT
				FROM #pref#_page p
				INNER JOIN #pref#_page_version pv on (p.PAGE_ID=pv.PAGE_ID AND p.LANGUE_ID = pv.LANGUE_ID AND p.PAGE_CURRENT_VERSION=pv.PAGE_VERSION)
				WHERE
				pv.TEMPLATE_PAGE_ID = :ZONE_TEMPLATE_ID
				AND p.SITE_ID = :SITE_ID
				AND p.LANGUE_ID = :LANGUE_ID
				AND p.PAGE_STATUS = 1
				AND pv.STATE_ID = 4
				ORDER BY ORDER_PAGE_PARENT, p.PAGE_ORDER
        ";
        $aResults = $oConnection->queryTab($sSQL, $aBind);
        $this->value = $aResults;
    }
}
