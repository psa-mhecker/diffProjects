<?php
    /**
     */

    /**
     * Fichier de Pelican_Cache : Récupération des liens vers les sous rubriques et les contenus associés.
     *
     * retour : PAGE_ID, SITE_ID, LANGUE_ID,  prévisu ou non
     *
     * @author Raphaël Carles <rcarles@businessdecision.com>
     *
     * @since 28/02/2006
     */
    class Frontend_Page_Child extends Pelican_Cache
    {
        public $duration = DAY;

        public function extractContent($rubrique)
        {
            $tab = array();
            for ($i = 0;$i<count($rubrique);$i++) {
                if ($rubrique[$i]["TYPE"] == "CONTENT") {
                    array_push($tab, $rubrique[$i]);
                } else {
                    $tab = array_merge($tab, extractContent($rubrique[$i]["CHILD"]));
                }
            }

            return $tab;
        }
        /** Valeur ou objet à mettre en Pelican_Cache */
        public function getValue()
        {
            $oConnection = Pelican_Db::getInstance();

            $i = 0;
            $aBind[":PAGE_ID"] = $this->params[1];
            $aBind[":SITE_ID"] = $this->params[2];
            $aBind[":LANGUE_ID"] = $this->params[3];
            if ($this->params[4]) {
                $type_version = $this->params[4];
            } else {
                $type_version = "CURRENT";
            }
            if ($this->params[5]) {
                $limit = $this->params[5];
            }

            /* récupération des sous rubriques */
            $sSQL = "
				SELECT
				p.PAGE_ID,
				PAGE_TITLE_BO
				FROM #pref#_page p
				INNER JOIN #pref#_page_version pv on (p.PAGE_ID=pv.PAGE_ID AND p.LANGUE_ID = pv.LANGUE_ID AND p.PAGE_".$type_version."_VERSION=pv.PAGE_VERSION)
				WHERE
				p.PAGE_PARENT_ID = :PAGE_ID
				AND p.SITE_ID = :SITE_ID
				AND p.LANGUE_ID = :LANGUE_ID
				AND PAGE_DISPLAY = 1
				AND PAGE_STATUS=1
				ORDER BY p.PAGE_ORDER";
            if (isset($limit)) {
                $sSQL = $oConnection->getLimitedSql($sSQL, 1, $limit, true, $aBind);
            }
            $result = $oConnection->queryTab($sSQL, $aBind);

            $tab = array();
            for ($i = 0;$i<count($result);$i++) {
                $results = Pelican_Cache::fetch("Frontend/Archives", array($result[$i]["PAGE_ID"], $this->params[2], $this->params[3], $type_version));
                $archives = extractContent($results);
                if ($archives) {
                    $tab[$result[$i]["PAGE_ID"]] = $result[$i]["PAGE_TITLE_BO"];
                }
            }
            $this->value = $tab;
        }
    }
