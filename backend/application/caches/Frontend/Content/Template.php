<?php
    /**
     */

    /**
     * Fichier de Pelican_Cache : Paramètres liés à un contenu donné ($_GET["pid"]).
     *
     * retour : id, lib
     *
     * @author Raphaël Carles <rcarles@businessdecision.com>
     *
     * @since 01/03/2006
     */
    class Frontend_Content_Template extends Pelican_Cache
    {
        public $duration = DAY;

        /** Valeur ou objet à mettre en Pelican_Cache */
        public function getValue()
        {
            $oConnection = Pelican_Db::getInstance();

            $aBind[":CONTENT_ID"] = $this->params[0];
            $aBind[":SITE_ID"] = $this->params[1];
            $aBind[":LANGUE_ID"] = $this->params[2];
            if ($this->params[3]) {
                $type_version = $this->params[3];
            } else {
                $type_version = "CURRENT";
            }
            if ($type_version == "CURRENT") {
                $status = " AND CONTENT_STATUS=1";
            }

            $query = "
				SELECT
					CONTENT_TITLE,
					CONTENT_TITLE_BO,
					CONTENT_SUBTITLE,
					CONTENT_CATEGORY_LABEL,
					".$oConnection->getNVLClause("cv.TEMPLATE_ID", "ct.TEMPLATE_ID")." as TEMPLATE_ID,
					PAGE_ID,
					c.CONTENT_TYPE_ID,
					CONTENT_META_TITLE,
					CONTENT_META_KEYWORD,
					CONTENT_META_DESC,
					ct.CONTENT_TYPE_LABEL,
					CONTENT_STATUS
				FROM #pref#_content c
				inner join #pref#_content_version cv on (c.CONTENT_ID=cv.CONTENT_ID AND c.CONTENT_".$type_version."_VERSION=cv.CONTENT_VERSION AND c.LANGUE_ID=cv.LANGUE_ID)
				inner join #pref#_content_type ct on (c.CONTENT_TYPE_ID=ct.CONTENT_TYPE_ID)
				left join #pref#_content_category csc on (csc.CONTENT_CATEGORY_ID=cv.CONTENT_CATEGORY_ID)
				WHERE
				cv.CONTENT_ID = :CONTENT_ID
				AND c.SITE_ID = :SITE_ID
				AND c.LANGUE_ID = :LANGUE_ID".$status;
            $result = $oConnection->queryRow($query, $aBind);

            if ($result["CONTENT_TITLE_BO"]) {
                $title = $result["CONTENT_TITLE_BO"];
                $title = str_replace("<br>", " ", $title);
                $title = str_replace("<br />", " ", $title);
                $title = str_replace("  ", " ", $title);
                $result["WINDOW_TITLE"] = $title;
            }
            if (!$result["CONTENT_META_TITLE"]) {
                $result["CONTENT_META_TITLE"] = ($result["WINDOW_TITLE"] ? $result["WINDOW_TITLE"] : $result["CONTENT_TITLE"]);
            }
            $this->value = $result;
        }
    }
