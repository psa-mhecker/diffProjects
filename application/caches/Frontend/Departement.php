<?php
/**
	* @package Pelican_Cache
	* @subpackage Pelican
	*/

/**
	* Fichier de Pelican_Cache : Données associées à un département
	*
	* @package Pelican_Cache
	* @subpackage Pelican
	* @author Lenormand Gilles <glenormand@businessdecision.com>
	* @since 15/05/2006
	*/
class Frontend_Departement extends Pelican_Cache {

	
	var $duration = DAY;
	
	/** Valeur ou objet à mettre en Pelican_Cache */
	function getValue() {
		$aBind=array();
		
		$metropole=$this->params[0];
		$oConnection = Pelican_Db::getInstance();
		
		$strSql = "SELECT DEPARTEMENT_ID,DEPARTEMENT_LABEL
				FROM #pref#_departement";
		if($metropole){
			$strSql .= " WHERE DEPARTEMENT_ID<97 AND DEPARTEMENT_ID not in ('20','21')";
		}	
		$strSql .= " ORDER BY DEPARTEMENT_ID ASC";
		$result = $oConnection->queryTab($strSql, $aBind);
		$result2=array();
		for($i=0;$i<count($result);$i++){
			$result2[$result[$i]["DEPARTEMENT_ID"]]=$result[$i]["DEPARTEMENT_LABEL"];
		}
		
		$this->value = $result2;
	}
}
?>