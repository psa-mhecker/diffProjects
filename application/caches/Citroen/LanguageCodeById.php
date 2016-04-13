<?php
/**
 * @package Cache
 * @subpackage Pelican
 */

/**
 * Fichier de Pelican_Cache : langues
 *
 * @package Cache
 * @subpackage Pelican
 * @author Moate david <david.moate@businessdecision.com>
 * @since 11/06/2013
 */
class Citroen_LanguageCodeById extends Pelican_Cache {
	
	var $duration = DAY;
	
	/** Valeur ou objet � mettre en Pelican_Cache */
	function getValue() {
				
		$oConnection = Pelican_Db::getInstance ();
		
		$aBind[":LANGUE_ID"] = $this->params[0];
		
		$sqlQuery = "select * from #pref#_language where LANGUE_ID= :LANGUE_ID";
		
		$result = $oConnection->queryRow ( $sqlQuery );
		
		$this->value = $result['LANGUE_CODE'];
	}
}