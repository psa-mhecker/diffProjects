<?php
/**
 * Fichier de Pelican_Cache : récupération des valeurs d'une Pelican_Index_Frontoffice_Zone spécifique dans un gabarit
 *
 * retour : id, lib
 *
 * @package Pelican_Cache
 * @subpackage Page
 * @author Raphaël Carles <rcarles@businessdecision.fr>
 * @since 25/02/2006
 */
class Frontend_Page_ZoneTemplateId extends Pelican_Cache {
	
	var $duration = WEEK;
	/** Valeur ou objet à mettre en Pelican_Cache */
	function getValue() {
		
		$oConnection = Pelican_Db::getInstance ();
		
		$aBind [":PAGE_ID"] = $this->params [0];
		$aBind [":ZONE_TEMPLATE_ID"] = $this->params [1];
		if ($this->params [2]) {
			$type_version = $this->params [2];
		} else {
			$type_version = "CURRENT";
		}
		$aBind [":LANGUE_ID"] = $this->params [3];
		
		$sSQL = "
				SELECT
				pz.*,czt.*, p.PAGE_PARENT_ID 
				FROM
				#pref#_page_zone pz
				inner join #pref#_page p on (pz.PAGE_ID=p.PAGE_ID)		
				LEFT JOIN #pref#_zone_layout czt on (czt.ZONE_LAYOUT_ID=pz.ZONE_LAYOUT_ID)
				WHERE
				pz.PAGE_ID = :PAGE_ID
				AND pz.PAGE_VERSION = p.PAGE_" . $type_version . "_VERSION
				AND pz.ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
				AND p.LANGUE_ID = :LANGUE_ID";
		$this->value = $oConnection->queryRow ( $sSQL, $aBind );
	
	}
}
?>