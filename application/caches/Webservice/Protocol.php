<?php
/**
	* Fichier de Pelican_Cache : Liste des protocoles de webservice
	*
	* retour : *
	*
	* @package Cache
	* @subpackage Webservice
	* @author Pierre Moiré <pierre.moire@businessdecision.com>
	* @since 09/01/2009
	*/
class Webservice_Protocol extends Pelican_Cache {

	
	var $duration = MONTH;

	/** Valeur ou objet à mettre en Pelican_Cache */
	function getValue() {
		
		$oConnection = Pelican_Db::getInstance();

		$p = $oConnection->queryTab("select * from #pref#_WEBSERVICE_PROTOCOL");
		$this->value = $p;
	}
}
?>