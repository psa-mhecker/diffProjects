<?php

/**
 * Fichier de Pelican_Cache : Tableau de mise en page des zones.
 *
 * retour : id, lib
 *
 * @author Patrice Deroubaix <pderoubaix@businessdecision.fr>
 *
 * @since 02/12/2004
 */
class Frontend_Page_Zone extends Pelican_Cache
{
    public $duration = WEEK;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[":PAGE_ID"] = $this->params[0];
        $aBind[":LANGUE_ID"] = $this->params[1];
        $isDraft = 0;
        if ($this->params[2]) {
            $type_version = $this->params[2];
        } else {
            $type_version = "CURRENT";
        }
        if ($type_version == "CURRENT") {
            $aBind[":STATE_ID"] = 4;
        } else {
            $aBind[":STATE_ID"] = 1;
            $isDraft = 1;
        }
        $champsTpl = "TEMPLATE_PAGE_ID";

        /*
         * Paramètre permettant de s'adapter à des browsers de types différents en masquant ou non certaines zones
         * Valeurs possibles : '' ou 'web', 'mobile', 'text', 'bot', 'probe'
         **/
        $sZoneCondition = '';
        $sZoneOrder = 'TEMPLATE_PAGE_AREA_ORDER, ZONE_TEMPLATE_ORDER';
        $sZoneOrderMulti = 'TEMPLATE_PAGE_AREA_ORDER, ZONE_ORDER';

        // Pour des types de browsers particuliers, on prend en compte un champs d'ordre différent
       /*if (isset($this->params[3])) {
            // Mode mobile
            if ($this->params[3] == 'mobile') {
                $sZoneOrder = 'ZONE_TEMPLATE_MOBILE_ORDER';
                $sZoneCondition = 'AND ZONE_TEMPLATE_MOBILE_ORDER IS NOT NULL AND ZONE_TEMPLATE_MOBILE_ORDER<>0';
            }
        }*/

        if (! empty($this->params[4])) {
            $altTemplatePagId = $oConnection->queryItem("	SELECT ALT_TEMPLATE_PAGE_ID
															FROM #pref#_page p, #pref#_page_version pv
															WHERE p.PAGE_ID = :PAGE_ID
                                                                                                                        AND p.PAGE_STATUS = 1
                                                                                                                        AND pv.STATE_ID = :STATE_ID
                                                                                                                        AND pv.PAGE_DISPLAY = 1
															AND pv.page_id = p.page_id
															AND pv.page_version = p.page_".$type_version."_version
															AND pv.langue_id = :LANGUE_ID", $aBind);

            if ($altTemplatePagId) {
                $champsTpl = "ALT_TEMPLATE_PAGE_ID";
            }
        }

        /*if ($this->params[3] == 'mobile') {
            $tabAreas = array();
            $tabAreas[] = array(
                "AREA_ID" => 1);
        } else {*/
            $sSQL = "
				SELECT
					*
				FROM
				#pref#_page p
				INNER JOIN #pref#_page_version pv on (p.PAGE_ID = pv.PAGE_ID AND p.LANGUE_ID = pv.LANGUE_ID AND pv.PAGE_VERSION = p.PAGE_".$type_version."_VERSION)
				INNER JOIN #pref#_template_page_area tpa on (pv.".$champsTpl." = tpa.TEMPLATE_PAGE_ID)
				INNER JOIN #pref#_area a on (tpa.AREA_ID = a.AREA_ID)
				WHERE
				p.PAGE_ID = :PAGE_ID";
        if ($isDraft == 0) {
            $sSQL .= "
                    AND p.PAGE_STATUS = 1";
        }
        $sSQL .= "
                AND pv.STATE_ID = :STATE_ID
                AND pv.PAGE_DISPLAY = 1
				AND p.LANGUE_ID = :LANGUE_ID
				ORDER BY TEMPLATE_PAGE_AREA_ORDER";
        $tabAreas = $oConnection->querytab($sSQL, $aBind);
        /*}*/

        $sSQL = "SELECT tpa.AREA_ID, pv.PAGE_VERSION,
				p.*,
				pv.*,
				pz.*,

				zt.*,
				z.*,
				czt.*,
				m.MEDIA_PATH as PAGE_VERSION_MEDIA_PATH,
				m.MEDIA_ALT as PAGE_VERSION_MEDIA_ALT,
				m2.MEDIA_ALT
				FROM
				#pref#_page p
				INNER JOIN #pref#_page_version pv on (pv.PAGE_ID = p.PAGE_ID AND pv.LANGUE_ID = p.LANGUE_ID AND pv.PAGE_VERSION = p.PAGE_".$type_version."_VERSION)
				INNER JOIN #pref#_template_page_area tpa on (tpa.TEMPLATE_PAGE_ID=pv.".$champsTpl.")
				INNER JOIN #pref#_zone_template zt on (tpa.TEMPLATE_PAGE_ID=zt.TEMPLATE_PAGE_ID AND tpa.AREA_ID=zt.AREA_ID)
				INNER JOIN #pref#_zone z on (z.ZONE_ID = zt.ZONE_ID)
                INNER JOIN #pref#_area a on (tpa.AREA_ID = a.AREA_ID)
				LEFT JOIN #pref#_page_zone pz on (zt.ZONE_TEMPLATE_ID=pz.ZONE_TEMPLATE_ID AND pz.PAGE_ID = pv.PAGE_ID AND pz.LANGUE_ID = pv.LANGUE_ID AND pz.PAGE_VERSION = pv.PAGE_VERSION)
				LEFT JOIN #pref#_zone_layout czt on (czt.ZONE_LAYOUT_ID=pz.ZONE_LAYOUT_ID)
				LEFT JOIN #pref#_media m on (m.MEDIA_ID=pv.MEDIA_ID)
				LEFT JOIN #pref#_media m2 on (m2.MEDIA_ID=pz.MEDIA_ID)

                LEFT JOIN #pref#_page_multi_zone pmz on (pmz.PAGE_ID = pv.PAGE_ID AND pmz.LANGUE_ID = pv.LANGUE_ID AND pmz.PAGE_VERSION = pv.PAGE_VERSION AND tpa.AREA_ID = pmz.AREA_ID)



				WHERE
				p.PAGE_ID = :PAGE_ID
				AND p.LANGUE_ID = :LANGUE_ID
                ";
        if ($isDraft == 0) {
            $sSQL .= "
                    AND p.PAGE_STATUS = 1";
        }
        $sSQL .= "
                                AND pv.STATE_ID = :STATE_ID
                                AND pv.PAGE_DISPLAY = 1
                AND a.AREA_DROPPABLE is NULL
				".$sZoneCondition."
				ORDER BY ".$sZoneOrder;

        $sSQLMulti = "SELECT
				p.*,
				pv.*,
				pmz.*,
				z.*,
				czt.*,
				m.MEDIA_PATH as PAGE_VERSION_MEDIA_PATH,
				m.MEDIA_ALT as PAGE_VERSION_MEDIA_ALT,
				m2.MEDIA_ALT
				FROM
				#pref#_page p
				INNER JOIN #pref#_page_version pv on (pv.PAGE_ID = p.PAGE_ID AND pv.LANGUE_ID = p.LANGUE_ID AND pv.PAGE_VERSION = p.PAGE_".$type_version."_VERSION)
				INNER JOIN #pref#_template_page_area tpa on (tpa.TEMPLATE_PAGE_ID=pv.".$champsTpl.")

				INNER JOIN #pref#_area a on (tpa.AREA_ID = a.AREA_ID)

                LEFT JOIN #pref#_page_multi_zone pmz on (pmz.PAGE_ID = pv.PAGE_ID AND pmz.LANGUE_ID = pv.LANGUE_ID AND pmz.PAGE_VERSION = pv.PAGE_VERSION AND tpa.AREA_ID = pmz.AREA_ID)
				INNER JOIN #pref#_zone z on (z.ZONE_ID = pmz.ZONE_ID)


                LEFT JOIN #pref#_zone_layout czt on (czt.ZONE_LAYOUT_ID=pmz.ZONE_LAYOUT_ID)
				LEFT JOIN #pref#_media m on (m.MEDIA_ID=pv.MEDIA_ID)
				LEFT JOIN #pref#_media m2 on (m2.MEDIA_ID=pmz.MEDIA_ID)

				WHERE
				p.PAGE_ID = :PAGE_ID
				AND p.LANGUE_ID = :LANGUE_ID
              ";
        if ($isDraft == 0) {
            $sSQLMulti .= "
                AND p.PAGE_STATUS = 1";
        }
        $sSQLMulti .= "
                                AND pv.STATE_ID = :STATE_ID
                                AND pv.PAGE_DISPLAY = 1
                AND a.AREA_DROPPABLE = 1
				".$sZoneCondition."
				ORDER BY ".$sZoneOrderMulti;

        $tabZones = $oConnection->queryTab($sSQL, $aBind);
        $tabMultiZones = $oConnection->queryTab($sSQLMulti, $aBind);

        if ($tabZones) {
            foreach ($tabZones as $index => $data) {
                /*if ($this->params[3] == 'mobile') {
                    $data["AREA_ID"] = "1";
                }*/
                if ($data["AREA_ID"]) {
                    $data["ZONE_FO_PATH"] =  $data["ZONE_FO_PATH"];
                    if ($data["ZONE_TITRE19"] == 'DS') {
                        $data["ZONE_SKIN"] =  'ds';
                    } elseif ($data["ZONE_TITRE19"] == 'C') {
                        $data["ZONE_SKIN"] =  'c-skin';
                    } else {
                        $data["ZONE_SKIN"] =  ($this->params[3] == 'mobile') ? Frontoffice_Design_Helper::getModeAffichage($data, 'zone', true) : Frontoffice_Design_Helper::getModeAffichage($data);
                    }
                    //$return[$data["AREA_ID"]][]= $data;
                    $return[$data["AREA_ID"]][$index][0] = $data;
                } else {
                    debug("pb de AREA_ID associé au ZONE_TEMPLATE_ID = ".$data["ZONE_TEMPLATE_ID"]);
                }
            }
        }
        if ($tabMultiZones) {
            foreach ($tabMultiZones as $index => $data) {
                /*if ($this->params[3] == 'mobile') {
                    $data["AREA_ID"] = "1";
                }*/
                if ($data["AREA_ID"]) {
                    $data["ZONE_FO_PATH"] =  $data["ZONE_FO_PATH"];
                    if ($data["ZONE_TITRE19"] == 'DS') {
                        $data["ZONE_SKIN"] =  'ds';
                    } elseif ($data["ZONE_TITRE19"] == 'C') {
                        $data["ZONE_SKIN"] =  'c-skin';
                    } else {
                        $data["ZONE_SKIN"] =  ($this->params[3] == 'mobile') ? Frontoffice_Design_Helper::getModeAffichage($data, 'zone', true) : Frontoffice_Design_Helper::getModeAffichage($data);
                    }
                    //$return[$data["AREA_ID"]][] = $data;
                    $return[$data["AREA_ID"]][$index][0] = $data;
                } else {
                    debug("pb de AREA_ID associé au ZONE_TEMPLATE_ID = ".$data["ZONE_TEMPLATE_ID"]);
                }
            }
        }

        $this->value = array(
            "areas" => $tabAreas ,
            "zones" => $return, );
    }
}
