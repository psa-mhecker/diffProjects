<?php

/**
 * Fichier de Pelican_Cache : Page comprenant une tranche Contrats de service.
 *
 * @param 0 SITE_ID	 ID du site
 * @param 1 LANGUE_ID Langue du site
 * @param 2 CURRENT	 par default
 */
class Frontend_Citroen_PageContratsService extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet a mettre en Pelican_Cache
     */

    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        $sVersion = ($this->params[2]) ? $this->params[2] : 'CURRENT';
        $aBind[':ZONE_ID'] = Pelican::$config['ZONE']['CONTRATS_SERVICE'];
        $sSQL = "
			select pv.*
			from #pref#_page p
			inner join #pref#_page_version pv
				on (pv.PAGE_ID = p.PAGE_ID
					and pv.PAGE_VERSION = p.PAGE_".$sVersion."_VERSION
					and pv.LANGUE_ID = p.LANGUE_ID
				)
			inner join #pref#_page_multi_zone pmz
				on (pmz.PAGE_ID = pv.PAGE_ID
					and pmz.PAGE_VERSION = pv.PAGE_VERSION
					and pmz.LANGUE_ID = pv.LANGUE_ID
				)
			where p.SITE_ID = :SITE_ID
			and p.LANGUE_ID = :LANGUE_ID
			and pmz.ZONE_ID = :ZONE_ID
                        and p.PAGE_STATUS = 1
                        and pv.STATE_ID = 4";
        $this->value = $oConnection->queryRow($sSQL, $aBind);
    }
}
