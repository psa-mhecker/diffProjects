<?php
	/**
	* @package Cache
	* @subpackage General
	*/
	 
	/**
	* Fichier de Pelican_Cache : URL front d'un site en fonction du DNS
	*
	* retour : id, lib
	*
	* @package Cache
	* @subpackage General
	* @author Raphaël Carles <rcarles@businessdecision.com>
	* @since 10/01/2006
	*/
class Frontend_Site_Url extends Pelican_Cache
{
		 
    public static $storage = 'file';
		 
		/** Valeur ou objet à mettre en Pelican_Cache */
    function getValue()
    {
			
			$oConnection = Pelican_Db::getInstance();
			$site_url = parse_url($this->params[0]);
			 
			$aBind[":V1"] = $oConnection->strToBind($this->params[0]);
			 
			$query = "
				SELECT #pref#_site.SITE_ID,
				" . $oConnection->getCaseClause("SITE_PRESERVE_DNS", array(
            "1" => "'" . $this->params[0] . "'"
        ), "SITE_URL") . "  as \"SITE_URL\"
				FROM #pref#_site,
				#pref#_site_dns
				WHERE
				#pref#_site.SITE_ID=#pref#_site_dns.SITE_ID
				AND SITE_DNS = :V1";
			$this->value = $oConnection->queryRow($query, $aBind);
		}
	}
	 
?>