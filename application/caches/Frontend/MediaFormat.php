<?php
	/**
	* @package Cache
	* @subpackage Media
	*/
	 
	/**
	* Fichier de Pelican_Cache : Résultat de requête sur media_format
	*
	* retour : *
	*
	* @package Cache
	* @subpackage Media
	* @author Raphaël Carles <rcarles@businessdecision.com>
	* @since 20/06/2004
	*/
	class Frontend_MediaFormat extends Pelican_Cache {
		 
		
		var $duration = UNLIMITED;
		 
		/** Valeur ou objet à mettre en Pelican_Cache */
		function getValue() {
			
			$oConnection = Pelican_Db::getInstance();
			 
			if ($this->params) {
				$query = "
					SELECT *
					FROM ".Pelican::$config["FW_MEDIA_FORMAT_TABLE_NAME"]."
					WHERE ".Pelican::$config["FW_MEDIA_FORMAT_FIELD_ID"]."=".(int) $this->params[0];
				$this->value = $oConnection->queryRow($query);
			}
		}
	}
	 
?>