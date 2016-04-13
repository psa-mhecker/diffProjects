<?php
	/**
	* @package Pelican_Cache
	* @subpackage Page
	*/

	/**
	* Fichier de Pelican_Cache : retourne une page ayant un template de page donné
	*
	* @package Pelican_Cache
	* @subpackage Page
	* @author Gilles Lenormand <glenormand@businessdecision.com>
	* @since 23/05/2006
	*/
	class Frontend_Page_Template extends Pelican_Cache {

		
		var $duration = DAY;
		
		public $isPersistent = true;

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
                        
                        // conditionner sur le PAGE_STATUS
                        // sans contrôler si en mode prévisu
                        if($type_version == 'CURRENT'){ // mode normal
                            $cond_status = "p.PAGE_STATUS = 1";
                        }else{ // mode prévisu
                            $cond_status = "1 = 1";
                        }
                        
			$aBind[":TEMPLATE_PAGE_ID"] = $this->params[3];


			$sSQL = "
				SELECT
				p.PAGE_ID,
				pv.PAGE_TITLE,
				pv.PAGE_CLEAR_URL,
				pv.PAGE_VERSION,
				pv.TEMPLATE_PAGE_ID,
				p.PAGE_PATH
				FROM #pref#_page p
				INNER JOIN #pref#_page_version pv on (p.PAGE_ID=pv.PAGE_ID AND p.LANGUE_ID = pv.LANGUE_ID AND p.PAGE_".$type_version."_VERSION=pv.PAGE_VERSION)
				WHERE p.SITE_ID = :SITE_ID
				AND p.LANGUE_ID = :LANGUE_ID
				AND pv.TEMPLATE_PAGE_ID = :TEMPLATE_PAGE_ID
				AND $cond_status";

			$sSQL = $oConnection->getLimitedSql($sSQL, 1, 1, true, $aBind);
			$return = $oConnection->queryRow($sSQL, $aBind);



			$this->value = $return;
		}
	}

?>