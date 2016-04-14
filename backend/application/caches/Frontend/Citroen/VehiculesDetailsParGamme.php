<?php

/**
 * Fichier de Pelican_Cache : VehiculesDetailsParGamme.
 */
class Frontend_Citroen_VehiculesDetailsParGamme extends Pelican_Cache
{
    public $duration = DAY;

    /**
     * Valeur ou objet à mettre en Pelican_Cache.
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        $aBind[':GAMME'] = $oConnection->strToBind($this->params[2]);
        $aBind[':TEMPLATE_PAGE_ID'] = Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL'];
        $aBind[':ZONE_TEMPLATE_ID'] = Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_ACCUEIL_SELECT_TEINTE'];
        $sSQL = "
			SELECT DISTINCT
				v.VEHICULE_ID,
				v.VEHICULE_LABEL,
				wsf.LCDV6,
				wsf.FINITION_CODE,
				wsf.FINITION_LABEL,
				emb.ENGINE_CODE,
				emb.LABEL as ENGINE_LABEL,
				wsf.V3D_LCDV,
				m.MEDIA_PATH
			FROM #pref#_page p
			INNER JOIN #pref#_page_version pv
			ON (p.PAGE_ID = pv.PAGE_ID
				AND p.PAGE_CURRENT_VERSION = pv.PAGE_VERSION
				AND p.LANGUE_ID = pv.LANGUE_ID)
			INNER JOIN #pref#_page_zone pz
			ON (pv.PAGE_ID = pz.PAGE_ID
				AND pv.PAGE_VERSION = pz.PAGE_VERSION
				AND pv.LANGUE_ID = pz.LANGUE_ID)
			INNER JOIN #pref#_vehicule v
				ON (v.VEHICULE_ID = pz.ZONE_ATTRIBUT)
			INNER JOIN #pref#_media m
				ON (v.VEHICULE_MEDIA_ID_THUMBNAIL = m.MEDIA_ID)
			INNER JOIN #pref#_ws_finitions wsf
				ON (v.VEHICULE_LCDV6_CONFIG = wsf.LCDV6
					AND v.VEHICULE_GAMME_CONFIG = wsf.GAMME
					AND v.SITE_ID = wsf.SITE_ID
					AND v.LANGUE_ID = wsf.LANGUE_ID)
			INNER JOIN #pref#_ws_prix_finition_version wspfv
				ON (wsf.CULTURE = wspfv.CULTURE
					AND wsf.LCDV6 = wspfv.LCDV6
					AND wsf.GAMME = wspfv.GAMME
					AND wsf.FINITION_CODE = wspfv.GR_COMMERCIAL_NAME_CODE
				)
			INNER JOIN #pref#_ws_energie_moteur emb
				ON (wspfv.CULTURE = emb.CULTURE
					AND wspfv.LCDV6 = emb.LCDV6
					AND wspfv.GAMME = emb.GAMME
					AND wspfv.ENGINE_CODE = emb.ENGINE_CODE
					AND wspfv.TRANSMISSION_CODE = emb.TRANSMISSION_CODE
				)
			WHERE p.SITE_ID = :SITE_ID
			AND p.LANGUE_ID = :LANGUE_ID
			AND p.PAGE_STATUS = 1
			AND pv.STATE_ID = 4
			AND pv.TEMPLATE_PAGE_ID = :TEMPLATE_PAGE_ID
			AND pz.ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
			AND v.SITE_ID = :SITE_ID
			AND v.LANGUE_ID = :LANGUE_ID
			ORDER BY p.PAGE_ORDER ASC, wsf.LCDV6 ASC, wsf.FINITION_CODE, emb.ENGINE_CODE";
        $aResults = $oConnection->queryTab($sSQL, $aBind);
        $this->value = $aResults;
    }
}
