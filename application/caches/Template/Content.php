<?php
/**
	*
	* @package Cache
	* @subpackage Config
	*/

/**
	* Fichier de Pelican_Cache : Template de contenu
	*
	* @param string $this->params[0] ID du site
	* @package Cache
	* @subpackage Config
	* @author Raphaël Carles <rcarles@businessdecision.com>
	* @since 22/11/2006
	*/
class Template_Content extends Pelican_Cache {
	/**
		* * Durée de vie
		*/
	var $duration = WEEK;

	/**
		* * Valeur ou objet à mettre en cache
		*/
	function getValue() {
		
		$oConnection = Pelican_Db::getInstance();

		$aBind[":SITE_ID"] = $this->params[0];

		$strSqlContent = "select
				*
				from
				#pref#_template_page tp
				inner join #pref#_page_type pt on (tp.PAGE_TYPE_ID=pt.PAGE_TYPE_ID)
				where SITE_ID = :SITE_ID
				AND PAGE_TYPE_CODE='CONTENT'";

		$this->value = $oConnection->queryRow($strSqlContent, $aBind);
	}
}

?>