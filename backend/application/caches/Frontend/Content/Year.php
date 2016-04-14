<?php
/**
 */

/**
 * Fichier de Pelican_Cache : Paramètres liés à un contenu donné ($_GET["pid"]).
 *
 * retour : id, lib
 *
 * @author Lenormand Gilles <glenormand@businessdecision.com>
 *
 * @since 27/04/2006
 */
class Frontend_Content_Year extends Pelican_Cache
{
    public $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind[":CONTENT_TYPE_ID"] = $this->params[0];
        $aBind[":SITE_ID"] = $this->params[1];
        $aBind[":LANGUE_ID"] = $this->params[2];
        if ($this->params[3]) {
            $type_version = $this->params[3];
        } else {
            $type_version = "CURRENT";
        }
        if ($this->params[4]) {
            $aBind[":CONTENT_CATEGORY_ID"] = $this->params[4];
        }
        $query = "
				SELECT
					distinct ".$oConnection->dateToYear("cv.CONTENT_DATE")." as YEAR
				FROM #pref#_content c
				inner join #pref#_content_version cv on (c.CONTENT_ID=cv.CONTENT_ID AND c.CONTENT_".$type_version."_VERSION=cv.CONTENT_VERSION AND c.LANGUE_ID=cv.LANGUE_ID)
				WHERE
				c.CONTENT_TYPE_ID = :CONTENT_TYPE_ID
				AND c.SITE_ID = :SITE_ID
				AND c.CONTENT_STATUS=1
				AND c.LANGUE_ID = :LANGUE_ID";
        if ($this->params[4]) {
            $query .= " AND cv.CONTENT_CATEGORY_ID=:CONTENT_CATEGORY_ID";
        }
        $query .= " order by YEAR DESC";
        $oConnection->query($query, $aBind);
        $tab = $oConnection->data["YEAR"];

        $query2 = "
				SELECT
					distinct ".$oConnection->dateToYear("cv.CONTENT_DATE2")." as YEAR
				FROM #pref#_content c
				inner join #pref#_content_version cv on (c.CONTENT_ID=cv.CONTENT_ID AND c.CONTENT_".$type_version."_VERSION=cv.CONTENT_VERSION AND c.LANGUE_ID=cv.LANGUE_ID)
				WHERE
				c.CONTENT_TYPE_ID = :CONTENT_TYPE_ID
				AND c.SITE_ID = :SITE_ID
				AND c.CONTENT_STATUS=1
				AND c.LANGUE_ID = :LANGUE_ID";
        if ($this->params[4]) {
            $query2 .= " AND cv.CONTENT_CATEGORY_ID=:CONTENT_CATEGORY_ID";
        }
        $query2 .= " order by YEAR DESC";
        $oConnection->query($query2, $aBind);
        $tab2 = $oConnection->data["YEAR"];

        if ($tab && $tab2) {
            $tab = array_merge($tab, $tab2);
        }
        $result = array();
        if ($tab) {
            rsort($tab);
            $result[0] = "--> Choisissez";
            for ($i = 0;$i<count($tab);$i++) {
                $result[$tab[$i]] = $tab[$i];
            }
        }
        $this->value = $result;
    }
}
