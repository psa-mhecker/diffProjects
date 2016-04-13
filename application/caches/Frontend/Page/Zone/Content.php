<?php
	/**
	* @package Cache
	* @subpackage Page
	*/
	 
	/**
	* Fichier de Pelican_Cache : tous les contenus associés à une Pelican_Index_Frontoffice_Zone de page
	*
	* retour : id, lib
	*
	* @package Cache
	* @subpackage Page
	* @author <glenormand@businessdecision.fr>
	* @since 24/04/2007
	*/
	class Frontend_Page_Zone_Content extends Pelican_Cache {
		
		
		var $duration = DAY;
		 
		/** Valeur ou objet à mettre en Pelican_Cache */
		function getValue() {
			
			$oConnection = Pelican_Db::getInstance();
			$aBind[":PAGE_ID"] = $this->params[0];
			$aBind[":LANGUE_ID"] = $this->params[1];
			$aBind[":PAGE_VERSION"] = $this->params[2];
			$aBind[":ZONE_TEMPLATE_ID"] = $this->params[3];
			if($this->params[4]){
				$sCibleId = $this->params[4];
			}
			


			//Récupération du contenu associé à la zone
			$sSqlList = " select c.CONTENT_ID,cv.CONTENT_TITLE,cv.CONTENT_CLEAR_URL,cv.CONTENT_SHORTTEXT from
				#pref#_content c,#pref#_page_zone_content cpz,#pref#_content_version cv ";
			if($this->params[4]){
				$sSqlList .=" inner join #pref#_content_cible cc on (cc.CONTENT_ID=cv.CONTENT_ID AND cc.CONTENT_VERSION=cv.CONTENT_VERSION AND cc.LANGUE_ID=cv.LANGUE_ID AND cc.CIBLE_TYPE_ID=".Pelican::$config["VISUALISATION"]." AND cc.CIBLE_ID in ".$sCibleId.")";
			}
			$sSqlList .=" WHERE cpz.PAGE_ID            = :PAGE_ID
				AND   cpz.LANGUE_ID       = :LANGUE_ID
				AND   cpz.ZONE_TEMPLATE_ID       = :ZONE_TEMPLATE_ID
				AND   cpz.PAGE_VERSION          = :PAGE_VERSION
				AND   c.CONTENT_ID                    = cpz.CONTENT_ID 
				AND   c.CONTENT_ID                    = cv.CONTENT_ID 
				AND   c.CONTENT_CURRENT_VERSION 			= cv.CONTENT_VERSION";
			$result = $oConnection->queryTab($sSqlList, $aBind);
			
			$result2=array();
		$i=0;
		if($result){
			
		foreach($result as $res){
			if($i==0 || ($i>0 && $result2[$i-1]["CONTENT_ID"]!=$res["CONTENT_ID"])){
				$result2[$i]=$res;
				$i++;
			}
		}
		}
			
			$this->value = $result2;
		}
		 
	}
?>