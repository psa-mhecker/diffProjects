<?php
/**
 * Fichier de Pelican_Cache : Resultats.
 */
class Frontend_Citroen_CarSelector_Resultats extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[':SITE_ID'] = $this->params[0];
        $aBind[':LANGUE_ID'] = $this->params[1];
        $aFiltres = $this->params[3];
        $aBind[':TYPE_GAMME'] = $this->params[5];

        $aBind[':SELECTEUR_TEINTES'] = Pelican::$config['ZONE_TEMPLATE_ID']['SHOWROOM_ACCUEIL_SELECT_TEINTE'];

        $sSQL = "
            SELECT
				wvg.*,
				v.*,
				m.MEDIA_PATH,
                IFNULL(v.VEHICULE_LCDV6_CONFIG, v.VEHICULE_LCDV6_MANUAL) as LCDV6,
                IFNULL(v.VEHICULE_GAMME_CONFIG, v.VEHICULE_GAMME_MANUAL) as GAMME,
                pv.PAGE_CLEAR_URL as URL_DETAIL
            FROM
            #pref#_page p
            INNER JOIN #pref#_page_version pv
                ON (pv.PAGE_ID = p.PAGE_ID
                    AND pv.LANGUE_ID = p.LANGUE_ID
                    AND pv.PAGE_VERSION = p.PAGE_CURRENT_VERSION)
            INNER JOIN #pref#_page_zone pz
                ON (pz.PAGE_ID = pv.PAGE_ID
                    AND pz.LANGUE_ID = pv.LANGUE_ID
                    AND pz.PAGE_VERSION = pv.PAGE_VERSION
                    AND pz.ZONE_TEMPLATE_ID = :SELECTEUR_TEINTES)
            INNER JOIN #pref#_vehicule v
                ON (v.VEHICULE_ID = pz.ZONE_ATTRIBUT)
            INNER JOIN #pref#_ws_vehicule_gamme wvg
                ON (wvg.LCDV6 = IFNULL(v.VEHICULE_LCDV6_CONFIG, v.VEHICULE_LCDV6_MANUAL)
                    AND wvg.GAMME = IFNULL(v.VEHICULE_GAMME_CONFIG, v.VEHICULE_GAMME_MANUAL)
                    AND wvg.SITE_ID = p.SITE_ID
                    AND wvg.LANGUE_ID = p.LANGUE_ID  )
			INNER JOIN #pref#_ws_critere_selection wcs
				ON (wvg.LCDV6 = wcs.LCDV6
                    and wcs.GAMME = wvg.GAMME
                    and wcs.SITE_ID = p.SITE_ID
                    and wcs.LANGUE_ID = p.LANGUE_ID)
			INNER JOIN #pref#_ws_caracteristique_moteur wcm
				ON (wvg.LCDV6 = wcm.LCDV6
                    and wcm.GAMME = wvg.GAMME
                    and wcm.SITE_ID = p.SITE_ID
                    and wcm.LANGUE_ID = p.LANGUE_ID)
			LEFT JOIN #pref#_vehicule_criteres vc
				ON (v.VEHICULE_ID = vc.VEHICULE_ID)
			LEFT JOIN #pref#_media m
				ON (m.MEDIA_ID = v.VEHICULE_MEDIA_ID_THUMBNAIL)

            WHERE wvg.SITE_ID = :SITE_ID
            AND wvg.LANGUE_ID = :LANGUE_ID
            AND p.PAGE_STATUS = 1
            AND pv.STATE_ID = 4
        ";
        if ($this->params[5]) {
            $sSQL .= " AND v.VEHICULE_GAMME_CONFIG = ':TYPE_GAMME'";
        }

        if ($this->params[2] == 1) {
            if (is_array($aFiltres) && count($aFiltres)>0) {
                foreach ($aFiltres as $key => $filtre) {
                    if ($key != "SILHOUETTE") {
                        if ($key == "ENERGIE" || $key == "BOITE_VITESSE") {
                            $aBind[':'.$key] = $oConnection->strToBind($filtre);
                        } else {
                            $aBind[':'.$key] = $filtre;
                        }
                    }
                }
            }
            if (is_array($aFiltres['SILHOUETTE']) && count($aFiltres['SILHOUETTE'])>0) {
                $sSQL .= " AND CRIT_BODY_CODE IN (";
                foreach ($aFiltres['SILHOUETTE'] as $key => $silhouette) {
                    $aBind[':SILHOUETTE_'.$key] = $oConnection->strToBind($silhouette);
                    if ($key != 0) {
                        $sSQL .= ",";
                    }
                    $sSQL .= ":SILHOUETTE_".$key;
                }
                $sSQL .= ")";
            }
            if (!empty($aBind[':PRIX'])) {
                $sSQL .= " AND CRIT_PRICE_MIN <= :PRIX";
            }

            if ($this->params[4] != 1) {
                if (!empty($aBind[':ENERGIE'])) {
                    $sSQL .= " AND ENERGY_CATEGORY = :ENERGIE";
                }
                if (!empty($aBind[':BOITE_VITESSE'])) {
                    $sSQL .= " AND CRIT_TR_CODE = :BOITE_VITESSE";
                }

                if (!empty($aBind[':PASSAGERS'])) {
                    if ($aBind[':PASSAGERS'] < 4) {
                        $sSQL .= " AND SEATS = :PASSAGERS";
                    } else {
                        $sSQL .= " AND SEATS >= :PASSAGERS";
                    }
                }
                if (!empty($aBind[':CONSO'])) {
                    $sSQL .= " AND CRIT_MIXEDCONSUMPTION_MIN <= :CONSO";
                }
                if (!empty($aBind[':EMISSION'])) {
                    $sSQL .= " AND (CRIT_CO2_RATE_MIN <= :EMISSION OR CRIT_CO2_RATE_MIN IS NULL)";
                }
                if (!empty($aBind[':LONGUEUR'])) {
                    $sSQL .= " AND CRIT_EXTERIOR_LENGTH_MIN <= :LONGUEUR";
                }
            }
        } elseif ($this->params[2] == 2) {
            if (!empty($aFiltres['PRIX']) && count($aFiltres['PRIX'])>0) {
                $aBind[':PRIX_START'] = $aFiltres['PRIX']['START'];
                $aBind[':PRIX_END'] = $aFiltres['PRIX']['END'];
                /*if($aFiltres['PRIX']['START'] && $aFiltres['PRIX']['END']){
                    $sSQL .= " AND (CRIT_PRICE_MIN >= :PRIX_START AND CRIT_PRICE_MIN <= :PRIX_END)";
                }else{*/
                    if ($aFiltres['PRIX']['START']) {
                        $sSQL .= " AND CRIT_PRICE_MIN >= :PRIX_START";
                    }
                if ($aFiltres['PRIX']['END']) {
                    $sSQL .= " AND CRIT_PRICE_MIN <= :PRIX_END";
                }
                //}
            }
            if (!empty($aFiltres['CRITERES']) && count($aFiltres['CRITERES'])>0) {
                $sSQL .= " AND vc.CRITERE_ID in (";
                foreach ($aFiltres['CRITERES'] as $key => $critere) {
                    $aBind[':CRITERES_'.$key] = $critere;
                    if ($key != 0) {
                        $sSQL .= ",";
                    }
                    $sSQL .= ":CRITERES_".$key;
                }
                $sSQL .= ")";
            }
        }
        $sSQL .= " GROUP BY p.PAGE_ID
				   ORDER BY p.PAGE_ORDER ASC";
        $aResults = $oConnection->queryTab($sSQL, $aBind);
        $this->value = $aResults;
    }
}
