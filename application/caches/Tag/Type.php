<?php
/**
	* @package Cache
	* @subpackage General
	*/

/**
	* Fichier de Pelican_Cache : Tag de fréquentation du site
	*
	* retour : id, lib
	*
	* @package Cache
	* @subpackage General
	* @author Raphaël Carles <rcarles@businessdecision.com>
	* @since 20/06/2004
	*/
class Tag_Type extends Pelican_Cache {


	var $duration = DAY;

	/** Valeur ou objet à mettre en Pelican_Cache */
	function getValue() {

		$oConnection = Pelican_Db::getInstance();

		$aBind[":SITE_ID"] = $this->params[0];
		$protocole = strtoupper(($this->params[1]?$this->params[1]:"http"));

		$query = "
				SELECT t.TAG_TYPE_".$protocole." as TAG, TAG_CLIENT as CLIENT
				FROM #pref#_tag_type t
				inner join #pref#_site s on (s.TAG_TYPE_ID=t.TAG_TYPE_ID)
				WHERE SITE_ID = :SITE_ID";
		$this->value = $oConnection->queryRow($query, $aBind);

	}
}

?>