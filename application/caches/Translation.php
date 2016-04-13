<?php
class Translation extends Pelican_Cache {
	
	var $duration = WEEK;
	
	/** Valeur ou objet � mettre en Pelican_Cache */
	function getValue() {
		$oConnection = Pelican_Db::getInstance ();
		
		$aBind [":LANGUE_CODE"] = $oConnection->strToBind ( $this->params[0] );
		
		$sSql = "
			select *
			from #pref#_label_langue
			inner join #pref#_language on (#pref#_language.LANGUE_ID=#pref#_label_langue.LANGUE_ID)
			where LANGUE_CODE = :LANGUE_CODE
			order by LABEL_ID
			";
		
		$aResult = $oConnection->queryTab ( $sSql, $aBind );
		if (is_array ( $aResult )) {
			foreach ( $aResult as $result ) {
				$aLabels [$result ["LABEL_ID"]] = $result ["LABEL_TRANSLATE"];
				//$aLabels [$result ["LABEL_ID"]] = array ("LABEL" => $result ["LABEL_TRANSLATE"], "PATH" => $result ["LABEL_PATH"] );
			}
		}
		
		$this->value = $aLabels;
	
	}
}
?>