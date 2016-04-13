<?php
	/**
	* @package Cache
	* @subpackage Page
	*/

	include_once(pelican_path('Media'));

	/**
	* Fichier de Pelican_Cache : Récupération des liens vers les sous rubriques et les contenus associés
	*
	* retour : PAGE_ID, SITE_ID, LANGUE_ID,  prévisu ou non
	*
	* @package Cache
	* @subpackage Page
	* @author Raphaël Carles <rcarles@businessdecision.com>
	* @since 28/02/2006
	*/

	class Frontend_Page_Childall extends Pelican_Cache {

		
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
				$limit = $this->params[4];
			}

			/** récupération des sous rubriques */
			$sSQL = "
				SELECT
				p.PAGE_ID,
				PAGE_TITLE,
				PAGE_TITLE_BO,
				PAGE_CLEAR_URL,
				PAGE_PARENT_ID,
				PAGE_META_DESC,
				PAGE_VERSION,
				TEMPLATE_PAGE_ID
				FROM #pref#_page p
				INNER JOIN #pref#_page_version pv on (p.PAGE_ID=pv.PAGE_ID AND p.LANGUE_ID = pv.LANGUE_ID AND p.PAGE_".$type_version."_VERSION=pv.PAGE_VERSION)
				WHERE
				p.PAGE_PARENT_ID = :PAGE_ID
				AND p.SITE_ID = :SITE_ID
				AND p.LANGUE_ID = :LANGUE_ID
				AND PAGE_DISPLAY = 1
				AND PAGE_DISPLAY_NAV = 1
				AND PAGE_STATUS=1
				ORDER BY p.PAGE_ORDER";
			if (isset($limit)) {
				$sSQL = $oConnection->getLimitedSql($sSQL, 1, $limit);
			}
			$result = $oConnection->queryTab($sSQL, $aBind);
			if ($result) {
				foreach($result as $value) {
					$lien[$i]["ID"] = $value["PAGE_ID"];
					$lien[$i]["PAGE_ID"] = $value["PAGE_PARENT_ID"];

					$lien[$i]["TITLE"] = $value["PAGE_TITLE"];
					$lien[$i]["SHORT_TITLE"] = $value["PAGE_TITLE_BO"];
					$lien[$i]["DESCRIPTION"] = $value["PAGE_TEXT"];
					$lien[$i]["RSS_DESCRIPTION"] = $value["PAGE_META_DESC"];
					$lien[$i]["TEMPLATE_PAGE_ID"] = $value["TEMPLATE_PAGE_ID"];
					$lien[$i]["PAGE_VERSION"] = $value["PAGE_VERSION"];
					$lien[$i]["URL"] = ($value["PAGE_CLEAR_URL"]?$value["PAGE_CLEAR_URL"]:makeClearUrl($value["PAGE_ID"], "pid", $value["PAGE_TITLE_BO"]));
					$lien[$i]["TITLE_URL"] = strip_tags($value["PAGE_TITLE"]);
					$lien[$i]["TYPE"] = "PAGE";
					$i++;
				}
			}

			/** récupération des contenus liés aux sous-rubriques */
			$sSQL = "
				SELECT
				c.CONTENT_ID,
				CONTENT_TITLE,
				CONTENT_TITLE_BO,
				CONTENT_CLEAR_URL,
				CONTENT_PICTO_URL,
				CONTENT_TITLE_URL,
				STATE_ID,
				cv.PAGE_ID,
				CONTENT_VERSION,
				MEDIA_PATH,
				MEDIA_ALT,
				DOC_ID,
				CONTENT_SHORTTEXT,
				CONTENT_EXTERNAL_LINK,
				CONTENT_META_DESC
				FROM #pref#_content c
				INNER JOIN #pref#_content_version cv on (c.CONTENT_ID=cv.CONTENT_ID AND c.LANGUE_ID = cv.LANGUE_ID AND c.CONTENT_".$type_version."_VERSION=cv.CONTENT_VERSION)
				LEFT JOIN #pref#_page_order po on (po.PAGE_ID=:PAGE_ID AND po.LANGUE_ID = c.LANGUE_ID AND po.PAGE_ORDER_ID=c.CONTENT_ID)
				LEFT JOIN #pref#_media m on (m.MEDIA_ID=cv.MEDIA_ID)
				WHERE ";
			if ($aBind[":PAGE_ID"] == 1) {
				$sSQL .= " cv.CONTENT_DIRECT_HOME = 1 ";
			} else {
				$sSQL .= " cv.CONTENT_DIRECT_PAGE = 1 AND cv.PAGE_ID = :PAGE_ID ";
			}
			$sSQL .= "AND c.SITE_ID = :SITE_ID
				AND c.LANGUE_ID = :LANGUE_ID
				AND CONTENT_STATUS=1
				ORDER BY PAGE_ORDER, CONTENT_ID DESC";
			if (isset($limit)) {
				$sSQL = $oConnection->getLimitedSql($sSQL, 1, $limit);
			}

			$result = $oConnection->queryTab($sSQL, $aBind);

			if ($result) {
				foreach($result as $value) {

					/** cas particulier des liens vers des documents mais avec résumé */
					if ($value["CONTENT_EXTERNAL_LINK"] && $value["CONTENT_EXTERNAL_LINK"]!=$value["CONTENT_CLEAR_URL"] && substr($value["CONTENT_CLEAR_URL"],0,1) == "/") {
						$value["CONTENT_TITLE_URL"] = "";
					}

					$lien[$i]["ID"] = $value["CONTENT_ID"];
					$lien[$i]["TITLE"] = $value["CONTENT_TITLE"];
					$lien[$i]["SHORT_TITLE"] = $value["CONTENT_TITLE_BO"];
					$lien[$i]["DESCRIPTION"] = $value["CONTENT_SHORTTEXT"];
					$lien[$i]["RSS_DESCRIPTION"] = $value["CONTENT_META_DESC"];
					$lien[$i]["PUBLICATION"] = $value["CONTENT_PUBLICATION_DATE"];
					$lien[$i]["AUTHOR"] = $value["CONTENT_AUTHOR"];
					$lien[$i]["URL"] = ($value["CONTENT_CLEAR_URL"]?$value["CONTENT_CLEAR_URL"]:makeClearUrl($value["CONTENT_ID"], "cid", $value["CONTENT_TITLE_BO"]));
					$lien[$i]["PICTO"] = $value["CONTENT_PICTO_URL"];
					$lien[$i]["TITLE_URL"] = strip_tags($value["CONTENT_TITLE_URL"]);
					$lien[$i]["TYPE"] = "CONTENT";
					$lien[$i]["MEDIA_PATH"] = Pelican_Media::getFileNameMediaFormat($value["MEDIA_PATH"], 7);
					$lien[$i]["MEDIA_ALT"] = $value["MEDIA_ALT"];
					$lien[$i]["DOC_ID"] = $value["DOC_ID"];
					$lien[$i]["PAGE_ID"] = $value["PAGE_ID"];
					$lien[$i]["CONTENT_SHORTTEXT"] = $value["CONTENT_SHORTTEXT"];
					$lien[$i]["CONTENT_EXTERNAL_LINK"] = $value["CONTENT_EXTERNAL_LINK"];
					$i++;
				}
			}
			if ($lien) {
				$nb = count($lien);
				if ($nb > 5) {
					if ($nb%2 == 0) {
						$this->value = array_chunk($lien, $nb/2);
					} else {
						$this->value = array_chunk($lien, 1+$nb/2);
					}
				} else {
					$this->value = array_chunk($lien, 5);
				}
			}
		}
	}
?>