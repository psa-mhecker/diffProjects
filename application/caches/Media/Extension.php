<?php
	/**
	* @package Cache
	* @subpackage Media
	*/
	 
	/**
	* Fichier de Pelican_Cache : Résultat de requête sur media_type, media_extension
	*
	* retour : *
	*
	* @package Cache
	* @subpackage Media
	* @author Raphaël Carles <rcarles@businessdecision.com>
	* @since 20/06/2004
	*/
	class Media_Extension extends Pelican_Cache {
		 
		
		var $duration = WEEK;
		 
		/** Valeur ou objet à mettre en Pelican_Cache */
		function getValue() {
			
			$oConnection = Pelican_Db::getInstance();
			 
			$query = "
				SELECT *
				FROM ".Pelican::$config["FW_MEDIA_TYPE_TABLE_NAME"]." mt,
				".Pelican::$config["FW_MEDIA_EXTENSION_TABLE_NAME"]." me
				WHERE mt.".Pelican::$config["FW_MEDIA_TYPE_FIELD_MEDIA_TYPE_ID"]." = me.".Pelican::$config["FW_MEDIA_EXTENSION_FIELD_MEDIA_TYPE_ID"]."
				AND me.".Pelican::$config["FW_MEDIA_EXTENSION_FIELD_MEDIA_EXTENSION_VISIBLE"]." = 1";
			$this->value = $oConnection->queryTab($query);
		}
	}
	 
?>