<?php
	/**
	* @package Pelican_Cache
	* @subpackage Config
	*/

	/**
	* Fichier de Pelican_Cache : Récupération d'une zone_template pour une page donnée et une Pelican_Index_Frontoffice_Zone
	*
	* @package Pelican_Cache
	* @subpackage Config
	* @author Raphaël Carles <rcarles@businessdecision.com>
	* @since 27/09/2004
	*/
class ZoneTemplate extends Pelican_Cache
{

    public static $storage = 'file';

		/** Valeur ou objet à mettre en Pelican_Cache */
    function getValue()
    {
			
			$oConnection = Pelican_Db::getInstance();
//ML//
			$aBind[":PAGE_ID"] = $this->params[0];
			$aBind[":ZONE_ID"] = $this->params[1];
			if ($this->params[2]) {
				$type_version = $this->params[2];
			} else {
				$type_version = "CURRENT";
			}

			$strSqlPage = "select
				#pref#_page_version.PAGE_VERSION,
				#pref#_zone_template.ZONE_TEMPLATE_ID
				from
				#pref#_page,
				#pref#_page_version,
				#pref#_zone_template
				where
				#pref#_page.PAGE_ID = :PAGE_ID
				AND #pref#_page.PAGE_ID = #pref#_page_version.PAGE_ID
				AND #pref#_page.LANGUE_ID = #pref#_page_version.LANGUE_ID
				AND #pref#_page.PAGE_".$type_version."_VERSION = #pref#_page_version.PAGE_VERSION
				AND #pref#_page_version.TEMPLATE_PAGE_ID = #pref#_zone_template.TEMPLATE_PAGE_ID
				AND #pref#_zone_template.ZONE_ID = :ZONE_ID";

			$this->value = $oConnection->queryRow($strSqlPage, $aBind);
		}
	}
?>