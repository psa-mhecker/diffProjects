<?php

/**
 * Fichier de Pelican_Cache : renvois les pages promotions liés au vehicule
 * @package Cache
 * @param 0 SITE_ID	 ID du site
 * @param 1 LANGUE_ID Langue du site
 * @param 2 PAGE_ZONE_MULTI_LABEL5 ID du véhicule
 * @param 3 CURRENT	 par default
 */
class Frontend_Citroen_PagesPromotionParVehicule extends Pelican_Cache
{

	var $duration = DAY;

	/*
	 * Valeur ou objet a mettre en Pelican_Cache
	 */

	function getValue()
	{
		$oConnection = Pelican_Db::getInstance();
		$aBind[':SITE_ID'] = $this->params[0];
		$aBind[':LANGUE_ID'] = $this->params[1];
		$aBind[':PAGE_ZONE_MULTI_LABEL5'] = $this->params[2];
		$sVersion = ($this->params[3]) ? $this->params[3] : 'CURRENT';
		$aBind[':TEMPLATE_PAGE_ID'] = Pelican::$config['TEMPLATE_PAGE']['DETAIL_PROMOTION'];
		$aBind[':ZONE_ID'] = Pelican::$config['ZONE']['PROMOTION'];
		$sSQL = "
			select distinct p.PAGE_ID
			from #pref#_page p
			inner join #pref#_page_version pv
				on (pv.PAGE_ID = p.PAGE_ID
					and pv.PAGE_VERSION = p.PAGE_" . $sVersion . "_VERSION
					and pv.LANGUE_ID = p.LANGUE_ID
				)
			inner join #pref#_zone_template zt
				on (zt.TEMPLATE_PAGE_ID = pv.TEMPLATE_PAGE_ID
					and zt.ZONE_ID = :ZONE_ID
				)
			inner join #pref#_page_zone pz
				on (pz.PAGE_ID = pv.PAGE_ID
					and pz.PAGE_VERSION = pv.PAGE_VERSION
					and pz.LANGUE_ID = pv.LANGUE_ID
					and pz.ZONE_TEMPLATE_ID = zt.ZONE_TEMPLATE_ID
				)
			inner join #pref#_page_zone_multi pzm
				on (pzm.PAGE_ID = pz.PAGE_ID
					and pzm.PAGE_VERSION = pz.PAGE_VERSION
					and pzm.LANGUE_ID = pz.LANGUE_ID
					and pzm.ZONE_TEMPLATE_ID = pz.ZONE_TEMPLATE_ID
				)
			where p.SITE_ID = :SITE_ID
			and p.LANGUE_ID = :LANGUE_ID
			and pv.TEMPLATE_PAGE_ID = :TEMPLATE_PAGE_ID
                        and p.PAGE_STATUS = 1
                        and pv.STATE_ID = 4
			and pzm.PAGE_ZONE_MULTI_LABEL5 = :PAGE_ZONE_MULTI_LABEL5";
		$this->value = $oConnection->queryTab($sSQL, $aBind);
	}

}
