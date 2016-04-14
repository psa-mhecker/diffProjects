<?php

/**
 * Fichier de Pelican_Cache : StickyBar.
 */
class Frontend_Citroen_StickyBar extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */

    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':PAGE_ID'] = $this->params[0];
        $aBind[':PAGE_PARENT_ID'] = $this->params[1];
        $aBind[':TEMPLATE_PAGE_ID'] = $this->params[2];
        $aBind[':LANGUE_ID'] = $this->params[3];
        $sVersion = ($this->params[3]) ? $this->params[4] : 'CURRENT';
        $aBind[':PAGE_STATUS'] = 1;
        $aBind[':ZONE_ID'] = Pelican::$config['ZONE']['STICKYBAR'];
        $aBind[':ZONE_ID2'] = Pelican::$config['ZONE']['STICKYBAR_PROMO'];
        $aBind[':AREA_ZONE_DYNAMIQUE'] = Pelican::$config['AREA']['DYNAMIQUE'];
        // Vérification de la présence d'une StickyBar (hors zone dynamique) dans la page courante
        $sSQL = "
            select 1
            from #pref#_zone_template
            where TEMPLATE_PAGE_ID = :TEMPLATE_PAGE_ID
            and (ZONE_ID = :ZONE_ID or ZONE_ID = :ZONE_ID2)
			and AREA_ID != :AREA_ZONE_DYNAMIQUE";
        $bZone = $oConnection->queryItem($sSQL, $aBind);
        // Vérification de la présence d'une StickyBar (dans une zone dynamique) dans la page courante
        $sSQL = "
			select 1
			from #pref#_page p
			inner join #pref#_page_version pv
				on (pv.PAGE_ID = p.PAGE_ID
					and pv.LANGUE_ID = p.LANGUE_ID
					and pv.PAGE_VERSION = p.PAGE_".$sVersion."_VERSION)
			inner join #pref#_page_multi_zone pmz
				on (pmz.PAGE_ID = pv.PAGE_ID
					and pmz.LANGUE_ID = pv.LANGUE_ID
					and pmz.PAGE_VERSION = pv.PAGE_VERSION)
			where p.PAGE_ID = :PAGE_ID
            and p.PAGE_STATUS = :PAGE_STATUS
            and pv.STATE_ID = 4
			and (pmz.ZONE_ID = :ZONE_ID or pmz.ZONE_ID = :ZONE_ID2)";
        $bZoneDynamique = $oConnection->queryItem($sSQL, $aBind);
        if ($bZone || $bZoneDynamique) {
            // Récupération du TEMPLATE_PAGE_ID de la page parente
            $sSQL = "
                select pv.TEMPLATE_PAGE_ID
                from #pref#_page p
                inner join #pref#_page_version pv
                on (p.PAGE_ID = pv.PAGE_ID
                    and p.LANGUE_ID = pv.LANGUE_ID
                    and p.PAGE_".$sVersion."_VERSION = pv.PAGE_VERSION)
                where p.PAGE_STATUS = :PAGE_STATUS
                and p.PAGE_ID = :PAGE_PARENT_ID
                and p.LANGUE_ID = :LANGUE_ID";
            $aPageParent = $oConnection->queryRow($sSQL, $aBind);
            $aBind[':TEMPLATE_PAGE_ID'] = $aPageParent['TEMPLATE_PAGE_ID'];
            // Vérification de la présence d'une StickyBar (hors zone dynamique) dans la page parente
            $sSQL = "
                select 1
                from #pref#_zone_template
                where TEMPLATE_PAGE_ID = :TEMPLATE_PAGE_ID
                and (ZONE_ID = :ZONE_ID or ZONE_ID = :ZONE_ID2)
				and AREA_ID != :AREA_ZONE_DYNAMIQUE";
            $bZone = $oConnection->queryItem($sSQL, $aBind);
            // Vérification de la présence d'une StickyBar (dans une zone dynamique) dans la page parente
            $sSQL = "
				select 1
				from #pref#_page p
				inner join #pref#_page_version pv
					on (pv.PAGE_ID = p.PAGE_ID
						and pv.LANGUE_ID = p.LANGUE_ID
						and pv.PAGE_VERSION = p.PAGE_".$sVersion."_VERSION)
				inner join #pref#_page_multi_zone pmz
					on (pmz.PAGE_ID = pv.PAGE_ID
						and pmz.LANGUE_ID = pv.LANGUE_ID
						and pmz.PAGE_VERSION = pv.PAGE_VERSION)
				where p.PAGE_STATUS = :PAGE_STATUS
                and p.PAGE_ID = :PAGE_PARENT_ID
				and (pmz.ZONE_ID = :ZONE_ID or pmz.ZONE_ID = :ZONE_ID2)";
            $bZoneDynamique = $oConnection->queryItem($sSQL, $aBind);
            // La page est une page N+1
            if ($bZone || $bZoneDynamique) {
                $aBind[':PAGE_PARENT_ID'] = $this->params[1];
            }
            // La page est une page N
            else {
                $aBind[':PAGE_PARENT_ID'] = $this->params[0];
                $aBind[':TEMPLATE_PAGE_ID'] = $this->params[2];
            }

            $aBind[':STATE_TRASH'] = Pelican::$config["CORBEILLE_STATE"];

            if (Pelican::$config['STICKYBAR'][$aBind[':TEMPLATE_PAGE_ID']]) {
                $sSQL = "
                    select distinct
                        p.PAGE_ID,
                        pv.PAGE_TITLE,
                        pv.PAGE_TITLE_BO,
                        pv.PAGE_CLEAR_URL,
                        pv.TEMPLATE_PAGE_ID,
                        LENGTH(p.PAGE_PATH) - LENGTH(REPLACE(p.PAGE_PATH, '#', '')) as level
                    from #pref#_page p
                    inner join #pref#_page_version pv
                        on (p.PAGE_ID = pv.PAGE_ID
                            and p.PAGE_".$sVersion."_VERSION = pv.PAGE_VERSION
                            and p.LANGUE_ID = pv.LANGUE_ID)
                    left join #pref#_zone_template zt
                        on(pv.TEMPLATE_PAGE_ID = zt.TEMPLATE_PAGE_ID)
                    left join #pref#_page_multi_zone pmz
                        on (pmz.PAGE_ID = pv.PAGE_ID
                            and pmz.LANGUE_ID = pv.LANGUE_ID
                            and pmz.PAGE_VERSION = pv.PAGE_VERSION)
                    where (
                        (p.PAGE_PARENT_ID = :PAGE_PARENT_ID AND pv.TEMPLATE_PAGE_ID in (".implode(',', Pelican::$config['STICKYBAR'][$aBind[':TEMPLATE_PAGE_ID']])."))
                        or (p.PAGE_ID = :PAGE_PARENT_ID AND pv.TEMPLATE_PAGE_ID = :TEMPLATE_PAGE_ID)
                    )
                    and p.LANGUE_ID = :LANGUE_ID
                    and p.PAGE_STATUS = :PAGE_STATUS
                    and (
                        (zt.AREA_ID != :AREA_ZONE_DYNAMIQUE and (zt.ZONE_ID = :ZONE_ID or zt.ZONE_ID = :ZONE_ID2))
                        or
                        (pmz.ZONE_ID = :ZONE_ID or pmz.ZONE_ID = :ZONE_ID2)
                    )
                    and pv.STATE_ID != :STATE_TRASH
                    order by level asc, p.PAGE_ORDER asc";
                $aResults = $oConnection->queryTab($sSQL, $aBind);
                if ($aResults) {
                    if ($aResults[0]['TEMPLATE_PAGE_ID'] == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']) {
                        $aResults[0]['PAGE_TITLE_BO'] = t('MODELE_EN_DETAIL');
                        $aResults[0]['PAGE_TITLE'] = t('MODELE_EN_DETAIL');
                    } else {
                        $aResults[0]['PAGE_TITLE_BO'] = t('OVERVIEW');
                        $aResults[0]['PAGE_TITLE'] = t('OVERVIEW');
                    }
                }
            }
        }
        $this->value = $aResults;
    }
}
