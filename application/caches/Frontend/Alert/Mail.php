<?php
	/**
	* @package Pelican_Cache
	* @subpackage Page
	*/

	/**
	* Fichier de Pelican_Cache : pages pour lesquelles ont été séléctionnées des listes de diffusion
	*
	* @package Pelican_Cache
	* @subpackage Page
	* @author Gilles Lenormand <glenormand@businessdecision.com>
	* @since 23/05/2006
	*/
	class Frontend_Alert_Mail extends Pelican_Cache {

		
		var $duration = DAY;

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

			$sSQL = "
				SELECT
				p.PAGE_ID,
				pv.PAGE_TITLE,
				LISTE_DIFF_LABEL
				FROM #pref#_page p
				INNER JOIN #pref#_page_version pv on (p.PAGE_ID=pv.PAGE_ID AND p.LANGUE_ID = pv.LANGUE_ID AND p.PAGE_".$type_version."_VERSION=pv.PAGE_VERSION)
				INNER JOIN #pref#__work ld on (ld.LISTE_DIFF_ID=p.LISTE_DIFF_ID)
				WHERE p.SITE_ID = :SITE_ID
				AND p.LANGUE_ID = :LANGUE_ID
				AND PAGE_STATUS = 1
				ORDER BY PAGE_TITLE";
			$return = $oConnection->queryTab($sSQL, $aBind);

			for($i=0;$i<count($return);$i++){
				$return2[$return[$i]["LISTE_DIFF_LABEL"]]=$return[$i]["PAGE_TITLE"];
			}

			$this->value = $return2;
		}
	}

?>