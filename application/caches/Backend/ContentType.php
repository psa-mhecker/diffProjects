<?php
	/**
	* @package Pelican_Cache
	* @subpackage General
	*/

	/**
	* Fichier de Pelican_Cache : Résultat de requête sur content_type
	* - Sans paramètre : liste des types de contenus
	* - 1 paramètre : liste des types de contenu d'un site (SITE_ID)
	* - 2 paramètres : liste des types de contenu d'un site (SITE_ID), en émission ("EMISSION") ou en réception ("RECEPTION")
	* - 3 paramètres : liste des types de contenu d'un site (SITE_ID), en émission ("EMISSION") ou en réception ("RECEPTION"), type de contenu
	* retour : id, lib, emission, reception
	*
	* @package Pelican_Cache
	* @subpackage General
	* @author Raphaël Carles <rcarles@businessdecision.com>
	* @since 20/06/2004
	*/
	class Backend_ContentType extends Pelican_Cache {

		public function getValue() {
			
			$oConnection = Pelican_Db::getInstance();
			if ($this->params) {
				// le champ SITE est renseigné
				$query = "SELECT
					#pref#_content_type.CONTENT_TYPE_ID as \"id\",
					CONTENT_TYPE_LABEL as \"lib\",
					CONTENT_TYPE_SITE_EMISSION as \"emission\",
					CONTENT_TYPE_SITE_EMISSION as \"reception\"
					FROM
					#pref#_content_type,
					#pref#_content_type_site
					WHERE
					#pref#_content_type.CONTENT_TYPE_ID = #pref#_content_type_site.CONTENT_TYPE_ID
					AND SITE_ID = ".$this->params[0]."
					AND  (CONTENT_TYPE_PAGE IS NULL OR CONTENT_TYPE_PAGE=0)";
				if ($this->params[1]) {
					$query .= " AND CONTENT_TYPE_SITE_".$this->params[1]."=1";
				}
				if ($this->params[2]) {
					$query .= " AND #pref#_content_type.CONTENT_TYPE_ID in (".$this->params[2].")";
				}
				/** Type de dontenus masqués */
				if ($this->params[3]) {
					$query .= " AND (#pref#_content_type.CONTENT_TYPE_ADMINISTRATION IS NULL OR #pref#_content_type.CONTENT_TYPE_ADMINISTRATION!=1)";
				}
			} else {
				$query = "SELECT
					CONTENT_TYPE_ID as \"id\",
					CONTENT_TYPE_LABEL as \"lib\"
					FROM
					#pref#_content_type
					WHERE CONTENT_TYPE_PAGE IS NULL OR CONTENT_TYPE_PAGE=''";
			}
			$query .= " ORDER BY
				CONTENT_TYPE_LABEL";
			$this->value = $oConnection->queryTab($query);
		}
	}

?>