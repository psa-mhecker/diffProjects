<?php
	/**
	* @package Pelican_Cache
	* @subpackage Pelican
	*/
	 
	/**
	* Fichier de Pelican_Cache : sous catégorie de contenu en fonction de la catégorie
	*
	* @package Pelican_Cache
	* @subpackage Pelican
	* @author Lenormand Gilles <glenormand@businessdecision.com>
	* @since 01/03/2006
	*/
	class Frontend_Content_SubCategory extends Pelican_Cache {
		 
		
		var $duration = DAY;
		 
		/** Valeur ou objet à mettre en Pelican_Cache */
		function getValue() {
			$aBind = array();
			
			 
			$oConnection = Pelican_Db::getInstance();
			 
			$strSql = "SELECT csc.CONTENT_SUB_CATEGORY_ID,csc.CONTENT_SUB_CATEGORY_LABEL
				FROM #pref#_content_sub_category csc";
			if ($this->params[0] && !$this->params[1]) {
				$aBind[":CONTENT_TYPE_ID"] = $this->params[0];
				$strSql .= "
					inner join #pref#_content_category cc on (csc.CONTENT_CATEGORY_ID=cc.CONTENT_CATEGORY_ID)
					WHERE cc.CONTENT_TYPE_ID= :CONTENT_TYPE_ID";
			} else {
				$aBind[":CONTENT_CATEGORY_ID"] = $this->params[1];
				$strSql .= " WHERE csc.CONTENT_CATEGORY_ID = :CONTENT_CATEGORY_ID";
			}
			$strSql .= " ORDER BY CONTENT_SUB_CATEGORY_LABEL ASC";
			 
			$result = $oConnection->queryTab($strSql, $aBind);
			$result2 = array();
			for($i = 0; $i < count($result); $i++) {
				$result2[$result[$i]["CONTENT_SUB_CATEGORY_ID"]] = $result[$i]["CONTENT_SUB_CATEGORY_LABEL"];
			}
			$this->value = $result2;
		}
	}
?>