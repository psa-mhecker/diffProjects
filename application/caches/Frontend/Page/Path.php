<?php
/**
	* Fichier de Pelican_Cache : Chemin de fer
	*
	* retour : id, lib
	*
	* @package Pelican_Cache
	* @subpackage Page
	* @author Gilles Lenormand <glenormand@businessdecision.fr>
	* @since 25/02/2006
	*/
class Frontend_Page_Path extends Pelican_Cache {
	
	var $duration = DAY;
	/** Valeur ou objet à mettre en Pelican_Cache */
	function getValue() {
		
		$oConnection = Pelican_Db::getInstance();

		$id = $this->params[0];
		$aBind[":LANGUE_ID"]=$this->params[1];
		if (!empty($this->params[2])) {
			$type_version = $this->params[2];
		} else {
			$type_version = "CURRENT";
		}

		$aParentName=array();
		$aParentUrl=array();
		$aParentId=array();
		$aIndex=array();
		$aParentShortName=array();
		$index=0;

		while($id != null){
			#retrouver la rubrique parente
			$aParentId[$index]=$id;

			$aBind[":PAGE_ID"]=$id;
			$sSQL="select PAGE_TITLE, PAGE_TITLE_BO, PAGE_PARENT_ID, PAGE_CLEAR_URL, PAGE_PICTO_URL
			from #pref#_page p
			INNER JOIN #pref#_page_version pv on (p.PAGE_ID = pv.PAGE_ID AND p.LANGUE_ID = pv.LANGUE_ID AND pv.PAGE_VERSION = p.PAGE_".$type_version."_VERSION)
			where p.PAGE_ID=:PAGE_ID
			AND p.LANGUE_ID=:LANGUE_ID";

			$aResult=$oConnection->queryRow($sSQL,$aBind);

			$id=$aResult["PAGE_PARENT_ID"];
			$sNomCourtRubrique=($aResult["PAGE_TITLE_BO"]?$aResult["PAGE_TITLE_BO"]:$aResult["PAGE_TITLE"]);
			$sNomRubrique=$aResult["PAGE_TITLE"];
			$sClearUrl=$aResult["PAGE_CLEAR_URL"];
			#Ajouter la rubrique parente au tableau
			$aParentName[$index]=$sNomRubrique;
			$aParentShortName[$index]=$sNomCourtRubrique;
			$aParentUrl[$index]=$sClearUrl;
			$aIndex[$index]=$index;
			$index++;
		}
		$this->value = array($aParentName,$aParentShortName,$aParentId,$aParentUrl,$aIndex);
	}
}
?>