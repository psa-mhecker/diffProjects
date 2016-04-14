<?php
/**
 */

/**
 * Fichier de Pelican_Cache : thème de contenu en fonction du type de contenu.
 *
 * @author Lenormand Gilles <glenormand@businessdecision.com>
 *
 * @since 01/03/2006
 */
class Frontend_Content_Theme extends Pelican_Cache
{
    public $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $aBind = array();

        $oConnection = Pelican_Db::getInstance();

        $strSql = "SELECT CONTENT_THEME_ID,CONTENT_THEME_LABEL
				FROM #pref#_content_theme";
        if ($this->params[0]) {
            $aBind[":CONTENT_TYPE_ID"] = $this->params[0];
            $strSql .= " WHERE CONTENT_TYPE_ID = :CONTENT_TYPE_ID";
        }
        $strSql .= " ORDER BY CONTENT_THEME_LABEL ASC";

        $result = $oConnection->queryTab($strSql, $aBind);
        $result2 = array();
        for ($i = 0;$i<count($result);$i++) {
            $result2[$result[$i]["CONTENT_THEME_ID"]] = $result[$i]["CONTENT_THEME_LABEL"];
        }

        $this->value = $result2;
    }
}
