<?php
	/**
	* @package Cache
	* @subpackage Workflow
	*/
	 
	/**
	* Fichier de Pelican_Cache : Liste des états de publication
	*
	* retour : id, lib
	*
	* @package Cache
	* @subpackage Workflow
	* @author Raphaël Carles <rcarles@businessdecision.com>
	* @since 20/06/2004
	*/
	class State_Publication extends Pelican_Cache {
		 
		/** Valeur ou objet à mettre en Pelican_Cache */
		public function getValue() {
			
			
			$oConnection = Pelican_Db::getInstance();
			$query = "select STATE_ID from #pref#_state where STATE_PUBLICATION=1";
			$oConnection->query($query);
			$this->value = $oConnection->data["STATE_ID"];
		}
	}
?>