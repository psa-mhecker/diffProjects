<?php
/**
 * Classe de gestion de l'indexation et de la sélection des résultats de recherche.
 *
 * Intégration des documents pdf, doc, xls, ppt et rtf pour windows et linus
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 */
pelican_import('Search.Interface');

/**
 * Classe de gestion de le recherche.
 *
 * Classe de gestion de le recherche
 *
 * @author Raphaël Carles <rcarles@businessdecision.fr>
 *
 * @since 20/03/2006
 *
 * @version 1.0
 */
class Pelican_Search_Db implements Pelican_Search_Interface
{
    /**
     * Comptages du résultat de recherche + pour les champs demandés en paramètres.
     *
     * @static __DESC__
     * @access public
     *
     * @param __TYPE__ $site    __DESC__
     * @param __TYPE__ $langue  __DESC__
     * @param string   $fields  Liste des champs du tableau de retour pour lesquels on
     *                          veut un comptage
     * @param mixed    $filters Clauses de filtrage du type
     *                          array("champ","valeur","type") où type peut être string, date, keyword,
     *                          integer
     *
     * @return __TYPE__
     */
    public function getStatistics($site, $langue, $fields, $filters)
    {
        global $sSQLWHERE, $aBindSearch, $aScore;
        $oConnection = Pelican_Db::getInstance();

        /* calcul du hash */

        /* récupération des paramètres de recherche */
        $research["recMot"] = $_GET["recMot"];
        if ($_GET["recDate1"]) {
            $research["recDate1"] = $_GET["recDate1"];
        }
        if ($_GET["recDate2"]) {
            $research["recDate2"] = $_GET["recDate2"];
        }
        if ($_GET["recChamp"]) {
            $research["recChamp"] = $_GET["recChamp"];
        }
        if ($_GET["recLang"]) {
            $research["recLang"] = $_GET["recLang"];
        }
        if ($_GET["recCategory"]) {
            $research["recCategory"] = $_GET["recCategory"];
        }
        $research["hashAffine"] = md5(serialize($research));
        //  if ($_GET["recTheme"]) $research["recTheme"] = $_GET["recTheme"];
        $tmp = @explode(",", $_GET["recRub"]);
        $tmp_unique = array_unique($tmp);
        $research["recRubArray"] = $tmp;
        if (count($tmp) == 1) {
            if ($tmp[0]) {
                $research["recRub"] = $tmp[0];
            }
        }
        if (count($tmp_unique) == 1 && !$tmp_unique[0]) {
            unset($research["recRubArray"]);
        }
        $research["hash"] = md5(serialize($research));
        if ($_GET["clean"]) {
            $_SESSION[APP]["research"][$research["hash"]] = null;
            $_SESSION[APP]["research"][$research["hashAffine"]] = null;
        }

        /* valeurs */
        $aBindSearch[":SITE_ID"] = $site;
        $aBindSearch[":LANGUE_ID"] = $langue;
        $aBindSearch[":RESEARCH_STATUS"] = 1;
        $aBindSearch[":RESEARCH_DISPLAY"] = 1;

        /* filtres */
        if ($filters) {
            $i = 0;
            $order = 0;
            foreach ($filters as $params) {
                $i++;
                $bind = ":".str_replace(".", "", $params[0])."_".$i;
                $operator = ($params[3] ? $params[3] : "=");
                if ($params[1]) {
                    switch ($params[2]) {
                        case "keyword": {
                                    $aBindSearch[$bind] = Pelican_Search::cleanSearch($params[1]);
                                    $aWhereContains[] = $this->containsClause(array($params[0]), $bind, $aBindSearch, "AND", ++$order);
                                break;
                            }
                        case "match": {
                                $aBindSearch[$bind] = Pelican_Search::cleanSearch($params[1]);
                                $aWhereContains[] = $this->matchClause($params[0], $bind, $aBindSearch, "AND", ++$order);
                                $aScore = $oConnection->getScoreClause($params[0], $aBindSearch[$bind], $bind);
                                break;
                            }
                        case "list": {
                                $list = array();
                                if (!is_array($params[1])) {
                                    $params[1] = array($params[1]);
                                }
                                $j = 0;
                                foreach ($params[1] as $key => $value) {
                                    if ($value) {
                                        $j++;
                                        $aBindSearch[$bind."_".$j] = $value;
                                        $list[$params[0]][] = $bind."_".$j;
                                    }
                                }
                                if ($list) {
                                    $aWhere[] = $params[0]." in (".implode(",", $list[$params[0]]).")";
                                }
                                break;
                            }
                        case "string": {
                                $aBindSearch[$bind] = $oConnection->strToBind($params[1]);
                                $aWhere[] = $params[0]." ".$operator." ".$bind;
                                break;
                            }
                        case "date": {
                                if (!$oConnection->allowBind) {
                                    $bind = $params[1];
                                }
                                $aBindSearch[$bind] = $params[1];
                                $aWhere[] = $params[0]." ".$operator." ".str_replace("'".$bind."'", $bind, $oConnection->dateStringToSql($bind, false));
                                break;
                            }
                        default: {
                                $aBindSearch[$bind] = $params[1];
                                $aWhere[] = $params[0]." ".$operator." ".$bind;
                                break;
                            }
                        }
                }
            }
        }
        $sSQLWHERE .= " WHERE r.SITE_ID = :SITE_ID
                AND r.RESEARCH_STATUS = :RESEARCH_STATUS
                AND r.RESEARCH_DISPLAY = :RESEARCH_DISPLAY
                AND r.LANGUE_ID = :LANGUE_ID
                AND r.RESEARCH_DATE_BEGIN <= ".$oConnection->getNow()."
                AND  ".$oConnection->getNow()." <= r.RESEARCH_DATE_END";
        if ($aWhere) {
            $sSQLWHERE .= " AND ".implode(" AND ", $aWhere);
        }
        if ($aWhereContains) {
            $sSQLWHERE .= " AND (".implode(" OR ", $aWhereContains).")";
        }

            /* requête */
            $sSQL = "select count(1) as NB";
        if ($fields) {
            if (!is_array($fields)) {
                $fields = array($fields);
            }
            $sSQL .= ",".implode(",", $fields);
        }
        $sSQL .= " from #pref#_research r ";
        $sSQL .= $sSQLWHERE;
        if ($fields) {
            $sSQL .= " group by ".implode(",", $fields);
        }
        unset($_SESSION[APP]["research"]);
        if (!$_SESSION[APP]["research"][$research["hash"]]) {

                /* mise en session des paramètres */
                $_SESSION[APP]["research"][$research["hash"]] = $research;
            $result = $oConnection->query($sSQL, $aBindSearch);
            if ($oConnection->data) {
                $nb = count($oConnection->data["NB"]);
                for ($i = 0;$i < $nb;$i++) {
                    $result["count"] += $oConnection->data["NB"][$i];
                    if ($fields) {
                        foreach ($fields as $field) {
                            $result[$field][$oConnection->data[$field][$i]] += $oConnection->data["NB"][$i];
                        }
                    }
                }
            }
            $_SESSION[APP]["research"][$research["hash"]]["statistics"] = $result;

                /* comptage des rubriques et catégories pour le formulaire d'affinage */
                if ($_GET["type"] != "Affine") {
                    if ($_SESSION[APP]["research"][$research["hash"]]["statistics"]["MORE_LEVEL1"] && !$_SESSION[APP]["research"][$research["hashAffine"]]["rubCount"]) {
                        $_SESSION[APP]["research"][$research["hashAffine"]]["rubCount"] = $_SESSION[APP]["research"][$research["hash"]]["statistics"]["MORE_LEVEL1"];
                    }
                    if ($_SESSION[APP]["research"][$research["hash"]]["statistics"]["MORE_THEME"] && !$_SESSION[APP]["research"][$research["hashAffine"]]["themeCount"]) {
                        $_SESSION[APP]["research"][$research["hashAffine"]]["themeCount"] = $_SESSION[APP]["research"][$research["hash"]]["statistics"]["MORE_THEME"];
                    }
                }
                //Pelican_Search::logResearch($site, $research["recMot"]);
        }

            /* affectation du résultat de la recehrche courante en session */
            $_SESSION[APP]["current_search"] = $_SESSION[APP]["research"][$research["hash"]];
        $_SESSION[APP]["current_searchAffine"] = $_SESSION[APP]["research"][$research["hashAffine"]];
    }

        /**
         * Retourne le résultat d'une recherche.
         *
         * @static __DESC__
         * @access public
         *
         * @param int $current_page (option) __DESC__
         * @param __TYPE__ $nbResultPerPage (option) __DESC__
         * @param __TYPE__ $order (option) __DESC__
         * @param __TYPE__ $fields (option) __DESC__
         *
         * @return mixed
         */
        public function getResult($current_page = 1, $nbResultPerPage = 10, $order = "date", $fields = array())
        {
            global $sSQLWHERE, $aBindSearch, $aScore;
            $oConnection = Pelican_Db::getInstance();
            if (!empty($_SESSION[APP]["research"][$_SESSION[APP]["current_search"]["hash"]]["results"][$nbResultPerPage."_".$current_page."_".$order])) {
                $return = $_SESSION[APP]["research"][$_SESSION[APP]["current_search"]["hash"]]["results"][$nbResultPerPage."_".$current_page."_".$order];
            } else {
                //            if ($aScore) {
                //                $score = "(" . implode("+", $aScore) . ")";
                //                if (count($aScore)) {
                //                    $score .= "/" . count($aScore) . " ";
                //                }
                //            } else {
                //                $score = "100";
                //            }
                if ($aScore) {
                    $score = $aScore;
                } else {
                    $score = "100";
                }
                $sSQL = "
                    SELECT
                    r.RESEARCH_TITLE,
                    r.RESEARCH_TYPE,
                    r.RESEARCH_TYPE_ID,
                    r.RESEARCH_URL,
                    r.RESEARCH_URL_TITLE,
                    ".$score." as PERTINENCE,
                    r.RESEARCH_URL_PICTO,
                    r.RESEARCH_DESCRIPTION,
                    ".$oConnection->dateSqlToString("r.RESEARCH_DATE")." AS RESEARCH_DATE_FR,
                    RESEARCH_LABEL";
                if ($fields) {
                    if (!is_array($fields)) {
                        $fields = array($fields);
                    }
                    $sSQL .= ",".implode(",", $fields);
                }
                $sSQL .= " FROM #pref#_research r
                    INNER JOIN #pref#_research_param rp ON (r.SITE_ID=rp.SITE_ID AND r.RESEARCH_TYPE=rp.RESEARCH_TYPE AND r.RESEARCH_TYPE_ID=rp.RESEARCH_TYPE_ID)
                    ".$sSQLWHERE;
                //   $sSQL .= " ORDER BY RESEARCH_DATE DESC, r.RESEARCH_TITLE";
                //$sSQL .= " ORDER BY " . ($order == "per" ? "PERTINENCE" : "RESEARCH_DATE") . " DESC, r.RESEARCH_TITLE";
                $sSQL .= " ORDER BY ".($order != "" && $order != "date" ? strtoupper($order) : "RESEARCH_DATE DESC, r.RESEARCH_TITLE");
                $sSQL = $oConnection->getLimitedSQL($sSQL, (($current_page - 1) * $nbResultPerPage) + 1, $nbResultPerPage, true, $aBindSearch);
                //debug(strtr($sSQL, $aBindSearch));
                $return = $oConnection->querytab($sSQL, $aBindSearch);
                //           debug($return);
                $_SESSION[APP]["research"][$_SESSION[APP]["current_search"]["hash"]]["results"][$nbResultPerPage."_".$current_page."_".$order] = $return;
            }

            return $return;
        }

        /**
         * __DESC__.
         *
         * @static __DESC__
         * @access public
         *
         * @param __TYPE__ $aField __DESC__
         * @param __TYPE__ $BindKey __DESC__
         * @param __TYPE__ $aBind __DESC__
         * @param __TYPE__ $join (option) __DESC__
         * @param __TYPE__ $order (option) __DESC__
         *
         * @return __TYPE__
         */
        public function containsClause($aField, $BindKey, &$aBind, $join = "OR", $order = 1)
        {
            $oConnection = Pelican_Db::getInstance();
            if ($aField) {
                if ($oConnection->info["type"] == "MySQL") {
                    $aValue = explode(" ", $aBind[$BindKey]);
                } else {
                    $aValue = array($aBind[$BindKey]);
                }
                if (!is_array($aField)) {
                    $aField = array($aField);
                }
                foreach ($aValue as $value) {
                    foreach ($aField as $key => $field) {
                        $return[] = $oConnection->getSearchClause($field, $value, $order, $BindKey, $aBind, $join);
                        $order++;
                    }
                }
                $return = "(".implode(" ".$join." ", $return).")";
            }

            return $return;
        }

        /**
         * Fonction qui utilise la recherche FULLTEXT.
         *
         * @static __DESC__
         * @access public
         *
         * @param __TYPE__ $sField __DESC__
         * @param __TYPE__ $BindKey __DESC__
         * @param __TYPE__ $aBind __DESC__
         * @param __TYPE__ $join (option) __DESC__
         * @param __TYPE__ $order (option) __DESC__
         *
         * @return __TYPE__
         */
        public function matchClause($sField, $BindKey, &$aBind, $join = "OR", $order = 1)
        {
            $oConnection = Pelican_Db::getInstance();
            if ($sField) {
                if ($oConnection->info["type"] == "MySQL") {
                    $return = $oConnection->getSearchClause($sField, $aBind[$BindKey], $order, $BindKey, $aBind, $join, true);
                } else {
                    //a implémenter pour les autre bdd
                    //exemple postgres http://www.postgresql.org/docs/8.3/static/textsearch-controls.html
                }
                //$return = "(" . implode(" " . $join . " ", $return) . ")";
            }

            return $return;
        }
}
