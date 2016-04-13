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
class Frontend_Page_ZoneTemplateByPageId extends Pelican_Cache
{
    var $duration = WEEK;

    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue()
    {
        
        $oConnection = Pelican_Db::getInstance();
        
        $aBind[":PAGE_ID"] = $this->params[0];
        $aBind[":PAGE_VERSION"] = $this->params[1];
        $aBind[":ZONE_TEMPLATE_ID"] = $this->params[2];
        $aBind[":LANGUE_ID"] = $this->params[3];
		// Pour Modification de la requette
			$aBind[":AREA_ID"] = $this->params[4];
			$aBind[":ZONE_ORDER"] = $this->params[5];
        
		$table = '#pref#_page_zone';
		$where = ' and ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID';
		if ($aBind[":ZONE_ORDER"] != '') {
			$table = '#pref#_page_multi_zone';
			$where = ' and AREA_ID = :AREA_ID
				and ZONE_ORDER = :ZONE_ORDER';
		}
		
        $sSQL = "
				SELECT
					*
				FROM
					" . $table . "
				WHERE
					PAGE_ID = :PAGE_ID
					and LANGUE_ID = :LANGUE_ID
					and PAGE_VERSION = :PAGE_VERSION
					" . $where . "";
        $this->value = $oConnection->queryRow($sSQL, $aBind);
    }
}
?>