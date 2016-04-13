<?php
	/**
	* @package Cache
	* @subpackage Config
	*/

	/**
	* Fichier de Pelican_Cache : Résultat de requête sur PAGE_ZONE_MULTI
	*
	* retour : id, lib
	*
	* @package Cache
	* @subpackage Config
	* @author Sebastien Maillot <sebastien.maillot@businessdecision.com>
	* @since 09/07/2013
	*/
	class Frontend_ZoneMulti extends Pelican_Cache {


		var $duration = DAY;

		/** Valeur ou objet à mettre en Pelican_Cache */
		function getValue() {
			$oConnection = Pelican_Db::getInstance();

			$aBind = array();
        	$aBind[":PAGE_ID"] = $this->params[0];
        	$aBind[":LANGUE_ID"] = $this->params[1];
        	$aBind[":PAGE_VERSION"] = $this->params[2];
        	$aBind[":ZONE_TEMPLATE_ID"] = $this->params[3];
        	$aBind[":PAGE_ZONE_MULTI_TYPE"] = $oConnection->strToBind($this->params[4]);
                
	        $query = "
	        select *,
	        ".$oConnection->dateSqlToString("PAGE_ZONE_MULTI_DATE_BEGIN", true)." AS PAGE_ZONE_MULTI_DATE_BEGIN,
	        ".$oConnection->dateSqlToString("PAGE_ZONE_MULTI_DATE_END", true)." AS PAGE_ZONE_MULTI_DATE_END
	        from
	            #pref#_page_zone_multi
	        where
	            PAGE_ID = :PAGE_ID
	            and LANGUE_ID = :LANGUE_ID
	            and PAGE_VERSION = :PAGE_VERSION
	            and ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
                and PAGE_ZONE_MULTI_TYPE = :PAGE_ZONE_MULTI_TYPE
	            ORDER BY PAGE_ZONE_MULTI_ID
	        ";
	        $this->value = $oConnection->queryTab($query, $aBind);
		}
	}
?>