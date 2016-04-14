<?php
/**
 */

/**
 * Fichier de Pelican_Cache : recherche paramétrée de contenu.
 *
 * @author Gilles Lenormand <glenormand@businessdecision.com>
 *
 * @since 04/04/2006
 */
include_once pelican_path('Media');

class Frontend_Search_Content extends Pelican_Cache
{
    public $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $contentTypeId = $this->params[0];
        $params = explode("#", $this->params[1]);
        $aBind[":SITE_ID"] = $params[0];
        $aBind[":LANGUE_ID"] = $params[1];
        $aBind[":PAGE_ID"] = $_GET["pid"];

        if ($params[2]) {
            $type_version = $params[2];
        } else {
            $type_version = "CURRENT";
        }
        if ($params[15]) {
            $displayCriterias = explode("§", $params[15]);
        }

        if ($params[21]) {
            $useCidRecherche = $params[21];
        }
        if ($params[22]) {
            $numberByPage = $params[22];
        }
        $mediaFormat = 8;

        $strSql = "SELECT cv.CONTENT_TITLE,
			cv.CONTENT_TITLE_BO,
			cv.CONTENT_PICTO_URL,
			cv.CONTENT_TITLE_URL as TITLE_URL,
			cv.CONTENT_CLEAR_URL,
			cv.CONTENT_EXTERNAL_LINK,
			cv.PAGE_ID,
			m.MEDIA_PATH,
			cv.CONTENT_META_DESC,
			m.MEDIA_ALT,".$oConnection->dateSqlToString("cv.CONTENT_PUBLICATION_DATE ", false)." as CONTENT_PUBLICATION_DATE,cv.CONTENT_SHORTTEXT AS SHORTTEXT,cv.DOC_ID";
        for ($i = 0; $i < count($displayCriterias); $i++) {
            switch ($displayCriterias[$i]) {
                case 1: //Auteur
                $strSql .= ",p.PERSON_LABEL, cv.CONTENT_TEXT2";
                break;
                case 2: //Date
                $strSql .= ",".$oConnection->dateSqlToString("cv.CONTENT_DATE ", false)." as CONTENT_DATE,".$oConnection->dateSqlToString("cv.CONTENT_DATE2 ", false)." as CONTENT_DATE2, cv.CONTENT_DATE_FREE";
                break;
                case 3: //Catégorie
                $strSql .= ",cc.CONTENT_CATEGORY_ID";
                $strSql .= ",cc.CONTENT_CATEGORY_LABEL";
                break;
                case 4: //Sous-catégorie
                $strSql .= ",csc.CONTENT_SUB_CATEGORY_ID";
                $strSql .= ",csc.CONTENT_SUB_CATEGORY_LABEL";
                break;
                case 5: //Description
                $strSql .= ",cv.CONTENT_SHORTTEXT";
                break;
                case 7: //Thème,thématique
                $strSql .= ",cth.CONTENT_THEME_LABEL";
                break;
                case 8: //Académie
                $strSql .= ",ma.ACADEMIE_LABEL";
                break;
                case 9: //Numéro de publication
                $strSql .= ",cv.CONTENT_CODE";
                break;
            }
        }
        $strSql .= " FROM #pref#_content c
				inner join #pref#_content_version cv on (c.CONTENT_ID=cv.CONTENT_ID AND c.CONTENT_".$type_version."_VERSION=cv.CONTENT_VERSION AND c.LANGUE_ID=cv.LANGUE_ID)
				LEFT JOIN #pref#_media m on (m.MEDIA_ID=cv.MEDIA_ID)";

        for ($i = 0; $i < count($displayCriterias); $i++) {
            switch ($displayCriterias[$i]) {
                case 1: //Auteur
                $strSql .= " left join #pref#_person p on (cv.PERSON_ID=p.PERSON_ID)";
                break;
                case 3: //Catégorie
                $strSql .= " left join #pref#_content_category cc on (cv.CONTENT_CATEGORY_ID=cc.CONTENT_CATEGORY_ID)";
                break;
                case 4: //Sous-catégorie
                $strSql .= " left join #pref#_content_sub_category csc on (cv.CONTENT_SUB_CATEGORY_ID=csc.CONTENT_SUB_CATEGORY_ID)";
                break;
                case 7: //Thème
                $strSql .= " left join #pref#_content_theme cth on (cv.CONTENT_THEME_ID=cth.CONTENT_THEME_ID)";
                break;
                case 8: //Académie
                $strSql .= " left join #pref#_academie ma on (cv.ACADEMIE_ID=ma.ACADEMIE_ID)";
                break;
            }
        }

        $strSql .= " inner join #pref#_content_type ct on (c.CONTENT_TYPE_ID=ct.CONTENT_TYPE_ID)
				WHERE
				c.SITE_ID = :SITE_ID
				AND c.CONTENT_STATUS=1
				AND c.LANGUE_ID = :LANGUE_ID
				AND ((cv.PAGE_ID=:PAGE_ID AND cv.CONTENT_DIRECT_PAGE=1) OR not cv.PAGE_ID=:PAGE_ID OR cv.PAGE_ID is null) ";
        if ($params[3]) {
            //Si on recherche le type de contenu actualité, il faut ramener aussi le type de contenu dossier de presse
            if ($params[3] == Pelican::$config["CNT_TYPE_ACTUALITE"]) {
                $aBind[":CONTENT_TYPE_ID"] = $params[3];
                $aBind[":CONTENT_TYPE_ID1"] = Pelican::$config["CNT_TYPE_DOSSIER_PRESSE"];
                $strSql .= " AND (c.CONTENT_TYPE_ID=:CONTENT_TYPE_ID OR c.CONTENT_TYPE_ID=:CONTENT_TYPE_ID1)";
            } else {
                $aBind[":CONTENT_TYPE_ID"] = $params[3];
                $strSql .= " AND c.CONTENT_TYPE_ID=:CONTENT_TYPE_ID";
            }
        }
        if ($params[4]) {
            if ($params[3] == Pelican::$config["CNT_TYPE_ACTUALITE"] && $params[4] == Pelican::$config["CNT_CAT_PRESSE"]) {
                $aBind[":CONTENT_CATEGORY_ID"] = $params[4];
                $aBind[":CONTENT_CATEGORY_ID1"] = Pelican::$config["CNT_CAT_DOSSIER_PRESSE"];
                $strSql .= " AND (cv.CONTENT_CATEGORY_ID=:CONTENT_CATEGORY_ID OR cv.CONTENT_CATEGORY_ID=:CONTENT_CATEGORY_ID1)";
            } else {
                $aBind[":CONTENT_CATEGORY_ID"] = $params[4];
                $strSql .= " AND cv.CONTENT_CATEGORY_ID=:CONTENT_CATEGORY_ID";
            }
        }
        if ($params[5]) {
            $useCidRecherche = false;
            if ($params[3] == Pelican::$config["CNT_TYPE_ACTUALITE"] && $params[5] == Pelican::$config["CNT_CAT_PRESSE"]) {
                $aBind[":CONTENT_CATEGORY_ID"] = $params[5];
                $aBind[":CONTENT_CATEGORY_ID1"] = Pelican::$config["CNT_CAT_DOSSIER_PRESSE"];
                $strSql .= " AND (cv.CONTENT_CATEGORY_ID=:CONTENT_CATEGORY_ID OR cv.CONTENT_CATEGORY_ID=:CONTENT_CATEGORY_ID1)";
            } else {
                $aBind[":CONTENT_CATEGORY_ID"] = $params[5];
                $strSql .= " AND cv.CONTENT_CATEGORY_ID=:CONTENT_CATEGORY_ID";
            }
        }
        if ($params[6]) {
            $useCidRecherche = false;
            if ($params[3] == Pelican::$config["CNT_TYPE_ACTUALITE"] && ($params[5] == Pelican::$config["CNT_CAT_PRESSE"] || $params[4] == Pelican::$config["CNT_CAT_PRESSE"]) && $params[6] == Pelican::$config["CNT_SUBCAT_DOSSIER_PRESSE"]) {
                $aBind[":CONTENT_SUB_CATEGORY_ID"] = $params[6];
                $strSql .= " AND (cv.CONTENT_SUB_CATEGORY_ID=:CONTENT_SUB_CATEGORY_ID OR cv.CONTENT_SUB_CATEGORY_ID is null)";
            } else {
                $aBind[":CONTENT_SUB_CATEGORY_ID"] = $params[6];
                $strSql .= " AND cv.CONTENT_SUB_CATEGORY_ID=:CONTENT_SUB_CATEGORY_ID";
            }
        }
        if ($params[7]) {
            $useCidRecherche = false;
            $aBind[":CONTENT_THEME_ID"] = $params[7];
            $strSql .= " AND cv.CONTENT_THEME_ID=:CONTENT_THEME_ID";
        }
        if ($params[8]) {
            $useCidRecherche = false;
            $aBind[":CONTENT_SUB_THEME_ID"] = $params[8];
            $strSql .= " AND cv.CONTENT_SUB_THEME_ID=:CONTENT_SUB_THEME_ID";
        }
        if ($params[9] && $params[10]) {
            $useCidRecherche = false;
            $aBind[":DATE1"] = $params[10]."-".$params[9]."-01";
            $strSql .= " AND (cv.CONTENT_DATE >= TO_DATE(:DATE1,'YYYY-MM-DD') AND cv.CONTENT_DATE <= ADD_MONTHS(TO_DATE(:DATE1,'YYYY-MM-DD')-1,1) OR cv.CONTENT_DATE2 BETWEEN TO_DATE(:DATE1,'YYYY-MM-DD') AND ADD_MONTHS(TO_DATE(:DATE1,'YYYY-MM-DD')-1,1) OR TO_DATE(:DATE1,'YYYY-MM-DD') between cv.CONTENT_DATE and cv.CONTENT_DATE2)";
        } elseif ($params[10]) {
            $useCidRecherche = false;
            $aBind[":DATE1"] = $params[10]."-01-01";
            $strSql .= " AND (cv.CONTENT_DATE >= TO_DATE(:DATE1,'YYYY-MM-DD') AND cv.CONTENT_DATE <= ADD_MONTHS(TO_DATE(:DATE1,'YYYY-MM-DD')-1,12) OR cv.CONTENT_DATE2 >= TO_DATE(:DATE1,'YYYY-MM-DD') AND cv.CONTENT_DATE2 <= ADD_MONTHS(TO_DATE(:DATE1,'YYYY-MM-DD')-1,12) OR TO_DATE(:DATE1,'YYYY-MM-DD') between cv.CONTENT_DATE and cv.CONTENT_DATE2)";
        } elseif ($params[9]) {
            $useCidRecherche = false;
            if ($params[9]<10) {
                $aBind[":DATE1"] = "0".$params[9];
            } else {
                $aBind[":DATE1"] = $params[9];
            }
            $strSql .= " AND (".$oConnection->dateToMonth("cv.CONTENT_DATE")."=:DATE1 OR ".$oConnection->dateToMonth("cv.CONTENT_DATE2")."=:DATE1)";
        }
        if ($params[11]) {
            $useCidRecherche = false;
            $aBind[":ACADEMIE_ID"] = $params[11];
            $strSql .= " AND (cv.ACADEMIE_ID=:ACADEMIE_ID OR cv.ACADEMIE_ID=0)";
        } else {
            $strSql .= " AND cv.ACADEMIE_ID is not null";
        }
        if ($params[12]) {
            $useCidRecherche = false;
            $aBind[":PERSON_ID"] = $params[12];
            $strSql .= " AND cv.PERSON_ID=:PERSON_ID";
        }
        if ($params[13]) {
            $useCidRecherche = false;
            $aBind[":PAGE_ID"] = $params[13];
            $strSql .= " AND cv.PAGE_ID=:PAGE_ID";
        }
        if ($params[14]) {
            $useCidRecherche = false;
            $aBind[":CONTENT_COLLECTION_ID"] = $params[14];
            $strSql .= " AND cv.CONTENT_COLLECTION_ID=:CONTENT_COLLECTION_ID";
        }
        if ($params[16] && $params[17]) {
            $useCidRecherche = false;
            $aBind[":DATE2"] = $params[17]."-".$params[16]."-01";
            $strSql .= " AND cv.CONTENT_PUBLICATION_DATE >= TO_DATE(:DATE2,'YYYY-MM-DD') AND cv.CONTENT_PUBLICATION_DATE <= ADD_MONTHS(TO_DATE(:DATE2,'YYYY-MM-DD')-1,1)";
        } elseif ($params[17]) {
            $useCidRecherche = false;
            $aBind[":DATE2"] = $params[17]."-01-01";
            $strSql .= " AND cv.CONTENT_PUBLICATION_DATE >= TO_DATE(:DATE2,'YYYY-MM-DD') AND cv.CONTENT_PUBLICATION_DATE <= ADD_MONTHS(TO_DATE(:DATE2,'YYYY-MM-DD')-1,12)";
        }
        if ($params[20]) {
            $useCidRecherche = false;
            $aBind[":CONTENT_CODE"] = $params[20];
            $strSql .= " AND cv.CONTENT_CODE=:CONTENT_CODE";
        }

        /* Ajout du Like sur le champ Autheur */
        if ($params[23]) {
            $aBind[":AUTHOR"] = $params[23];
            $strSql .= ' AND cv.CONTENT_SHORTTEXT2 LIKE \'%'.Pelican_Text::dropAccent(html_entity_decode($params[23])).'%\'';
        }

        if ($useCidRecherche) {
            $aBind[":CONTENT_ID"] = $params[21];
            $strSql .= " AND c.CONTENT_ID=:CONTENT_ID";
        }
        $strSql .= " ORDER BY cv.CONTENT_DATE DESC";

        if ($params[18]) {
            $limit = $params[18];
            $strSql = $oConnection->getLimitedSql($strSql, 1, $limit, true, $aBind);
        } elseif ($params[19]) {
            $page = $params[19];
            $mini = $page * $numberByPage+1;
            $strSql = $oConnection->getLimitedSql($strSql, $mini, $numberByPage, true, $aBind);
        } elseif ($numberByPage) {
            $strSql = $oConnection->getLimitedSql($strSql, 1, $numberByPage, true, $aBind);
        }

        $result = $oConnection->queryTab($strSql, $aBind);
        for ($j = 0; $j < count($result); $j++) {
            $result[$j]["MEDIA_PATH"] = Pelican_Media::getFileNameMediaFormat($result[$j]["MEDIA_PATH"], $mediaFormat);

            /* cas particulier des liens vers des documents mais avec résumé */
            if ($result[$j]["CONTENT_EXTERNAL_LINK"] && $result[$j]["CONTENT_EXTERNAL_LINK"] != $result[$j]["CONTENT_CLEAR_URL"] && substr($result[$j]["CONTENT_CLEAR_URL"], 0, 1) == "/") {
                $result[$j]["TITLE_URL"] = "";
            }
        }

        $this->value = $result;
    }
}
