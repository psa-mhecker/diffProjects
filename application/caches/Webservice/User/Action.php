<?php
/**
	* Fichier de Pelican_Cache : Liste les actions disponibles pour un utilisateur
	*
	* Paramètres :
	* 	0 - PACKAGE_ACTION_NAME : Nom de l'action de webservice
	* 	1 - USER : Login Utilisateur
	* 	1 - PASS : Pass Utilisateur
	*
	* @package Pelican_Cache
	* @subpackage Webservice
	* @author Pierre Moiré <pierre.moire@businessdecision.com>
	* @since 01/06/2009
	*/
class Webservice_User_Action extends Pelican_Cache {

	
	var $duration = WEEK;

	/** Valeur ou objet à mettre en Pelican_Cache */
	function getValue() {
		
		$oConnection = Pelican_Db::getInstance();
		$where = array();
		$joins = array();
		
		$aBind = array();
		$aBind[':WEBSERVICE_ACCOUNT_LOGIN'] = $oConnection->strToBind($this->params[0]);
		
		if( $this->params[0] && $this->params[0]!=''){
		$displayPassword = 'wa.WEBSERVICE_ACCOUNT_PASSWORD as PASSWORD,';
		}else{
			$displayPassword = '';
		}
		
		//$aBind[':PASS'] = $oConnection->strToBind($this->params[2]);
		error_log('Recuperation du Pelican_Cache webservice_Webservice_User_Action pour le service '.$this->params[0]);
		
		error_log(' - Requete SQL lancee');
		
		
		$where[] = "w.WEBSERVICE_ENABLED=1";
		
		
		////////////////////////////////////////////////////////////////////////
		// Cas 1 : Recherche de tous les services disponibles pour l'utilisateur mentionné
		////////////////////////////////////////////////////////////////////////
		
		if(isset($this->params[0]) && $this->params[0]!=''){
			error_log(' - Recherche de tous les services disponibles pour'.$this->params[0]);
			
			$joins[] = "LEFT JOIN #pref#_webservice_account_package wacp ON (wacp.WEBSERVICE_PACKAGE_ID=wp.WEBSERVICE_PACKAGE_ID AND wacp.WEBSERVICE_ACCOUNT_LOGIN=:WEBSERVICE_ACCOUNT_LOGIN)";
			$joins[] = "LEFT JOIN #pref#_webservice_account wa ON (wacp.WEBSERVICE_ACCOUNT_LOGIN=wa.WEBSERVICE_ACCOUNT_LOGIN AND wa.WEBSERVICE_ACCOUNT_ENABLED=1)";
			
			$where[] = "(WEBSERVICE_PACKAGE_BEGINNING_DATE IS NULL OR WEBSERVICE_PACKAGE_BEGINNING_DATE<NOW())";
			$where[] = "(WEBSERVICE_PACKAGE_END_DATE IS NULL OR WEBSERVICE_PACKAGE_END_DATE>NOW())";
			$where[] = "(wp.WEBSERVICE_PACKAGE_PUBLIC=1 OR wa.WEBSERVICE_ACCOUNT_ENABLED=1)";
		
		
		////////////////////////////////////////////////////////////////////////
		// Cas 2 : Recherche de tous les services publics
		////////////////////////////////////////////////////////////////////////	
			
		}else{
			error_log(' - Recherche des services publics disponibles');
				
			$where[] = "(WEBSERVICE_PACKAGE_BEGINNING_DATE IS NULL OR WEBSERVICE_PACKAGE_BEGINNING_DATE<NOW())";
			$where[] = "(WEBSERVICE_PACKAGE_END_DATE IS NULL OR WEBSERVICE_PACKAGE_END_DATE>NOW())";
			$where[] = "wp.WEBSERVICE_PACKAGE_PUBLIC=1";
		}
		
		
		$sQuery = "select DISTINCT
				wac.WEBSERVICE_ACTION_NAME,
				wac.WEBSERVICE_ACTION_DESCRIPTION,
				wac.WEBSERVICE_ACTION_METHOD,
				w.WEBSERVICE_CLASS_PATH,
				w.WEBSERVICE_CLASS,
				wp.WEBSERVICE_PACKAGE_PUBLIC,
				".$displayPassword."
				wac.OUTPUT_XML,
				wac.OUTPUT_RSS,
				wac.OUTPUT_ATOM,
				wac.OUTPUT_HTML,
				wac.OUTPUT_SOAP
			from 
				#pref#_webservice_package wp
				".implode($joins,"\n")."
			INNER JOIN 
				#pref#_webservice_action_package wap ON (wp.WEBSERVICE_PACKAGE_ID=wap.WEBSERVICE_PACKAGE_ID)
			INNER JOIN 
				#pref#_webservice_action wac ON (wac.WEBSERVICE_ACTION_ID=wap.WEBSERVICE_ACTION_ID)
			INNER JOIN 
				#pref#_webservice w ON (w.WEBSERVICE_ID=wac.WEBSERVICE_ID)
			WHERE 
				".implode($where,' AND ');
		$result = $oConnection->queryTab($sQuery, $aBind);
		
		$this->value = $result;
	}
}
?>