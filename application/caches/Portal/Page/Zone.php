<?php
/**
	* Fichier de Pelican_Cache : Tableau de mise en page des zones
	*
	* retour : id, lib
	*
	* @package Pelican_Cache 
	* @subpackage Portal
	* @author Gilles LENORMAND <gilles.lenormand@businessdecision.fr>
	* @since 01/12/2008
*/
class Portal_Page_Zone extends Pelican_Cache {
	
	var $duration = WEEK;
	/** Valeur ou objet à mettre en Pelican_Cache */
	function getValue() {
		
		$oConnection = Pelican_Db::getInstance();
		$aBind[":PAGE_ID"] = $this->params[0];
		$aBind[":LANGUE_ID"] = $this->params[1];
		if ($this->params[2]) {
			$type_version = $this->params[2];
		} else {
			$type_version = "CURRENT";
		}
		$sSQL = "
				SELECT
					*
				FROM
				#pref#_page p
				INNER JOIN #pref#_page_version pv on (p.PAGE_ID = pv.PAGE_ID AND p.LANGUE_ID = pv.LANGUE_ID AND pv.PAGE_VERSION = p.PAGE_".$type_version."_VERSION)
				INNER JOIN #pref#_template_page_area tpa on (pv.TEMPLATE_PAGE_ID = tpa.TEMPLATE_PAGE_ID)
				INNER JOIN #pref#_area a on (tpa.AREA_ID = a.AREA_ID)
				WHERE
				p.PAGE_ID = :PAGE_ID
				AND p.LANGUE_ID = :LANGUE_ID
				ORDER BY TEMPLATE_PAGE_AREA_ORDER";
		$tabAreas = $oConnection->querytab($sSQL, $aBind);
		$aBind[":PAGE_VERSION"]=$tabAreas[0]["PAGE_VERSION"];
		$aBind[":TEMPLATE_PAGE_ID"]=$tabAreas[0]["TEMPLATE_PAGE_ID"];
				$sSQL = "SELECT
				pz.*,
				zt.*,
				z.*,
				:PAGE_ID as PAGE_ID,
				:LANGUE_ID as LANGUE_ID,
				:PAGE_VERSION as PAGE_VERSION
				FROM #pref#_zone_template zt 
				INNER JOIN #pref#_template_page_area tpa on (tpa.TEMPLATE_PAGE_ID = zt.TEMPLATE_PAGE_ID AND tpa.AREA_ID=zt.AREA_ID AND IS_DROPPABLE<>1)
				INNER JOIN #pref#_zone z on (z.ZONE_ID = zt.ZONE_ID)
				LEFT JOIN #pref#_page_zone pz on (pz.PAGE_ID = :PAGE_ID AND pz.LANGUE_ID = :LANGUE_ID AND pz.PAGE_VERSION = :PAGE_VERSION AND pz.ZONE_TEMPLATE_ID=zt.ZONE_TEMPLATE_ID)
				WHERE
				zt.TEMPLATE_PAGE_ID = :TEMPLATE_PAGE_ID
				ORDER BY TEMPLATE_PAGE_AREA_ORDER,ZONE_TEMPLATE_ORDER";
		$tabZones = $oConnection->queryTab($sSQL, $aBind);

		if ($tabZones) {
			foreach($tabZones as $data) {
				if ($data["AREA_ID"]) {
					$data["ZONE_FO_PATH"] = "/layout".$data["ZONE_FO_PATH"];
					$return[$data["AREA_ID"]][] = $data;
				} else {
					debug("pb de AREA_ID associé au ZONE_TEMPLATE_ID = ".$data["ZONE_TEMPLATE_ID"]);
				}
			}
		}
		$this->value = array("areas"=>$tabAreas, "zones"=>$return);
	}
}
?>