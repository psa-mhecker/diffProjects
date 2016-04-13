<?php
/**
	* Fichier de Pelican_Cache : donn�es d'un bloc
	*
	* retour : toutes donn�es du bloc
	*
	* @package Cache
	* @subpackage Page
	* @author Gilles LENORMAND <gilles.lenormand@businessdecision.com>
	* @since 05/01/2009
	*/
class Portal_Bloc extends Pelican_Cache {
	
	var $duration = WEEK;

	/** Valeur ou objet à mettre en Pelican_Cache */
	function getValue() {
		

		$oConnection = Pelican_Db::getInstance();
		$aBind[":PAGE_ID"] = $this->params[0];
		$aBind[":ZONE_TEMPLATE_ID"] = $this->params[1];
		$aBind[":PORTAL_USER_ID"] = $oConnection->strToBind($this->params[2]);
		$aBind[":LANGUE_ID"] = $this->params[3];
		$aBind[":PAGE_VERSION"] = $this->params[4];

		$sSQL = "SELECT
					z.*,
					".$oConnection->getConcatClause(array("'/layout'","z.ZONE_FO_PATH"))." as ZONE_FO_PATH,
					pz.*,	
					zt.*,";

		if($this->params[2]){
			$sSQL .="
					upz.ZONE_DATA,
					";
		}
		$sSQL .=":PAGE_ID as PAGE_ID,
				 :PAGE_VERSION as PAGE_VERSION,
				 :LANGUE_ID as LANGUE_ID ";

		if($this->params[2]){
			$sSQL .="	FROM #pref#_portal_user_zone_template zt ";
		}else{
			$sSQL .="	FROM #pref#_zone_template zt ";
		}

		$sSQL .= " INNER JOIN #pref#_zone z on (z.ZONE_ID = zt.ZONE_ID) ";


		$sSQL.=" LEFT JOIN #pref#_page_zone pz on (pz.ZONE_TEMPLATE_ID=:ZONE_TEMPLATE_ID AND pz.PAGE_ID = :PAGE_ID AND pz.LANGUE_ID = :LANGUE_ID AND pz.PAGE_VERSION=:PAGE_VERSION) ";
		if($this->params[2]){
			$sSQL.=" LEFT JOIN #pref#_portal_user_page_zone upz on (upz.PORTAL_USER_ID=:PORTAL_USER_ID AND upz.ZONE_TEMPLATE_ID=:ZONE_TEMPLATE_ID AND upz.PAGE_ID = :PAGE_ID AND upz.LANGUE_ID = :LANGUE_ID) ";
		}
		$sSQL.=" WHERE zt.ZONE_TEMPLATE_ID =:ZONE_TEMPLATE_ID
					";
		if($this->params[2]){
			$sSQL.=" AND zt.PORTAL_USER_ID = :PORTAL_USER_ID
					";
		}
		$z = $oConnection->queryRow($sSQL, $aBind);

		$aZoneData=array();
		parse_str($z["ZONE_DATA"],$aZoneData);
		foreach ($aZoneData as $key=>$zoneData){
			$z[$key]=str_replace(array("\\\\\"","\\'","\\\"","\\\\"),array("\"","'","\"","\\"),stripcslashes($zoneData));
		}
		unset($z["ZONE_DATA"]);

		$this->value = $z;
	}
}
?>