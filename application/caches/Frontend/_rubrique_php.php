<?php
	/**
	* @package Cache
	* @subpackage Page
	*/

	pelican_import('Hierarchy');

	/**
	* Fichier de Pelican_Cache : Hiérarchie des pages d'un site
	* @param string $this->params[0] ID du site
	*
	* @package Cache
	* @subpackage Page
	* @author Raphaël Carles <rcarles@businessdecision.com>
	* @since 02/09/2004
	*/
	class rubrique_php extends Pelican_Cache {

		
		var $duration = WEEK;

		/** Valeur ou objet à mettre en Pelican_Cache */
		function getValue() {
			
			$oConnection = Pelican_Db::getInstance();

			$aBind[":SITE_ID"] = $this->params[0];
			$aBind[":LANGUE_ID"] = $this->params[1];

			$strSqlPage = "
				SELECT
				p.PAGE_ID as \"id\",
				PAGE_PARENT_ID as \"pid\",
				PAGE_TITLE_BO as \"lib\",
				PAGE_ORDER as \"order\"
				FROM
				#pref#_page p
				INNER JOIN #pref#_page_version pv on (p.PAGE_ID = pv.PAGE_ID AND p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION AND p.LANGUE_ID = pv.LANGUE_ID)
				WHERE p.SITE_ID=:SITE_ID
				AND p.LANGUE_ID=:LANGUE_ID
				AND (PAGE_GENERAL=0 OR PAGE_GENERAL IS NULL)";

			$MENU = $oConnection->queryTab($strSqlPage, $aBind);

			$oTree = Pelican_Factory::getInstance('Hierarchy',"header", "id", "pid");
			$oTree->addTabNode($MENU);
			$oTree->setOrder("order", "ASC");
			$i = -1;
			foreach($oTree->aNodes as $menu) {
				$aMenu[] = getTreeParams($menu);
			}
			$this->value = $aMenu;
		}
	}
 ?>