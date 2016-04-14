<?php

/**
 * Fichier de Pelican_Cache : Menu Mon projet.
 */
class Frontend_Citroen_MonProjet_Menu extends Pelican_Cache
{
    public $duration = DAY;

    /**
     * Valeur ou objet à mettre en Pelican_Cache.
     *
     * @param int SITE_ID
     * @param int LANGUE_ID
     * @param string VERSION CURRENT/DRAFT
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        $sVersion = ($this->params[2]) ? $this->params[2] : "CURRENT";
        $aBind[':PAGE_STATUS'] = 1;
        $aBind[':ZONE_ID'] = Pelican::$config['ZONE']['MON_PROJET_MENU'];
        // Récupération du parametrage de la zone "Menu principal" du gabarit "Mon projet / Sélection"
        $aBind[':TEMPLATE_PAGE_ID'] = Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_SELECTION'];
        $sSQL = "
			select
                p.PAGE_ID,
				pv.PAGE_TITLE_BO,
				pv.PAGE_CLEAR_URL,
                pz.ZONE_PARAMETERS
			from #pref#_page p
			inner join #pref#_page_version pv
				on (pv.PAGE_ID = p.PAGE_ID
					and pv.PAGE_VERSION = p.PAGE_".$sVersion."_VERSION
					and pv.LANGUE_ID = p.LANGUE_ID)
			inner join #pref#_page_zone pz
				on (pz.PAGE_ID = pv.PAGE_ID
					and pz.PAGE_VERSION = pv.PAGE_VERSION
					and pz.LANGUE_ID = pv.LANGUE_ID)
			inner join #pref#_zone_template zt
				on (zt.ZONE_TEMPLATE_ID = pz.ZONE_TEMPLATE_ID)
			where p.SITE_ID = :SITE_ID
			and p.LANGUE_ID = :LANGUE_ID
			and p.PAGE_STATUS = :PAGE_STATUS
                        and pv.STATE_ID = 4
			and pv.TEMPLATE_PAGE_ID  = :TEMPLATE_PAGE_ID
			and zt.ZONE_ID = :ZONE_ID
			order by p.PAGE_ORDER asc";
        $aPageZone = $oConnection->queryTab($sSQL, $aBind);
        // Récupération du menu à partir des gabarits "Mon Projet"
        $sSQL = "
			select
				p.PAGE_ID,
				pv.PAGE_TITLE_BO,
				pv.PAGE_CLEAR_URL
			from #pref#_page p
			inner join #pref#_page_version pv
				on (pv.PAGE_ID = p.PAGE_ID
					and pv.PAGE_VERSION = p.PAGE_".$sVersion."_VERSION
					and pv.LANGUE_ID = p.LANGUE_ID)
			where p.SITE_ID = :SITE_ID
			and p.LANGUE_ID = :LANGUE_ID
			and p.PAGE_STATUS = :PAGE_STATUS
			and pv.TEMPLATE_PAGE_ID in (".Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_CONCESSIONS'].",".Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_PREFERENCES'].(($aPageZone[0]['ZONE_PARAMETERS']) ? ",".Pelican::$config['TEMPLATE_PAGE']['MON_PROJET_CONSEILS'] : "").")
			order by p.PAGE_ORDER asc";
        $aMenu = $oConnection->queryTab($sSQL, $aBind);
        $this->value = array_merge($aPageZone, $aMenu);
    }
}
