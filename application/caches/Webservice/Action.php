<?php
/**
	* Fichier de Pelican_Cache : Détail d'une action de webservice
	*
	* Paramètres :
	* 	0 - WEBSERVICE_NAME : Nom du webservice
	*
	* @package Pelican_Cache
	* @subpackage Webservice
	* @author Pierre Moiré <pierre.moire@businessdecision.com>
	* @since 01/06/2009
	*/
class Webservice_Action extends Pelican_Cache {

	
	var $duration = WEEK;
	var $keepCache = false;
	
	/** Valeur ou objet à mettre en Pelican_Cache */
	function getValue() {
		
		$oConnection = Pelican_Db::getInstance();
		$r = array();
		$aBind[':WEBSERVICE_ACTION_METHOD'] = $oConnection->strToBind($this->params[0]);
		error_log('Recuperation du Pelican_Cache webservice_Webservice_Action pour le service '.$this->params[0]);
		
		error_log(' - Requete SQL lancee');
		$sQuery = "select *
			from 
				#pref#_webservice_action wa
			INNER JOIN 
				#pref#_webservice w ON (wa.WEBSERVICE_ID=w.WEBSERVICE_ID)
			INNER JOIN 
				#pref#_webservice_action_package wap ON (wap.WEBSERVICE_ACTION_ID=wa.WEBSERVICE_ACTION_ID)
			INNER JOIN 
				#pref#_webservice_package wp ON (wp.WEBSERVICE_PACKAGE_ID=wap.WEBSERVICE_PACKAGE_ID)
			WHERE 
				wa.WEBSERVICE_ACTION_METHOD=:WEBSERVICE_ACTION_METHOD
				AND w.WEBSERVICE_ENABLED=1
				AND (WEBSERVICE_PACKAGE_BEGINNING_DATE IS NULL OR WEBSERVICE_PACKAGE_BEGINNING_DATE<NOW())
				AND (WEBSERVICE_PACKAGE_END_DATE IS NULL OR WEBSERVICE_PACKAGE_END_DATE>NOW())";
		
		$wa = $oConnection->queryRow($sQuery, $aBind);
		
		//~ foreach($wa as $val){
			//~ $r[$val['WEBSERVICE_ACTION_NAME']] = $val;
		//~ }
		$this->value = $wa;
	}
}
?>