<?php

/**
 * @package Cache
 * @subpackage Config
 */

/**
 * Fichier de Pelican_Cache : Valeurs initiale d'un site
 *
 * retour : id, lib
 *
 * @package Cache
 * @subpackage Config
 * @author Raphaël Carles <rcarles@businessdecision.com>, Laurent Franchomme <laurent.franchomme@businessdecision.com>
 * @since 10/01/2006, 27/05/08 (LFR) Ajout du param 1, langue_id
 */
class Frontend_Site_Init extends Pelican_Cache {
	
	public static $storage = 'file';
	
	/** Valeur ou objet à mettre en Pelican_Cache */
	function getValue() {
		
		$oConnection = Pelican_Db::getInstance ();
		
		$site_host = parse_url ( $this->params [0] );
		
		$aBind [":SITE_DNS"] = $oConnection->strToBind ( $this->params [0] );
		$aBind [":LANGUE_ID"] = ($this->params [1] ? $this->params [1] : 'LANGUE_ID');
		
		/** recherche des paramètres de DNS s'ils existent */
		$query = "SELECT #pref#_site_parameter.site_id, #pref#_site_parameter.site_parameter_id,
				#pref#_site_parameter.site_parameter_value, home_zone_template_id,
				navigation_page_id
				FROM #pref#_site_parameter, #pref#_site_parameter_dns
				WHERE #pref#_site_parameter.site_id = #pref#_site_parameter_dns.site_id
				AND #pref#_site_parameter.site_parameter_id = #pref#_site_parameter_dns.site_parameter_id
				AND #pref#_site_parameter.site_parameter_value = #pref#_site_parameter_dns.site_parameter_value
				AND SITE_DNS = :SITE_DNS";
		$_DNS_SPECIFIQUE = $oConnection->queryRow ( $query, $aBind );
		
		/** Contrôle du DNS réel */
		$query = "
				SELECT SITE_URL, SITE_PRESERVE_DNS
				FROM #pref#_site,
				#pref#_site_dns
				WHERE
				#pref#_site.SITE_ID=#pref#_site_dns.SITE_ID
				AND SITE_DNS = :SITE_DNS";
		$site_url = $oConnection->queryRow ( $query, $aBind );
		
		$aBind [":SITE_URL"] = $oConnection->strToBind ( $site_url ["SITE_URL"] );
		
		$query = "
				SELECT #pref#_page.*,
				#pref#_site_code.SITE_CODE_PAYS,
				#pref#_site.SITE_HOMEPAGE_REDIRECT 
				FROM #pref#_site
				INNER JOIN #pref#_page ON (#pref#_page.SITE_ID=#pref#_site.SITE_ID)
				LEFT JOIN #pref#_site_code ON (#pref#_site_code.SITE_ID=#pref#_site.SITE_ID)
				WHERE
				
				PAGE_PARENT_ID IS NULL
				AND SITE_URL = :SITE_URL
				AND LANGUE_ID = :LANGUE_ID ";
		if ($_DNS_SPECIFIQUE) {
			$query .= " AND (PAGE_ID IN (SELECT PAGE_ID FROM #pref#_page_version WHERE TEMPLATE_PAGE_ID = " . $_DNS_SPECIFIQUE ["HOME_ZONE_TEMPLATE_ID"] . ") OR PAGE_ID=" . $_DNS_SPECIFIQUE ["NAVIGATION_PAGE_ID"] . ")";
		}
		$query .= " ORDER BY PAGE_GENERAL, PAGE_ID";
		
		$tmp = $oConnection->queryTab ( $query, $aBind );
		
		$this->value = $tmp [0];
		$this->value ["NAVIGATION_ID"] = $tmp [count ( $tmp ) - 1] ["PAGE_ID"];
		$this->value ["NAVIGATION_CURRENT_VERSION"] = $tmp [count ( $tmp ) - 1] ["PAGE_CURRENT_VERSION"];
		$this->value ["NAVIGATION_DRAFT_VERSION"] = $tmp [count ( $tmp ) - 1] ["PAGE_DRAFT_VERSION"];
		if (! empty ( $_DNS_SPECIFIQUE ["SITE_PARAMETER_VALUE"] )) {
			$this->value [$_DNS_SPECIFIQUE ["SITE_PARAMETER_ID"]] = $_DNS_SPECIFIQUE ["SITE_PARAMETER_VALUE"];
		}
	}
}

?>