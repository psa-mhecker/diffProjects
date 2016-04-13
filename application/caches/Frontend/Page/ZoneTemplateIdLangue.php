<?php

/**
 * Fichier de Pelican_Cache : récupération des valeurs d'une Pelican_Index_Frontoffice_Zone spécifique dans un gabarit
 *
 * retour : id, lib
 *
 * @package Pelican_Cache
 * @subpackage Page
 * @author Joseph Franclin <joseph.frnaclin@businessdecision.com>
 * @since 25/02/2006
 */
class Frontend_Page_ZoneTemplateIdLangue extends Pelican_Cache
{
    var $duration = HOUR;
	

    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue()
    {
        
        $oConnection = Pelican_Db::getInstance();
        
        $aBind[":SITE_ID"] = $this->params[0];
        $aBind[":ZONE_TEMPLATE_ID"] = $this->params[1];;
        $aBind[":LANGUE_ID"] = $this->params[2];
        
        $sSQL = "
				SELECT
					pz.*
				FROM
				#pref#_page_zone pz, #pref#_page p
				WHERE
				p.SITE_ID = :SITE_ID
                AND p.PAGE_STATUS = 1
                AND pz.PAGE_ID = p.PAGE_ID
				AND pz.PAGE_VERSION = p.PAGE_CURRENT_VERSION
				AND pz.ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
				AND pz.LANGUE_ID = :LANGUE_ID";
        $this->value = $oConnection->queryRow($sSQL, $aBind);
    }
}
?>