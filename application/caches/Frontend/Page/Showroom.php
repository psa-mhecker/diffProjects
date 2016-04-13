<?php
/**
	* Fichier de Pelican_Cache : Chemin de fer
	*
	*
	* @package Pelican_Cache
	* @subpackage Page
	*/
	
class Frontend_Page_Showroom extends Pelican_Cache {
	
	var $duration = DAY;
	/** Valeur ou objet à mettre en Pelican_Cache */
	
	function getValue() {
		
		$oConnection = Pelican_Db::getInstance();

		$iPageId = $this->params[0];
		$aBind[":LANGUE_ID"]=$this->params[1];
		
		if (!empty($this->params[2])) {
			$type_version = $this->params[2];
		} else {
			$type_version = "CURRENT";
		}
		$iCompteur = 0;
		

		while($iPageId != null){
			
			$aParentId[$iCompteur]=$iPageId;

			$aBind[":PAGE_ID"]=$iPageId;
			
			$sSql="SELECT PAGE_TITLE, PAGE_TITLE_BO, PAGE_PARENT_ID, PAGE_CLEAR_URL, PAGE_PICTO_URL,TEMPLATE_PAGE_ID,pv.PAGE_VERSION,pv.LANGUE_ID
				   FROM #pref#_page p
				   INNER JOIN #pref#_page_version pv on (p.PAGE_ID = pv.PAGE_ID AND p.LANGUE_ID = pv.LANGUE_ID AND pv.PAGE_VERSION = p.PAGE_".$type_version."_VERSION)
				   WHERE p.PAGE_ID=:PAGE_ID
				   AND p.LANGUE_ID=:LANGUE_ID";

			$aResult= $oConnection->queryRow($sSql,$aBind);
			
			if(isset($this->params[3])&& $this->params[3]===true ){				
				$aValuePage = $this->getPageVersionData($iPageId,$aResult["LANGUE_ID"],$aResult["PAGE_VERSION"]);
				if(!empty($aValuePage['PAGE_PRIMARY_COLOR']) && !empty($aValuePage['PAGE_PRIMARY_COLOR'])){
					break;
				}
			}elseif(intval($this->params[3])>0 && sizeof($aResult)>0){
				if($this->params[3] == $aResult["TEMPLATE_PAGE_ID"]){
					$aValuePage = $this->getPageVersionData($iPageId,$aResult["LANGUE_ID"],$aResult["PAGE_VERSION"]);
					break;
				}
			}

			$iPageId=$aResult["PAGE_PARENT_ID"];
			$iCompteur++;
		}
		
		
		$this->value = $aValuePage;
		
	}
	
	
	private function getPageVersionData($iPageId,$iLangueId,$iPageVersion){
		
		$oConnection = Pelican_Db::getInstance();
		
		$aBind[":PAGE_ID"]      = $iPageId;
		$aBind[":LANGUE_ID"]    = $iLangueId;
		$aBind[":PAGE_VERSION"] = $iPageVersion;
		
		$sSql=" SELECT pv.PAGE_ID, pv.LANGUE_ID, pv.PAGE_VERSION,pv.PAGE_TITLE,pv.PAGE_PRIMARY_COLOR,pv.PAGE_SECOND_COLOR,pv.PAGE_MODE_AFFICHAGE
				FROM #pref#_page_version pv
				WHERE pv.PAGE_ID=:PAGE_ID
				AND pv.LANGUE_ID=:LANGUE_ID
				AND pv.PAGE_VERSION=:PAGE_VERSION";
			
		$aPageVersionData =$oConnection->queryRow($sSql,$aBind);	
		
		return $aPageVersionData;  
		
	}
}
?>