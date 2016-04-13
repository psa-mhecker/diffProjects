<?php
	/**
	* @package Pelican_Cache
	* @subpackage Page
	*/
	 
	 
	/**
	* Fichier de Pelican_Cache : Récupération des liens vers les contenus associés à une Page intérieure
	*
	* retour : PAGE_ID, SITE_ID, LANGUE_ID,  prévisu ou non
	*
	* @package Pelican_Cache
	* @subpackage Page
	* @author Lenormand Gilles <glenormand@businessdecision.com>
	* @since 07/05/2007
	*/
	class Frontend_Page_Content extends Pelican_Cache {
		 
		
		var $duration = DAY;
		 
		/** Valeur ou objet à mettre en Pelican_Cache */
		function getValue() {
			
			$oConnection = Pelican_Db::getInstance();
			 
			$i = 0;
			$aBind[":PAGE_ID"] = $this->params[0];
			$aBind[":SITE_ID"] = $this->params[1];
			$aBind[":LANGUE_ID"] = $this->params[2];
			if ($this->params[3]) {
				$type_version = $this->params[3];
			} else {
				$type_version = "CURRENT";
			}
			if ($this->params[4]) {
				$sTypeContent = $this->params[4];
				$aBind[":CONTENT_TYPE_ID"] = $sTypeContent;
				$contentFilter = "AND po.PAGE_ORDER_TYPE=c.CONTENT_TYPE_ID";
			}
			if ($this->params[5]) {
				$limit = $this->params[5];
			}
			
			 
			/** récupération des contenus liés à la rubrique */
			$sSQL = "
				SELECT
				c.CONTENT_ID,
				CONTENT_TITLE,
				CONTENT_TITLE_BO,
				CONTENT_CLEAR_URL,
				CONTENT_PICTO_URL,
				CONTENT_TITLE_URL,
				STATE_ID,
				CONTENT_VERSION,
				MEDIA_PATH,
				MEDIA_ALT,
				DOC_ID,
				CONTENT_SHORTTEXT,
				cv.PAGE_ID,
				CONTENT_EXTERNAL_LINK
				FROM #pref#_content c
				INNER JOIN #pref#_content_version cv on (c.CONTENT_ID=cv.CONTENT_ID AND c.LANGUE_ID = cv.LANGUE_ID AND c.CONTENT_".$type_version."_VERSION=cv.CONTENT_VERSION)
				LEFT JOIN #pref#_page_order po on (po.PAGE_ID=:PAGE_ID ".$contentFilter." AND po.LANGUE_ID = c.LANGUE_ID AND po.PAGE_ORDER_ID=c.CONTENT_ID)
				LEFT JOIN #pref#_media m on (m.MEDIA_ID=cv.MEDIA_ID)";
			if (isset($sTypeContent)) {
				$sSQL .= " INNER JOIN #pref#_content_type ct on (ct.CONTENT_TYPE_ID=c.CONTENT_TYPE_ID)";
			}
			
			 
			$sSQL .= "
				WHERE
				cv.PAGE_ID = :PAGE_ID
				";
			/*
			if ($aBind[":PAGE_ID"] == 1) {
			$sSQL .= " cv.CONTENT_DIRECT_HOME = 1 ";
			} else {
			$sSQL .= " cv.CONTENT_DIRECT_PAGE = 1 AND cv.PAGE_ID = :PAGE_ID ";
			}
			*/
			$sSQL .= " AND c.SITE_ID = :SITE_ID
				AND c.LANGUE_ID = :LANGUE_ID
				AND CONTENT_STATUS=1";
			 
			if (isset($sTypeContent)) {
				$sSQL .= " AND ct.CONTENT_TYPE_ID = :CONTENT_TYPE_ID";
			}
			 
			/** filtre sur les droits */
			if ($this->params[9]) {
				$sSQL .= " AND CONTENT_CREATION_USER like '%#".$this->params[9]."#%'";
			}
			 
			$sSQL .= " ORDER BY cv.CONTENT_PUBLICATION_DATE DESC,PAGE_ORDER, CONTENT_ID DESC";
			 
			 
			if (isset($start) && isset($limit)) {
				$sSQL = $oConnection->getLimitedSql($sSQL, $start, $limit, true, $aBind);
			} elseif (isset($limit)) {
				$sSQL = $oConnection->getLimitedSql($sSQL, 1, $limit, true, $aBind);
			}
			$result = $oConnection->queryTab($sSQL, $aBind);
			 
			$current_exists = false;
			if ($result) {
				foreach($result as $value) {
					if ($i == 0 || ($i > 0 && $value["CONTENT_ID"] != $contenu[$i-1]["ID"])) {
						/** cas particulier des liens vers des documents mais avec résumé */
						if ($value["CONTENT_EXTERNAL_LINK"] && $value["CONTENT_EXTERNAL_LINK"] != $value["CONTENT_CLEAR_URL"] && substr($value["CONTENT_CLEAR_URL"], 0, 1) == "/") {
							$value["CONTENT_TITLE_URL"] = "";
						}
						 
						$contenu[$i]["ID"] = $value["CONTENT_ID"];
						$contenu[$i]["TITLE"] = $value["CONTENT_TITLE"];
						$contenu[$i]["SHORT_TITLE"] = $value["CONTENT_TITLE_BO"];
						$contenu[$i]["URL"] = ($value["CONTENT_CLEAR_URL"]?$value["CONTENT_CLEAR_URL"]:makeClearUrl($value["CONTENT_ID"], "cid", $value["CONTENT_TITLE_BO"]));
						$contenu[$i]["PICTO"] = $value["CONTENT_PICTO_URL"];
						$contenu[$i]["TITLE_URL"] = strip_tags($value["CONTENT_TITLE_URL"]);
						$contenu[$i]["TYPE"] = "CONTENT";
						$contenu[$i]["STATE"] = $value["STATE_ID"];
						$contenu[$i]["VERSION"] = $value["CONTENT_VERSION"];
						$contenu[$i]["MEDIA_PATH"] = Pelican_Media::getFileNameMediaFormat($value["MEDIA_PATH"], $mediaFormat);
						$contenu[$i]["MEDIA_ALT"] = $value["MEDIA_ALT"];
						$contenu[$i]["DOC_ID"] = $value["DOC_ID"];
						$contenu[$i]["PAGE_ID"] = $value["PAGE_ID"];
						$contenu[$i]["CONTENT_SHORTTEXT"] = $value["CONTENT_SHORTTEXT"];
						$contenu[$i]["CONTENT_EXTERNAL_LINK"] = $value["CONTENT_EXTERNAL_LINK"];
						$contenu[$i]["CURRENT"] = ($current == $value["CONTENT_ID"]);
						if ($contenu[$i]["CURRENT"]) {
							$current_exists = true;
						}
						$i++;
					}
				}
			}
			 
			/** contenu courant, s'il n'est pas dans le résultat */
			if ($current && !$current_exists) {
				$aBind[":CONTENT_ID"] = $current;
				 
				$sSQL = "
					SELECT
					c.CONTENT_ID,
					CONTENT_TITLE,
					CONTENT_TITLE_BO,
					CONTENT_CLEAR_URL,
					CONTENT_PICTO_URL,
					CONTENT_TITLE_URL,
					STATE_ID,
					PAGE_ID,
					CONTENT_VERSION
					FROM #pref#_content c
					INNER JOIN #pref#_content_version cv on (c.CONTENT_ID=cv.CONTENT_ID AND c.LANGUE_ID = cv.LANGUE_ID AND c.CONTENT_".$type_version."_VERSION=cv.CONTENT_VERSION)
					WHERE c.SITE_ID = :SITE_ID
					AND c.CONTENT_ID = :CONTENT_ID
					AND c.LANGUE_ID = :LANGUE_ID
					AND CONTENT_STATUS=1";
				$value = $oConnection->queryRow($sSQL, $aBind);
				if ($value) {
					$contenu[$i]["ID"] = $value["CONTENT_ID"];
					$contenu[$i]["TITLE"] = $value["CONTENT_TITLE"];
					$contenu[$i]["SHORT_TITLE"] = $value["CONTENT_TITLE_BO"];
					$contenu[$i]["URL"] = ($value["CONTENT_CLEAR_URL"]?$value["CONTENT_CLEAR_URL"]:makeClearUrl($value["CONTENT_ID"], "cid", $value["CONTENT_TITLE_BO"]));
					$contenu[$i]["PICTO"] = $value["CONTENT_PICTO_URL"];
					$contenu[$i]["TITLE_URL"] = strip_tags($value["CONTENT_TITLE_URL"]);
					$contenu[$i]["TYPE"] = "CONTENT";
					$contenu[$i]["STATE"] = $value["STATE_ID"];
					$contenu[$i]["PAGE_ID"] = $value["PAGE_ID"];
					$contenu[$i]["VERSION"] = $value["CONTENT_VERSION"];
					$contenu[$i]["CURRENT"] = true;
				}
			}
			 
			 
			/*
			if ($contenu) {
			$nb = count($contenu);
			if ($nb > 5) {
			if ($nb%2 == 0) {
			$this->value = array_chunk($contenu, $nb/2);
			} else {
			$this->value = array_chunk($contenu, 1+$nb/2);
			}
			} else {
			$this->value = array_chunk($contenu, 5);
			}
			}
			*/
			if ($contenu) {
				$this->value = $contenu;
			}
		}
	}
?>