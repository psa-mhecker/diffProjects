<?php

/**
 * Fichier de Pelican_Cache : Liste des véhicules par page promotion.
 */
class Frontend_Citroen_Promotion extends Pelican_Cache
{
    public $duration = WEEK;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        if ($this->params[3]) {
            $type_version = $this->params[3];
        } else {
            $type_version = "CURRENT";
        }
        $aBind[':ZONE_TEMPLATE_ID'] = Pelican::$config['ZONE_TEMPLATE_ID']['PROMOTIONS'];
        $aBind[':PAGE_ZONE_MULTI_TYPE'] = $oConnection->strToBind("Promotion");

        $sSQL = "
			SELECT
				pzm.*,
				m.MEDIA_PATH,
				m.MEDIA_ALT,
				m2.MEDIA_PATH AS MEDIA_PATH_FLASH,
				m2.MEDIA_ALT AS MEDIA_ALT_FLASH,
				m3.MEDIA_PATH AS YOUTUBE_PATH,
				m3.MEDIA_ALT AS YOUTUBE_ALT,
				v.VEHICULE_ID,
				v.VEHICULE_LABEL,
				CONCAT(pzm.PAGE_ID,'||',pzm.LANGUE_ID,'||',ZONE_TEMPLATE_ID,'||',PAGE_ZONE_MULTI_ID) as IDENTIFIANT
			FROM #pref#_page p
			INNER JOIN #pref#_page_version pv
				ON (pv.PAGE_ID = p.PAGE_ID
					AND pv.PAGE_VERSION = p.PAGE_".$type_version."_VERSION
					AND pv.LANGUE_ID = p.LANGUE_ID)
			INNER JOIN #pref#_page_zone_multi pzm
				ON (pzm.PAGE_ID = pv.PAGE_ID
					AND pzm.PAGE_VERSION = pv.PAGE_VERSION
					AND pzm.LANGUE_ID = pv.LANGUE_ID)
			LEFT JOIN #pref#_vehicule v
				ON (v.VEHICULE_ID = pzm.PAGE_ZONE_MULTI_LABEL5 AND v.LANGUE_ID = pzm.LANGUE_ID)
			LEFT JOIN #pref#_media m
				ON (m.MEDIA_ID = pzm.MEDIA_ID)
			LEFT JOIN #pref#_media m2
				ON (m2.MEDIA_ID = pzm.MEDIA_ID2)
			LEFT JOIN #pref#_media m3
				ON (m3.MEDIA_ID = pzm.YOUTUBE_ID)
			WHERE p.SITE_ID = :SITE_ID
			AND p.LANGUE_ID = :LANGUE_ID
                        AND p.PAGE_STATUS = 1
                        AND pv.STATE_ID = 4
			AND pzm.ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
			AND pzm.PAGE_ZONE_MULTI_TYPE = :PAGE_ZONE_MULTI_TYPE";
        if ($this->params[2]) {
            $aBind[':PAGE_ID'] = $this->params[2];
            $sSQL .= " AND p.PAGE_ID = :PAGE_ID ";
        }
        if ($this->params[4]) {
            $aBind[':VEHICULE_ID'] = $this->params[4];
            $sSQL .= " AND v.VEHICULE_ID = :VEHICULE_ID ";
        }
        $sSQL .= "
			ORDER BY pzm.PAGE_ZONE_MULTI_ID";
        $aListePromotions = $oConnection->queryTab($sSQL, $aBind);

        // CTA des promotions
        $iCount = count($aListePromotions);
        for ($i = 0; $i < $iCount; $i++) {
            $aBind[':PAGE_ID'] = $aListePromotions[$i]["PAGE_ID"];
            $aBind[':PAGE_ZONE_MULTI_ID'] = $aListePromotions[$i]["PAGE_ZONE_MULTI_ID"];
            $aBind[':PAGE_ZONE_MULTI_TYPE3'] = $oConnection->strToBind("CTAFORM");
            $sSQLCTA = "
				SELECT
					pzmm.*,
					m.MEDIA_PATH
				FROM #pref#_page p
				INNER JOIN #pref#_page_version pv
					ON (pv.PAGE_ID = p.PAGE_ID
						AND pv.PAGE_VERSION = p.PAGE_".$type_version."_VERSION
						AND pv.LANGUE_ID = p.LANGUE_ID)
				INNER JOIN #pref#_page_zone_multi pzm
					ON (pzm.PAGE_ID = pv.PAGE_ID
						AND pzm.PAGE_VERSION = pv.PAGE_VERSION
						AND pzm.LANGUE_ID = pv.LANGUE_ID)
				INNER JOIN #pref#_page_zone_multi_multi pzmm
					ON (pzmm.PAGE_ID = pv.PAGE_ID
						AND pzmm.PAGE_VERSION = pzm.PAGE_VERSION
						AND pzmm.LANGUE_ID = pzm.LANGUE_ID
						AND pzmm.ZONE_TEMPLATE_ID = pzm.ZONE_TEMPLATE_ID
						AND pzmm.PAGE_ZONE_MULTI_ID = pzm.PAGE_ZONE_MULTI_ID)
				LEFT JOIN #pref#_media m
					ON (m.MEDIA_ID = pzmm.MEDIA_ID)
				WHERE p.LANGUE_ID = :LANGUE_ID
				AND p.PAGE_ID = :PAGE_ID
				AND pzm.ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
				AND pzm.PAGE_ZONE_MULTI_ID = :PAGE_ZONE_MULTI_ID
				AND pzmm.PAGE_ZONE_MULTI_TYPE = :PAGE_ZONE_MULTI_TYPE3
				ORDER BY pzmm.PAGE_ZONE_MULTI_MULTI_ID";
            $aListePromotions[$i]['CTA'] = $oConnection->queryTab($sSQLCTA, $aBind);
        }
        $this->value = array_values($aListePromotions);

        return $this->value;
    }
}
