<?php
/**
 * Fichier de Pelican_Cache : Blocs perso.
 *
 * retour : id, lib
 *
 * @author Gilles LENORMAND <gilles.lenormand@businessdecision.fr>
 *
 * @since 08/12/2008
 */
class Portal_Page_User extends Pelican_Cache
{
    public $duration = WEEK;
    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[":PAGE_ID"] = $this->params[0];
        $aBind[":PORTAL_USER_ID"] = $oConnection->strToBind($this->params[1]);
        $aBind[":LANGUE_ID"] = $this->params[2];
        $aBind[":PAGE_VERSION"] = $this->params[3];
        $aBind[":TEMPLATE_PAGE_ID"] = $this->params[4];

        $sSQL = "SELECT
					z.*,
					pz.*,
					zt.*,";
        if ($this->params[1]) {
            $sSQL .= "
					upz.ZONE_DATA,
					";
        }
        $sSQL .= "	:PAGE_ID as PAGE_ID,
					:LANGUE_ID as LANGUE_ID,
					:PAGE_VERSION as PAGE_VERSION ";

        if ($this->params[1]) {
            $sSQL .= "	FROM #pref#_portal_user_zone_template zt ";
        } else {
            $sSQL .= "	FROM #pref#_zone_template zt ";
        }

        if (!$this->params[1]) {
            $sSQL .= "	INNER JOIN #pref#_template_page_area tpa on (tpa.TEMPLATE_PAGE_ID = zt.TEMPLATE_PAGE_ID AND tpa.AREA_ID=zt.AREA_ID AND IS_DROPPABLE=1) ";
        } else {
            $sSQL .= "	INNER JOIN #pref#_page_version pv on (pv.PAGE_ID = zt.PAGE_ID and pv.LANGUE_ID=:LANGUE_ID and pv.PAGE_VERSION=:PAGE_VERSION)
						INNER JOIN #pref#_template_page_area tpa on (tpa.TEMPLATE_PAGE_ID = pv.TEMPLATE_PAGE_ID AND tpa.AREA_ID=zt.AREA_ID AND IS_DROPPABLE=1) ";
        }
        $sSQL .= "	INNER JOIN #pref#_zone z on (z.ZONE_ID = zt.ZONE_ID)
				";

        $sSQL .= "LEFT JOIN #pref#_page_zone pz on (pz.PAGE_ID = :PAGE_ID AND pz.LANGUE_ID = :LANGUE_ID AND pz.PAGE_VERSION = :PAGE_VERSION AND pz.ZONE_TEMPLATE_ID=zt.ZONE_TEMPLATE_ID) ";

        if ($this->params[1]) {
            $sSQL .= "LEFT JOIN #pref#_portal_user_page_zone upz on (upz.PORTAL_USER_ID=:PORTAL_USER_ID AND upz.PAGE_ID = :PAGE_ID AND upz.LANGUE_ID = :LANGUE_ID AND upz.ZONE_TEMPLATE_ID=zt.ZONE_TEMPLATE_ID) ";
        }

        if (!$this->params[1]) {
            $sSQL .= " WHERE
					zt.TEMPLATE_PAGE_ID=:TEMPLATE_PAGE_ID
					";
        } else {
            $sSQL .= " WHERE
					zt.PAGE_ID=:PAGE_ID
					and zt.PORTAL_USER_ID=:PORTAL_USER_ID ";
        }
        if (!$this->params[1]) {
            $sSQL .= "AND pz.ZONE_EMPTY=0";
        }

        $sSQL .= " ORDER BY zt.ZONE_TEMPLATE_ORDER";

        $tabZones = $oConnection->queryTab($sSQL, $aBind);

        $return = array();
        if ($tabZones) {
            foreach ($tabZones as $data) {
                if ($data["AREA_ID"]) {
                    $data["ZONE_FO_PATH"] = "/layout".$data["ZONE_FO_PATH"];

                    $aZoneData = array();
                    parse_str($data["ZONE_DATA"], $aZoneData);
                    foreach ($aZoneData as $key => $zoneData) {
                        if (!empty($zoneData)) {
                            $data[$key] = str_replace(array("\\\\\"", "\\'", "\\\"", "\\\\"), array("\"", "'", "\"", "\\"), stripcslashes($zoneData));
                        }
                    }
                    unset($data["ZONE_DATA"]);

                    $return[$data["AREA_ID"]][] = $data;
                } else {
                    debug("pb de AREA_ID associé au ZONE_TEMPLATE_ID = ".$data["ZONE_TEMPLATE_ID"]);
                }
            }
        }

        $this->value = $return;
    }
}
