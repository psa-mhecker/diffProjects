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
	class Frontend_Site_Tree extends Pelican_Cache {

		
		var $duration = WEEK;

		/** Valeur ou objet à mettre en Pelican_Cache */
		function getValue() {
			
			$oConnection = Pelican_Db::getInstance();

			$aBind[":SITE_ID"] = $this->params[0];
			$aBind[":LANGUE_ID"] = $this->params[1];

			if ($this->params[2]) {
				$type_version = $this->params[2];
			} else {
				$type_version = "CURRENT";
			}
			$plan_du_site = $this->params[3];

			if ($this->params[4]) {
				$in = explode('#',trim($this->params[4],'#'));
			}

			$strSqlPage = "
				SELECT
					p.PAGE_ID as \"id\",
					PAGE_PARENT_ID as \"pid\",
					PAGE_TITLE_BO as \"lib\",
					PAGE_ORDER as \"order\",
					PAGE_CLEAR_URL as \"url\",
					PAGE_VERSION_UPDATE_DATE as \"lastmod\",
					PAGE_PRIORITY as \"priority\"
				FROM
				#pref#_page p
				INNER JOIN #pref#_page_version pv on (p.PAGE_ID = pv.PAGE_ID AND p.LANGUE_ID = pv.LANGUE_ID AND pv.PAGE_VERSION = p.PAGE_".$type_version."_VERSION)
				WHERE SITE_ID=:SITE_ID
					AND (PAGE_GENERAL IS NULL OR PAGE_GENERAL=0)
					AND PAGE_STATUS = 1
					AND PAGE_DISPLAY = 1 ";
					if (!$plan_du_site) {
							$strSqlPage .= " AND PAGE_DISPLAY_NAV = 1 ";
					}
					
					if ($in) {
						$strSqlPage .= " AND (p.PAGE_PARENT_ID IS NULL OR p.PAGE_PARENT_ID in (".implode(",",$in)."))";
					}
					$strSqlPage .= " AND p.LANGUE_ID=:LANGUE_ID
				ORDER BY PAGE_ORDER";

			$MENU = $oConnection->queryTab($strSqlPage, $aBind);

			$oTree = Pelican_Factory::getInstance('Hierarchy',"nav", "id", "pid");
			$oTree->addTabNode($MENU);
			$oTree->setOrder("order", "ASC");

			$this->value = $oTree;
		}
	}
