<?php

/**
 * Menu de gauche : formulaire de recherche des contenus (onglet Contenus) et liste des Pages sous forme d'arborescence
 *
 * @package Pelican_BackOffice
 * @subpackage Navigation
 * @author Raphaël Carles <rcarles@businessdecision.com>
 * @since 28/05/2004
 */
include_once (pelican_path('Form'));

class Cms_Navigation_Controller extends Pelican_Controller_Back
{

    protected $displaySearch;

    protected $templateRecherche;

    protected $maxOnglet;

    protected $countOnglet;

    public function getTreeJsonAction ()
    {
        // $tree = $this->getPageHierarchy("page", "menu", "", $this->getView()->getHead()->skinPath, 'jstree');
        $oConnection = Pelican_Db::getInstance();
        $skinPath = $this->getView()->getHead()->skinPath;
        $templatePage = Pelican::$config["TPL_PAGE"];
        $action = "menu";
        $aBind[":SITE_ID"] = ($site_id ? $site_id : $_SESSION[APP]['SITE_ID']);
        // $aBind [":LANGUE_ID"] = $_SESSION[APP]['LANG'];
        $aBind[":USER_ID"] = $_SESSION[APP]["user"]["id"];
        
        $sqlPage = self::_getPageQuery($templatePage, (! $_SESSION[APP]["user"]["main"]), "", (! $_SESSION[APP]["user"]["main"] ? "void" : $action), $skinPath);
        $listPage = $oConnection->queryTab($sqlPage, $aBind);
        
        if (! $_SESSION[APP]["user"]["main"]) {
            // $sqlPage = getPageQuery($templatePage, false, " = :USER_ID",
            // $action);
            $sqlPage = self::_getPageQuery($templatePage, false, " like '%" . $aBind[":USER_ID"] . "%'", $action, $skinPath);
            $listPage2 = $oConnection->queryTab($sqlPage, $aBind);
        }
        $oTree = Pelican_Factory::getInstance('Hierarchy.Tree', 'dtree' . $id . ($site_id ? $site_id : $_SESSION[APP]['SITE_ID']), "id", "pid");
        
        $oTree->addTabNode($listPage);
        if (isset($listPage2)) {
            $oTree->addTabNode($listPage2);
        }
        $oTree->setOrder("order", "ASC");
        $oTree->setTreeType($type);
        $jsontree = $oTree->buildJsonTree($oTree->aNodes);
        
        print(json_encode($jsontree));
    }

    public function indexAction ()
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
            $oConnection = Pelican_Db::getInstance();
            /**
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
                arsort($menu);
            foreach ($menu as $value) {
                if ($value["pid"] == $idNiveau1) {
                    $this->templateRecherche = $value["TEMPLATE_ID"];
                }
            }
            }
            $aTypeContenus = getComboValuesFromCache("Backend/ContentType", array(
                $_SESSION[APP]['SITE_ID'],
                "",
                $_GET["contenttype"],
                true
            ));
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
            if (! valueExists($_GET, "popup_content")) {
                if ($tree) {
                    $return = $this->_addSearchRubrique($tree);
                    // $this->getView()->default =
                    // execDefault($_SESSION[APP]['SITE_ID']);
                    if (! empty($_SESSION["MOVE"])) {
                        if (isset($oTree)) {
                            $node = ($oTree->aParams[$_SESSION["MOVE"]["id"]]["record"] - 1);
                        }
                        unset($_SESSION["MOVE"]);
                    }
                    if ($this->pid) {
                        $this->getView()->default = "<script type=\"text/javascript\">

						var valNode = dtreepage" . $_SESSION[APP]['SITE_ID'] . ".aIncrement[trim('" . $this->pid . "')];
						if (valNode) {
						dtreepage" . $_SESSION[APP]['SITE_ID'] . ".openTo(valNode);
						dtreepage" . $_SESSION[APP]['SITE_ID'] . ".s(valNode);
						sUrl = dtreepage" . $_SESSION[APP]['SITE_ID'] . ".aNodes[valNode].url
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
							dtreepage" . $_SESSION[APP]['SITE_ID'] . ".doDefault(top.initTree" . (isset($node) ? "," . $node : "") . ");
							top.initTree=false;
							</script>";
                        }
                    }
                } else {
                    $return = $this->_getSearchContentForm($this->templateRecherche);
                    $this->getView()->default = "<script type=\"text/javascript\">document.fFormContentSearch.submit();</script>";
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

    public static function getPageHierarchy ($id = "page", $action = "menu", $site_id = "", $skinPath = '', $type = "dtree")
    {
        $oConnection = Pelican_Db::getInstance();
        
        $templatePage = Pelican::$config["TPL_PAGE"];
        
        $aBind[":SITE_ID"] = ($site_id ? $site_id : $_SESSION[APP]['SITE_ID']);
        // $aBind [":LANGUE_ID"] = $_SESSION[APP]['LANG'];
        $aBind[":USER_ID"] = $_SESSION[APP]["user"]["id"];
        
        $sqlPage = self::_getPageQuery($templatePage, (! $_SESSION[APP]["user"]["main"]), "", (! $_SESSION[APP]["user"]["main"] ? "void" : $action), $skinPath);
        $listPage = $oConnection->queryTab($sqlPage, $aBind);
        
        if (! $_SESSION[APP]["user"]["main"]) {
            // $sqlPage = getPageQuery($templatePage, false, " = :USER_ID",
            // $action);
            $sqlPage = self::_getPageQuery($templatePage, false, " like '%" . $aBind[":USER_ID"] . "%'", $action, $skinPath);
            $listPage2 = $oConnection->queryTab($sqlPage, $aBind);
        }
        $oTree = Pelican_Factory::getInstance('Hierarchy.Tree', 'dtree' . $id . ($site_id ? $site_id : $_SESSION[APP]['SITE_ID']), "id", "pid");
        
        $oTree->addTabNode($listPage);
        if (isset($listPage2)) {
            $oTree->addTabNode($listPage2);
        }
        $oTree->setOrder("order", "ASC");
        $oTree->setTreeType($type);
        
        return $oTree->getTree();
    }

    /**
     * Ajout d'un onglet de recherche à du code Pelican_Html existant
     *
     * @return string
     * @param $tree string
     *            Code Pelican_Html de l'arborescence
     */
    protected function _addSearchRubrique ($tree)
    {
        if ($this->displaySearch) {
            if (! valueExists($_GET, "oRubrique")) {
                $_GET["oRubrique"] = "0";
            }
            /*
             * $oTab = Pelican_Factory::getInstance('Index.Tab',"tabContent"); $oTab->addTab(t('RUBRIQUES'), "ongletRubrique0", ($_GET["oRubrique"] == "0"), "", "top.activeOngletRubrique(document, '0');", "", "petit"); $oTab->addTab(t('CONTENUS'), "ongletRubrique1", ($_GET["oRubrique"] == "1"), "", "top.activeOngletRubrique(document, '1');", "", "petit"); $return = Pelican_Html::div(array(style=>"height:31px"),$oTab->getTabs());
             */
            
            $oTab = Pelican_Factory::getInstance('Form.Tab', "tab" . $this->sFormName);
            
            $oTab->addTab(t('RUBRIQUES'), "ongletRubrique0", ($_GET["oRubrique"] == "0"), "", "top.activeOngletRubrique(document, '0');", "", "petit");
            $oTab->addTab(t('CONTENUS'), "ongletRubrique1", ($_GET["oRubrique"] == "1"), "", "top.activeOngletRubrique(document, '1');", "", "petit");
            
			
			
            $return = Pelican_Html::div(array(
                "style" => "height:31px"
            ), $oTab->getTabs());
            
			
			$return .= "<div style=\"height:31px\"><div id=\"img_resize_plus\" style=\"margin-top:-29px;width:20px;height:31px;margin-left:160px\"><img src=".Pelican::$config['MEDIA_HTTP']."/design/frontend/images/picto/picto-plus.png></div></div>"; 
			$return .= "<div id=\"img_resize_moins\" style=\"margin-top:-58px;width:20px;height:31px;margin-left:333px;display:none\"><img src=".Pelican::$config['MEDIA_HTTP']."/design/frontend/images/picto/picto-moins.png  width=18px height=18px/></div>";
			
            $return .= "<div id=\"divRubrique0\">" . $tree . "</div>";
            $return .= "<div id=\"divRubrique1\" style=\"display:none;\"><br />";
            $return .= $this->_getSearchContentForm($this->templateRecherche);
            $return .= "</div>";
			
			

        } else {
            $return = $tree;
        }
        
        return $return;
    }

    protected function _getSearchContentForm ($template)
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
        $form .= beginFormTable("0", "0", "form", false);
        $form .= $oForm->createInput("rechercheTexte", t('Content research') . " :", 50, "", false, "", false, 25);
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
            $bNotAllContentType
        ));
        if (count($aTypeContenus) == "1") {
            $form .= $oForm->createHidden("rechercheContentType", key($aTypeContenus));
            $form .= $oForm->createLabel(t('Content type') . " :", "<b>" . $aTypeContenus[key($aTypeContenus)] . "</b>");
        } else {
            $form .= $oForm->createComboFromList("rechercheContentType", "Type de contenu :", $aTypeContenus, "", false, false, "1", false, "165", t("-Tous les Types-"), false);
        }
        
        $aThemes = getComboValuesFromCache("Backend/Themes", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID']
        ));
          
        $form .= $oForm->createComboFromList("rechercheTheme", t('THEME') . " :", $aThemes, $aSelected, false, false, "1", false, "165", t("-Tous les Th鮥s-"), false);   
          
        $aPages = getComboValuesFromCache("Backend/Page", array(
            $_SESSION[APP]['SITE_ID'],
            $_SESSION[APP]['LANGUE_ID']
        ));
        if ($aPages) {
            if (isset($_SESSION[APP]['PAGE_ID'])) {
                $aSelected = array(
                    $_SESSION[APP]['PAGE_ID']
                );
            } else {
                $aSelected = null;
            }
            
            $form .= $oForm->createComboFromList("recherchePage", t('RUBRIQUE') . " :", $aPages, $aSelected, false, false, "1", false, "165", t("-Toutes les Rubriques-"), false);
        }
        
        
        if (valueExists($_GET, "popup_content")) {
            $publication = Pelican_Cache::fetch("State/Publication");
            $aPublication = Pelican_Cache::fetch("Backend/State", $publication);
            $form .= $oForm->createHidden("rechercheState", $publication[0]);
            $form .= $oForm->createLabel(t('State') . " :", "<b>" . $aPublication[0]["lib"] . "</b>");
        } else {
            $aStates = getComboValuesFromCache("Backend/State", (isset($param) ? $param : ""));
            unset($aStates[Pelican::$config["CORBEILLE_STATE"]]); // Masquage de l'état "A supprimer"
            $form .= $oForm->createComboFromList("rechercheState", t('State') . " :", $aStates, "", false, false, "1", false, "165", t("-Tous les Statuts-"), false);
        }
        
        if ($_SESSION[APP]["user"]["main"]) {
            $form .= $oForm->createInput("rechercheAuteur", t('Author') . " :", 50, "", false, "", false, 25);
        } else {
            $form .= $oForm->createHidden("rechercheAuteur", $_SESSION[APP]["user"]["id"]);
        }
        $form .= $oForm->createInput("rechercheDateDebut", t('Created on') . " :", 10, "shortdate", false, "", false, 10);
        $form .= $oForm->createInput("rechercheDateFin", t('And on') . " :", 10, "shortdate", false, "", false, 10);
        $form2 = '';
        
        /**
         * Si un seul type de contenu est défini
         */
        if (valueExists($_GET, "popup_content") && valueExists($_GET, "mutualisation") && count($aTypeContenus) == "1") {
            $aBind[":CONTENT_TYPE_ID"] = $_GET["contenttype"];
            $aBind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
            $aBind[":CONTENT_TYPE_SITE_RECEPTION"] = 1;
            $aBind[":CONTENT_TYPE_SITE_EMISSION"] = 1;
            $reception = $oConnection->queryItem("select count(*) from #pref#_content_type_site where CONTENT_TYPE_ID=:CONTENT_TYPE_ID AND SITE_ID=:SITE_ID AND CONTENT_TYPE_SITE_RECEPTION=:CONTENT_TYPE_SITE_RECEPTION", $aBind);
            /**
             * Si le site peut recevoir des contenus de ce type
             */
            if ($reception) {
                $form .= $oForm->showSeparator();
                /*
                 * Création du Pelican_Html de la combo des sites par profiles
                 */
                $aResult = $oConnection->getTab("select #pref#_site.SITE_ID, SITE_LABEL from #pref#_site, #pref#_content_type_site where #pref#_site.SITE_ID=#pref#_content_type_site.SITE_ID AND CONTENT_TYPE_ID=:CONTENT_TYPE_ID AND (CONTENT_TYPE_SITE_EMISSION=:CONTENT_TYPE_SITE_EMISSION OR #pref#_site.SITE_ID=:SITE_ID) ORDER BY SITE_LABEL", $aBind);
                /**
                 * S'il y a d'autres sites à consulter, on affiche la combo
                 */
                if (count($aResult) > 1) {
                    if ($aResult) {
                        foreach ($aResult as $valeur) {
                            $aSites[$valeur[0]] = $valeur[1];
                        }
                    }
                    $form .= $oForm->createComboFromList("rechercheSite", t('SITE') . " : ", $aSites, array(
                        $_SESSION[APP]['SITE_ID']
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
        
        /**
         * Recherche avancée
         */
        $form .= endFormTable(false);
        $form .= $form2;
        $oForm->_sDefaultFocus = "";
        $oForm->createJs("if (allblank(obj)) {
			alert(\"Veuillez remplir au moins un critère de recherche\");
            
			return false;
			}");
        /**
         * pour avoir la fonction isBlank il faut inclure le bon .
         *
         *
         *
         *
         *
         * js
         */
		 
        $oForm->_aIncludes["text"] = true;
        $form .= $oForm->close();
        $form .= "</div>";
        
        // Zend_Form start
        if (($oForm instanceof Zend_Form)) {
            /**
             * ******** Pour faire correspondre *********
             */
            $form = '<div class=\"content\">';
            $form .= formToString($oForm, $form);
            $form .= '</div>';
        /**
         * ******************************************
         */
        }
        // Zend_Form stop
        return $form;
    }
	
    protected static function _getPageQuery ($templatePage, $readOnly = false, $userClause = "", $action = "menu", $skinPath = '')
    {
        $oConnection = Pelican_Db::getInstance();
        $img = "";
        $sqlPage = "";
        $sqlFrom = "";
        $sqlWhere = "";
        
        if ($readOnly) {
            $img = "_red";
        }
        
        switch ($action) {
            case "menu":
                {
                    $isMultilang = true;
                    $js = $oConnection->getConcatClause(array(
                        "'javascript:menu('",
                        $templatePage,
                        "','''','",
                        "p.PAGE_ID",
                        "')'"
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
                        "'javascript:" . $action . "('",
                        "p.PAGE_ID",
                        "','''",
                        "REPLACE(PAGE_TITLE_BO,'''','&quot;')",
                        "''');'"
                    ));
                    break;
                }
        }
        
        $aBind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
        $sql = "SELECT sl.LANGUE_ID , l.LANGUE_LABEL, l.LANGUE_CODE
                FROM #pref#_language l, #pref#_site_language sl
                WHERE sl.langue_id = l.langue_id
                AND sl.site_id = :SITE_ID";
        $aOngletLangue = $oConnection->queryTab($sql, $aBind);
        // $_SESSION[APP]["LANGUE_ID_EDITORIAL_DEFAUT"] = $aOngletLangue[0]['LANGUE_ID'];
        
        $aBind[":STATE_ID"] = Pelican::$config["CORBEILLE_STATE"];
        /*
         * $sqlPage = "select p.PAGE_ID as \"id\", PAGE_PARENT_ID as \"pid\", PAGE_TITLE_BO as \"lib\", PAGE_ORDER as \"order\", PAGE_PATH as \"path\", " . $oConnection->getCaseClause ( "PAGE_GENERAL", array ("1" => $js ), $js ) . " as \"url\", " . $oConnection->getCaseClause ( "PAGE_GENERAL", array ("1" => "'" . $skinPath . "/images/tree_base.gif'" ), "'" . $skinPath . "/images/tree_table" . $img . ".gif'" ) . " as \"icon\", " . $oConnection->getCaseClause ( "PAGE_GENERAL", array ("1" => "'" . $skinPath . "/images/tree_base.gif'" ), "'" . $skinPath . "/images/tree_table" . $img . ".gif'" ) . " as \"iconOpen\" FROM #pref#_page p INNER JOIN #pref#_page_version pv on (p.PAGE_ID = pv.PAGE_ID AND p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION AND p.LANGUE_ID = pv.LANGUE_ID) WHERE p.SITE_ID=:SITE_ID AND p.LANGUE_ID = " . ($_SESSION[APP]["LANGUE_ID_EDITORIAL_DEFAUT"] ? $_SESSION[APP]["LANGUE_ID_EDITORIAL_DEFAUT"] : 1); if ($userClause) { $sqlPage .= " AND p.PAGE_CREATION_USER " . $userClause; } if ($action != "menu" || $userClause) { $sqlPage .= " AND (PAGE_GENERAL = 0 OR PAGE_GENERAL IS NULL)"; } //on n'affiche pas les éléments de la corbeille $sqlPage .= " AND pv.STATE_ID <> ".$aBind[":STATE_ID"];
         */
        if ($isMultilang) {
            $aBind[":SITE_ID"] = $_SESSION[APP]['SITE_ID'];
            $sql = "SELECT sl.LANGUE_ID
					FROM  #pref#_site_language sl
					WHERE sl.site_id = :SITE_ID";
            $aSiteLangue = $oConnection->queryTab($sql, $aBind);
        }
        $sStateFields = '';
        if (count($aSiteLangue) >= 1) {
            $sStateFields = '';
            foreach ($aSiteLangue as $key => $lang) {
                
                $sStateFields .= sprintf("pv%s.PAGE_START_DATE as \"start\",
                                  pv%s.PAGE_END_DATE as \"end\",
                                  pv%s.STATE_ID as \"state\",
                                ", $lang['LANGUE_ID'], $lang['LANGUE_ID'], $lang['LANGUE_ID']);
            }
        }
        // requete sur 'p' à vide et on comble les titres selon les langues où ils existent
        $sqlPage = "select
            p.PAGE_ID as \"id\",
            p.PAGE_PARENT_ID as \"pid\",
            p.PAGE_ORDER as \"order\",
            p.PAGE_PATH as \"path\",
            p.PAGE_STATUS as \"status\",
			p.PAGE_CURRENT_VERSION as \"current_version\",
			p.PAGE_GENERAL as \"page_general\",
			$sStateFields
           
            " . $oConnection->getCaseClause("p.PAGE_GENERAL", array(
            "1" => $js
        ), $js) . " as \"url\",
            " . $oConnection->getCaseClause("p.PAGE_GENERAL", array(
            "1" => "'" . $skinPath . "/images/tree_base.gif'"
        ), "'" . $skinPath . "/images/tree_table" . $img . ".gif'") . " as \"icon\",
            " . $oConnection->getCaseClause("p.PAGE_GENERAL", array(
            "1" => "'" . $skinPath . "/images/tree_base.gif'"
        ), "'" . $skinPath . "/images/tree_table" . $img . ".gif'") . " as  \"iconOpen\"
            ";
        
        if (count($aSiteLangue) >= 1) {
            
            $aLngPageTitleBO = array();
            $aLngPageLangue = array();
            $incr = 0;
            foreach ($aSiteLangue as $lng) {
                
                $incr ++;
                $x = $lng["LANGUE_ID"];
                // die();
                if ($lng["LANGUE_ID"] == $_SESSION[APP]['LANGUE_ID']) {
                    // first la langue en cours
                    $order = 0;
                } else {
                    $order = $incr;
                }
                $aLngPageTitleBO[$order] = "pv" . $x . ".PAGE_TITLE_BO";
                $aLngPageLangue[$order] = "pv" . $x . ".LANGUE_ID";
                $sqlFrom .= " LEFT JOIN #pref#_page p" . $x . " on (p.PAGE_ID = p" . $x . ".PAGE_ID AND p" . $x . ".langue_id = " . $x . ")
					 LEFT JOIN #pref#_page_version pv" . $x . " on (p" . $x . ".PAGE_ID = pv" . $x . ".PAGE_ID AND p" . $x . ".PAGE_DRAFT_VERSION = pv" . $x . ".PAGE_VERSION AND pv" . $x . ".LANGUE_ID = p" . $x . ".langue_id) 
					";
                $sqlPageState[] = "pv" . $x . ".STATE_ID <> " . $aBind[":STATE_ID"];
            }
            
            ksort($aLngPageTitleBO); // first la langue en cours
            $strLngPageTitleBO = implode(',', $aLngPageTitleBO);
            $sqlPage .= " , COALESCE(" . $strLngPageTitleBO . ") as \"lib\"";
            
            ksort($aLngPageLangue); // first la langue en cours
            $strLngPageLangue = implode(',', $aLngPageLangue);
            $sqlPage .= " , COALESCE(" . $strLngPageLangue . ") as \"langue_id\"";
            // $strLangClause = '';
            
            /*
             * if($_GET['ayoub']){ d $i=0; foreach($aLngPageLangue as $sLang){ $aClause1[] = sprintf('%s IS NOT NULL',$sLang); if($i==count($aLngPageLangue)-1){ $aClause2[] =sprintf('%s IS NULL',$sLang); }else{ $aClause2[] = sprintf('%s IS NOT NULL',$sLang); } $i++; } $strClause1 = sprintf('(%s) = true THEN %s',implode(' AND ',$aClause1),$_SESSION[APP]['LANGUE_ID']); $strClause2 = sprintf('(%s) = true THEN %s',implode(' AND ',$aClause2),reset($aLngPageLangue)); $sqlPage .= sprintf(' , CASE WHEN %s WHEN %s ELSE "NO LANG" END AS "page_langue_id"', $strClause1,$strClause2); }else{ $strLngPageLangue = implode (',', $aLngPageLangue); $sqlPage .= " , COALESCE(".$strLngPageLangue.") as \"langue_id\""; }
             */



            /* pv1.LANGUE_ID IS NOT NULL AND pv35.LANGUE_ID IS NOT NULL)=true THEN pv35.LANGUE_ID
              WHEN (pv1.LANGUE_ID IS NOT NULL AND pv35.LANGUE_ID IS NULL)=true THEN
              pv1.LANGUE_ID
              ELSE 'NO LANG'
              END AS "CURRENT_LANGUE_ID",
             */
        } else {
            
            $sqlPage .= " , pv.PAGE_TITLE_BO as \"lib\" ";
            $sqlFrom .= " INNER JOIN #pref#_page_version pv on (p.PAGE_ID = pv.PAGE_ID AND p.PAGE_DRAFT_VERSION = pv.PAGE_VERSION AND p.LANGUE_ID = pv.LANGUE_ID)";
            $sqlWhere = " AND p.LANGUE_ID = " . ($_SESSION[APP]['LANGUE_ID'] ? $_SESSION[APP]['LANGUE_ID'] : $aOngletLangue[0]['LANGUE_ID']);
        }
        
        $sqlPage .= " FROM #pref#_page p ";
        $sqlPage .= $sqlFrom;
        $sqlPage .= " WHERE p.SITE_ID=:SITE_ID ";
        $sqlPage .= $sqlWhere;
        
        if ($userClause) {
            $sqlPage .= " AND p.PAGE_CREATION_USER " . $userClause;
        }

		if( $_SESSION[APP]['PROFIL_LABEL'] == Pelican::$config['PROFILE']['ADMINISTRATEUR'] || $_SESSION[APP]['PROFIL_LABEL'] == Pelican::$config['PROFILE']['IMPORTATEUR']){
			$userClause	= '';
		}
		
        if ($action != "menu" || $userClause ) {
            
            $sqlPage .= " AND (p.PAGE_GENERAL = 0 OR p.PAGE_GENERAL IS NULL)";
        }
        
        // on n'affiche pas les éléments de la corbeille
        if (count($aSiteLangue) >= 1) {
            $sqlPage .= " AND (" . implode(" OR ", $sqlPageState) . ")";
        } else {
            
            $sqlPage .= " AND pv.STATE_ID <> " . $aBind[":STATE_ID"];
        }
        $sqlPage .= " GROUP BY p.PAGE_ID";
        return $sqlPage;
    }
	
	
}