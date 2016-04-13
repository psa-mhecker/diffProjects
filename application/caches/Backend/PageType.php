<?php
/**
	*
	* @package Cache
	* @subpackage Config
	*/

/**
	* Fichier de Pelican_Cache : Liste des templates de page d'un site
	*
	* @param string $this->params[0] ID du site
	* @package Cache
	* @subpackage Config
	* @author Raphaël Carles <rcarles@businessdecision.com>
	* @since 25/06/2009
	*/
class Backend_PageType extends Pelican_Cache {
		/**
		* * Valeur ou objet à mettre en cache
		*/
	public function getValue() {
		
		$oConnection = Pelican_Db::getInstance();

		$aBind[":SITE_ID"] = $this->params[0];
		$restrict = $this->params[1];

		if ($this->params[1]) {
			/** pour ne pas rejeter le page_type_id en cours d'utilisation */
			$aBind[":TEMPLATE_PAGE_ID"] = $this->params[1];

			/** recherche des templates uniques déjà utilisé */
			$unique = "select DISTINCT pt.PAGE_TYPE_ID from
			#pref#_template_page tp 
			inner join #pref#_page_type pt on (tp.PAGE_TYPE_ID=pt.PAGE_TYPE_ID)
			where tp.SITE_ID=:SITE_ID
			AND PAGE_TYPE_UNIQUE=1
			AND tp.TEMPLATE_PAGE_ID != :TEMPLATE_PAGE_ID";
			$oConnection->query($unique, $aBind);
			$exclude = $oConnection->data['PAGE_TYPE_ID'];
		}

		$strSqlPage = "select *
				from
				#pref#_page_type";

		if ($exclude) {
			$strSqlPage .= " WHERE PAGE_TYPE_ID not in (".implode(',',$exclude).") ";
		}

		$strSqlPage .= " order by PAGE_TYPE_LABEL";
		$resultat = $oConnection->queryTab($strSqlPage, $aBind);

		$this->value = $resultat;
	}
}
?>