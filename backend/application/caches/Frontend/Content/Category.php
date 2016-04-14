<?php
    /**
     */

    /**
     * Fichier de Pelican_Cache : catégorie de contenu en fonction du type de contenu.
     *
     * @author Lenormand Gilles <glenormand@businessdecision.com>
     *
     * @since 01/03/2006
     */
    class Frontend_Content_Category extends Pelican_Cache
    {
        public $duration = DAY;

        /** Valeur ou objet à mettre en Pelican_Cache */
        public function getValue()
        {
            $aBind = array();

            $oConnection = Pelican_Db::getInstance();

            $lib = ($this->params[1] ? "CONTENT_CATEGORY_RESEARCH" : "CONTENT_CATEGORY_LABEL");

            $strSql = "SELECT CONTENT_CATEGORY_ID,".$lib." FROM #pref#_content_category";
            if ($this->params[0]) {
                $aBind[":CONTENT_TYPE_ID"] = $this->params[0];
                $strSql .= " WHERE CONTENT_TYPE_ID = :CONTENT_TYPE_ID";
            }
            $strSql .= " ORDER BY ".$lib." ASC";

            $result = $oConnection->queryTab($strSql, $aBind);
            $result2 = array();
            for ($i = 0; $i < count($result); $i++) {
                $result2[$result[$i]["CONTENT_CATEGORY_ID"]] = $result[$i][$lib];
            }

            $this->value = $result2;
        }
    }
