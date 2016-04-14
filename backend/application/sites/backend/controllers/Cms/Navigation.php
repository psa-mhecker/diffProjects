<?php

/**
 * Menu de gauche : formulaire de recherche des contenus (onglet Contenus) et liste des Pages sous forme d'arborescence.
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 28/05/2004
 */
use Itkg\Hierarchy\Tree;

include_once pelican_path('Form');

class Cms_Navigation_Controller extends Pelican_Controller_Back
{
    protected $displaySearch;

    protected $templateRecherche;

    protected $maxOnglet;

    protected $countOnglet;

    public function getTreeJsonAction()
    {
        // JIRA NDP-2867 - Arborescence BO : si aucune page n'est configurée, l'arbo du BO est incohérente
        // le premier appel ajax envoie le parametre id=0 et recoit l'arborescence complete.
        // Si un appel ajax est fait depuis une page, ne rien retourner puisque l'arborescence complete est deja chargee
        if (isset($_REQUEST["id"]) && $_REQUEST["id"] !== '0' ) {
            return;
        }
        $type = 'dtree';
        $oConnection = Pelican_Db::getInstance();
        $skinPath = $this->getView()->getHead()->skinPath;
        $templatePage = Pelican::$config["TPL_PAGE"];
        $action = "menu";
        if($_GET['tid'] == Pelican::$config['TEMPLATE_ADMIN_PAGETAGGAGE']) {
            $templatePage = Pelican::$config['TEMPLATE_ADMIN_PAGETAGGAGE'];
        }
        $aBind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
        $aBind[":USER_ID"] = $_SESSION[APP]["user"]["id"];
        $aBind[":LANGUE_ID"] = $_SESSION[APP]['LANGUE_ID'];

        $sqlPage = $this->getPageQuery($templatePage, (! $_SESSION[APP]["user"]["main"]), "", (! $_SESSION[APP]["user"]["main"] ? "void" : $action), $skinPath);
        $listPage = $oConnection->queryTab($sqlPage, $aBind);

         if (! $_SESSION[APP]["user"]["main"]) {
            $sqlPage = $this->getPageQuery($templatePage, false, " like '%".$aBind[":USER_ID"]."%'", $action, $skinPath);
            $listPage2 = $oConnection->queryTab($sqlPage, $aBind);
        }

        $tree = new Tree('dtree'.$_SESSION[APP]['SITE_ID'], "id", "pid");
        $options['LIB_PATH'] = Pelican::$config['LIB_PATH'];
        $options['LIB_HIERARCHY'] = Pelican::$config['LIB_HIERARCHY'];
        $options['tid'] = Pelican::$config['TPL_PAGE'];
        $tree->setOptions($options);

        $tree->addTabNode($listPage);
        if (isset($listPage2)) {
            $tree->addTabNode($listPage2);
        }
        $tree->setOrder("order", "ASC");
        $tree->setTreeType($type);

        $jsontree = $tree->buildJsonTree($tree->aNodes);

        print(json_encode($jsontree));
    }

    public function indexAction()
    {
        if (! empty($_SESSION[APP]["search"])) {
            if ($_SESSION[APP]["search"]["type"] == "PAGE") {
                $this->pid = $_SESSION[APP]["search"]["id"];
            } else {
                $this->cid = $_SESSION[APP]["search"]["id"];
            }
            unset($_SESSION[APP]["search"]);
        }

        if ($_SESSION[APP]["content_type"]["id"]) {
            /*
             * Objet de gestion de formulaire
             */
            if (! valueExists($_GET, "contenttype")) {
                $_GET["contenttype"] = implode(",", $_SESSION[APP]["content_type"]["id"]);
            }
            $this->displaySearch = true;
            $aOnglet = $this->getParam('aOnglet');

            $idNiveau1 = $aOnglet[$_GET["view"]]["id"];
            $menu = $aOnglet[$_GET["view"]]["navigation"];
            if (is_array($menu)) {
                foreach ($menu as $value) {
                    if ($value["pid"] == $idNiveau1) {
                        $this->templateRecherche = $value["TEMPLATE_ID"];
                    }
                }
            }

            if ($_SESSION[APP]["content_type"]["id"][1]) {
                if (empty($_SESSION[APP]['CURRENT_PAGE_PATH'])) {
                    $_SESSION[APP]['CURRENT_PAGE_PATH'] = '';
                }
                $treeType = "jstree";
                // remplacé par l'appel ajax de la méthode getTreeJsonAction
                // @TODO: optimiser getPageHierarchy pour le cas du js tree
                $tree = $this->getPageHierarchy("page", "menu", "", $this->getView()
                    ->getHead()->skinPath, $treeType);
            }
            if (! valueExists($_GET, "popup_content") && $tree) {
                    $return = $this->_addSearchRubrique($tree);
                    if (! empty($_SESSION["MOVE"])) {
                        if (isset($oTree)) {
                            $node = ($oTree->aParams[$_SESSION["MOVE"]["id"]]["record"] - 1);
                        }
                        unset($_SESSION["MOVE"]);
                    }
                    if ($this->pid) {
                        $this->getView()->default = "<script type=\"text/javascript\">

						var valNode = dtreepage".$_SESSION[APP]['SITE_ID'].".aIncrement[trim('".$this->pid."')];
						if (valNode) {
						dtreepage".$_SESSION[APP]['SITE_ID'].".openTo(valNode);
						dtreepage".$_SESSION[APP]['SITE_ID'].".s(valNode);
						sUrl = dtreepage".$_SESSION[APP]['SITE_ID'].".aNodes[valNode].url
						if (sUrl.indexOf(\"javascript\")!=-1) {
						eval(sUrl.replace(\"javascript:\",\"\"));
						} else {
						document.location.href=sUrl;
						}
						} else {
						alert('cette page n\\'existe pas')
						}

						</script>";
                    } elseif ($this->cid) {
                        $this->getView()->default = "<script type=\"text/javascript\">top.activeOngletRubrique(document, '1');</script>";
                    } else {
                        if ($treeType == 'dtree') {
                            $this->getView()->default = "<script type=\"text/javascript\">
							dtreepage".$_SESSION[APP]['SITE_ID'].".doDefault(top.initTree".(isset($node) ? ",".$node : "").");
							top.initTree=false;
							</script>";
                        }
                    }
            } else {
                $return = $this->_getSearchContentForm($this->templateRecherche);
                $this->getView()->default = "<script type=\"text/javascript\">document.fFormContentSearch.submit();</script>";
            }
            $this->assign('body', $return, false);
            $this->assign('treeId', $_SESSION[APP]['SITE_ID']);
            $this->fetch();
        }
    }

    public function getPageHierarchy($id = "page", $action = "menu", $site_id = "", $skinPath = '', $type = "dtree")
    {
        $oConnection = Pelican_Db::getInstance();

        $templatePage = Pelican::$config["TPL_PAGE"];

        $aBind[":SITE_ID"] = ($site_id ? $site_id : $_SESSION[APP]['SITE_ID']);
        $aBind[":USER_ID"] = $_SESSION[APP]["user"]["id"];
        $aBind[":LANGUE_ID"] = $_SESSION[APP]['LANGUE_ID'];

        $sqlPage = $this->getPageQuery($templatePage, (! $_SESSION[APP]["user"]["main"]), "", (! $_SESSION[APP]["user"]["main"] ? "void" : $action), $skinPath);
        $listPage = $oConnection->queryTab($sqlPage, $aBind);

        if (! $_SESSION[APP]["user"]["main"]) {
            $sqlPage = $this->getPageQuery($templatePage, false, " like '%".$aBind[":USER_ID"]."%'", $action, $skinPath);
            $listPage2 = $oConnection->queryTab($sqlPage, $aBind);
        }
        $tree = new Tree('dtree'.$id.($site_id ? $site_id : $_SESSION[APP]['SITE_ID']), "id", "pid");
        $options['LIB_PATH'] = Pelican::$config['LIB_PATH'];
        $options['LIB_HIERARCHY'] = Pelican::$config['LIB_HIERARCHY'];
        $options['tid'] = Pelican::$config['TPL_PAGE'];
        $tree->setOptions($options);

        $tree->addTabNode($listPage);
        if (isset($listPage2)) {
            $tree->addTabNode($listPage2);
        }
        $tree->setOrder("order", "ASC");
        $tree->setTreeType($type);
        return $tree->getTree();
    }

    /**
     * Ajout d'un onglet de recherche à du code Pelican_Html existant.
     *
     * @return string
     *
     * @param $tree string
     *                Code Pelican_Html de l'arborescence
     */
    protected function _addSearchRubrique($tree)
    {
        if ($this->displaySearch) {
            if (! valueExists($_GET, "oRubrique")) {
                $_GET["oRubrique"] = "0";
            }
            $oTab = Pelican_Factory::getInstance('Form.Tab', "tab".$this->sFormName);
            $oTab->addTab(t('RUBRIQUES'), "ongletRubrique0", ($_GET["oRubrique"] == "0"), "", "top.activeOngletRubrique(document, '0');", "", "petit");
            $oTab->addTab(t('CONTENUS'), "ongletRubrique1", ($_GET["oRubrique"] == "1"), "", "top.activeOngletRubrique(document, '1');", "", "petit");

            $return = Pelican_Html::div(array(
                "style" => "height:31px",
            ), $oTab->getTabs());

            $return .= '<div style="height:31px; position:relative">
                            <div id="img_resize_moins" style="cursor:pointer;margin-top:-29px;width:20px;height:31px;float:right;display:none"><img src="'.Pelican::$config['MEDIA_HTTP'].'/design/backend/images/silk/arrow_left.png"  width="16px" height="16px"/></div>
                            <div id="img_resize_plus" style="cursor:pointer;margin-top:-29px;width:20px;height:31px;float:right;"><img src="'.Pelican::$config['MEDIA_HTTP'].'/design/backend/images/silk/arrow_right.png"  width="16px" height="16px" /></div>
                        </div>';


            $return .= "<div id=\"divRubrique0\">".$tree."</div>";
            $return .= "<div id=\"divRubrique1\" style=\"display:none;\"><br />";
            $return .= $this->_getSearchContentForm($this->templateRecherche);
            $return .= "</div>";
        } else {
            $return = $tree;
        }

        return $return;
    }

    protected function _getSearchContentForm($template)
    {
        global $oConnection;

        $form = "<div class=\"content\">";
        $oForm = Pelican_Factory::getInstance('Form', false, "vertical", "formlib_recherche", "formlib_recherche");
        $form .= $oForm->open(Pelican::$config["PAGE_INDEX_IFRAME_PATH"], "get", "fFormContentSearch", false, true, "CheckForm", "iframeRight", false, false);
        $form .= $oForm->createHidden("id", (! empty($this->cid) ? $this->cid : ''));
        $form .= $oForm->createHidden("tid", $template);
        $form .= $oForm->createHidden("lang", (! empty($_GET["lang"]) ? $_GET["lang"] : ''));
        $form .= $oForm->createHidden("view", (isset($_REQUEST["view"]) ? $_REQUEST["view"] : ""));
        $form .= $oForm->createHidden("popup_content", (isset($_GET["popup_content"]) ? $_GET["popup_content"] : ""));
        $form .= $oForm->createHidden("mutualisation", (isset($_GET["mutualisation"]) ? $_GET["mutualisation"] : ""));
        if ($_GET['rechercheContentCode2']) {
            $form .= $oForm->createHidden("rechercheContentCode2", (isset($_GET["rechercheContentCode2"]) ? $_GET["rechercheContentCode2"] : ""));
        }
        $form .= beginFormTable("0", "0", "form", false);
        $form .= $oForm->createInput("rechercheTexte", t('Content research')." :", 50, "", false, "", false, 25);
        $form .= $oForm->createFreeHtml("<tr><td class=\"formval\">&nbsp;</td></tr>");
        $form .= $oForm->showSeparator();
        $bNotAllContentType = true;

        if (valueExists($_GET, "popup_content")) {
            // Dans le cas de la popup on ne fait plus le filtre sur le champ
            // CONTENT_TYPE_ADMINISTRATION de la table
            // ".Pelican::$config['FW_PREFIXE_TABLE']."content_type
            $bNotAllContentType = false;
        }
        $aTypeContenus = getComboValuesFromCache("Backend/ContentType", array(
            $_SESSION[APP]['SITE_ID'],
            "",
            $_GET["contenttype"],
            $bNotAllContentType,
            true
        ));
        if (count($aTypeContenus) == "1") {
            $form .= $oForm->createHidden("rechercheContentType", key($aTypeContenus));
            $form .= $oForm->createLabel(t('Content type')." :", "<b>".$aTypeContenus[key($aTypeContenus)]."</b>");
        } else {
            $form .= $oForm->createComboFromList("rechercheContentType", "Type de contenu :", $aTypeContenus, "", false, false, "1", false, "165", t("-Tous les Types-"), false);
        }

        if (valueExists($_GET, "popup_content")) {
            $publication = Pelican_Cache::fetch("State/Publication");
            $aPublication = Pelican_Cache::fetch("Backend/State", $publication);
            $form .= $oForm->createHidden("rechercheState", $publication[0]);
            $form .= $oForm->createLabel(t('State')." :", "<b>".$aPublication[0]["lib"]."</b>");
        } else {
            $aStates = getComboValuesFromCache("Backend/State", (isset($param) ? $param : ""));
            unset($aStates[Pelican::$config["CORBEILLE_STATE"]]); // Masquage de l'état "A supprimer"
            $form .= $oForm->createComboFromList("rechercheState", t('State')." :", $aStates, "", false, false, "1", false, "165", t("-Tous les Statuts-"), false);
        }

        if ($_SESSION[APP]["user"]["main"]) {
            $form .= $oForm->createInput("rechercheAuteur", t('Author')." :", 50, "", false, "", false, 25);
        } else {
            $form .= $oForm->createHidden("rechercheAuteur", $_SESSION[APP]["user"]["id"]);
        }
        $form .= $oForm->createInput("rechercheDateDebut", t('Created on')." :", 10, "shortdate", false, "", false, 10);
        $form .= $oForm->createInput("rechercheDateFin", t('And on')." :", 10, "shortdate", false, "", false, 10);
        $form2 = '';

        /*
         * Si un seul type de contenu est défini
         */
        if (valueExists($_GET, "popup_content") && valueExists($_GET, "mutualisation") && count($aTypeContenus) == "1") {
            $aBind[":CONTENT_TYPE_ID"] = $_GET["contenttype"];
            $aBind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
            $aBind[":CONTENT_TYPE_SITE_RECEPTION"] = 1;
            $aBind[":CONTENT_TYPE_SITE_EMISSION"] = 1;
            $reception = $oConnection->queryItem("select count(*) from #pref#_content_type_site where CONTENT_TYPE_ID=:CONTENT_TYPE_ID AND SITE_ID=:SITE_ID AND CONTENT_TYPE_SITE_RECEPTION=:CONTENT_TYPE_SITE_RECEPTION", $aBind);
            /*
             * Si le site peut recevoir des contenus de ce type
             */
            if ($reception) {
                $form .= $oForm->showSeparator();
                /*
                 * Création du Pelican_Html de la combo des sites par profiles
                 */
                $aResult = $oConnection->getTab("select #pref#_site.SITE_ID, SITE_LABEL from #pref#_site, #pref#_content_type_site where #pref#_site.SITE_ID=#pref#_content_type_site.SITE_ID AND CONTENT_TYPE_ID=:CONTENT_TYPE_ID AND (CONTENT_TYPE_SITE_EMISSION=:CONTENT_TYPE_SITE_EMISSION OR #pref#_site.SITE_ID=:SITE_ID) ORDER BY SITE_LABEL", $aBind);
                /*
                 * S'il y a d'autres sites à consulter, on affiche la combo
                 */
                if (count($aResult) > 1) {
                    if ($aResult) {
                        foreach ($aResult as $valeur) {
                            $aSites[$valeur[0]] = $valeur[1];
                        }
                    }
                    $form .= $oForm->createComboFromList("rechercheSite", t('SITE')." : ", $aSites, array(
                        $_SESSION[APP]['SITE_ID'],
                    ), false, false, "1", false, "165", false);
                    $form .= $oForm->createFreeHtml("</tr>");
                } else {
                    $form2 = $oForm->createHidden("rechercheSite", $_SESSION[APP]['SITE_ID']);
                }
            } else {
                $form2 = $oForm->createHidden("rechercheSite", $_SESSION[APP]['SITE_ID']);
            }
        } else {
            $form2 = $oForm->createHidden("rechercheSite", $_SESSION[APP]['SITE_ID']);
        }

        $form .= $oForm->createFreeHtml("<tr><td class=\"formval\">&nbsp;</td></tr>");
        $form .= $oForm->showSeparator();

        $form .= $oForm->createFreeHtml("<tr>");
        $form .= $oForm->createFreeHtml("<td class=\"formval\"><center><br />");
        $form .= $oForm->createSubmit("submitRecherche", t('FORM_BUTTON_SEARCH'));
        $form .= $oForm->createFreeHtml("</center></td></tr>");

        /*
         * Recherche avancée
         */
        $form .= endFormTable(false);
        $form .= $form2;
        $oForm->_sDefaultFocus = "";
        $oForm->createJs("if (allblank(obj)) {
			alert(\"Veuillez remplir au moins un critère de recherche\");

			return false;
			}");
        /*
         * pour avoir la fonction isBlank il faut inclure le bon js
         */
        $oForm->_aIncludes["text"] = true;
        $form .= $oForm->close();
        $form .= "</div>";

        // Zend_Form start
        if (($oForm instanceof Zend_Form)) {
            /*
             * ******** Pour faire correspondre *********
             */
            $form = '<div class=\"content\">';
            $form .= formToString($oForm, $form);
            $form .= '</div>';
        /*
         * ******************************************
         */
        }
        // Zend_Form stop
        return $form;
    }

    protected function getPageQuery($templatePage, $readOnly = false, $userClause = "", $action = "menu", $skinPath = '')
    {
        $oConnection = Pelican_Db::getInstance();
        $img = "";

        if ($readOnly) {
            $img = "_red";
        }
        switch ($action) {
            case "menu":
                {
                    $js = $oConnection->getConcatClause(array(
                        "'javascript:menu('",
                        $templatePage,
                        "','''','",
                        "p1.PAGE_ID",
                        "')'",
                    ));
                    break;
                }
            case "void":
                {
                    $js = "''";
                    break;
                }
            default:
                {
                    $js = $oConnection->getConcatClause(array(
                        "'javascript:".$action."('",
                        "p.PAGE_ID",
                        "','''",
                        "REPLACE(PAGE_TITLE_BO,'''','&quot;')",
                        "''');'",
                    ));
                    break;
                }
        }

        $bind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
        $bind[":LANGUE_ID"] = $_SESSION[APP]['LANGUE_ID'];
        // récupération des langues du site
        $sql = "SELECT sl.LANGUE_ID
                FROM #pref#_site_language sl
                WHERE sl.site_id = :SITE_ID AND LANGUE_ID != :LANGUE_ID ";
        $temp = $oConnection->queryTab($sql, $bind);
        // la langue courante en premier
        $langues = [];
        $langues[] = $_SESSION[APP]['LANGUE_ID'];
        // les autres langues du site
        foreach ($temp as $langue) {
            $langues[] = $langue['LANGUE_ID'];
        }




        // requete sur 'p' à vide et on comble les titres selon les langues où ils existent
        $sqlPage = 'SELECT
            p1.PAGE_ID as "id",
            p1.PAGE_PARENT_ID as "pid",
            p1.PAGE_ORDER as "order",
            p1.PAGE_PATH as "path",
            p2.PAGE_STATUS as "status",
			p2.PAGE_CURRENT_VERSION as "current_version",
			p2.PAGE_DRAFT_VERSION as "draft_version",
			p2.PAGE_GENERAL as "page_general",
            pv.STATE_ID AS "state",
            pc.PAGE_START_DATE AS "start_prev",
            pc.PAGE_END_DATE AS "end_prev",
            '.$oConnection->getCaseClause("p2.PAGE_GENERAL", array(
            "1" => $js,
        ), $js)." as \"url\",
            ".$oConnection->getCaseClause("p2.PAGE_GENERAL", array(
            "1" => "'".$skinPath."/images/tree_base.gif'",
        ), "'".$skinPath."/images/tree_table".$img.".gif'")." as \"icon\",
            ".$oConnection->getCaseClause("p2.PAGE_GENERAL", array(
            "1" => "'".$skinPath."/images/tree_base.gif'",
        ), "'".$skinPath."/images/tree_table".$img.".gif'").' as  "iconOpen",

        COALESCE(pv.PAGE_TITLE_BO, lv.PAGE_TITLE_BO) AS "lib",
        COALESCE(p2.LANGUE_ID, p1.LANGUE_ID) AS "langue_id"
        ';
        // on selection toute les pages du site quelque soit la langue
        $sqlPage .= ' FROM (SELECT sp.PAGE_ID, sp.PAGE_PARENT_ID, sp.LANGUE_ID,sp.PAGE_ORDER, sp.PAGE_PATH, sp.PAGE_DRAFT_VERSION FROM #pref#_page sp WHERE SITE_ID=:SITE_ID GROUP BY sp.PAGE_ID ORDER BY sp.PAGE_ID, FIELD(LANGUE_ID, '.implode(',', $langues).') ) p1 ';
        //on fait la jointure avec les pages dispo dans cette langue uniquement
        $sqlPage .= '  LEFT JOIN (SELECT DISTINCT lp.PAGE_ID, lp.LANGUE_ID,  lp.PAGE_STATUS, lp.PAGE_CURRENT_VERSION, lp.PAGE_DRAFT_VERSION, lp.PAGE_GENERAL, lp.PAGE_CREATION_USER FROM #pref#_page lp WHERE SITE_ID=:SITE_ID AND LANGUE_ID=:LANGUE_ID) p2 ON p1.PAGE_ID=p2.PAGE_ID';
        // on fait la jointure avec les version
        $sqlPage .=' LEFT JOIN #pref#_page_version pv ON (p2.PAGE_ID = pv.PAGE_ID
                   AND p2.PAGE_DRAFT_VERSION = pv.PAGE_VERSION
                   AND p2.LANGUE_ID = pv.langue_id)
                LEFT JOIN #pref#_page_version pc ON (p2.PAGE_ID = pc.PAGE_ID
                       AND p2.PAGE_CURRENT_VERSION = pc.PAGE_VERSION
                       AND p2.LANGUE_ID = pc.langue_id)
                LEFT JOIN #pref#_page_version lv ON (p1.PAGE_ID = lv.PAGE_ID
                      AND p1.PAGE_DRAFT_VERSION = lv.PAGE_VERSION
                      AND p1.LANGUE_ID = lv.langue_id)

';
        $sqlPage .= " WHERE pv.STATE_ID <> ".Pelican::$config["CORBEILLE_STATE"]." OR pv.STATE_ID IS NULL ";

        if ($userClause) {
            $sqlPage .= " AND p2.PAGE_CREATION_USER ".$userClause;
        }

        if ($_SESSION[APP]['PROFIL_LABEL'] == Pelican::$config['PROFILE']['ADMINISTRATEUR']) {
            $userClause    = '';
        }

        if ($action != "menu" || $userClause) {
            $sqlPage .= " AND (p2.PAGE_GENERAL = 0 OR p2.PAGE_GENERAL IS NULL)";
        }

        return $sqlPage;
    }
}
