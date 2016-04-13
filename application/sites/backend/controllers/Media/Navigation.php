<?php

/**
 * Formulaire de gestion des médias
 *
 * @package Pelican_BackOffice
 * @subpackage Administration
 * @author Pierre Moiré <pierre.moire@businessdecision.com>
 * @since 04/03/2011
 */
ini_set('max_execution_time', 90);

require_once (pelican_path('Media'));
pelican_import('Hierarchy');
pelican_import('Hierarchy.Tree');
/**
 * Librairie de création d'onglets
 */
pelican_import('Index.Tab');
pelican_import('Index');

/**
 * Librairie de gestion de la mediathèque
 */
require_once (pelican_path('Media'));

class Media_Navigation_Controller extends Pelican_Controller_Back {

    protected $allowAdd;
    protected $allowDel;
    protected $rootImage;
    protected $begin;
    protected $rubrique;
    protected $end;
    protected $displaySearch;
    protected $noBegin = true;

    const SEPARATOR = '|';

    public function init() {
        $this->allowAdd = (Pelican::$config ["FW_MEDIA_ALLOW_ADD"] ? "true" : "false");
        $this->allowDel = (Pelican::$config ["FW_MEDIA_ALLOW_DEL"] ? "true" : "false");
        $this->rootImage = Pelican::$config ["SKIN_PATH"] . "/images/tree_media.gif";
    }

    /**
     * Servira à présenter la médiathèque en mode popup
     */
    public function indexAction() {
        $head = $this->getView()->getHead();

        if (!isset($dir)) {
            $dir = "";
        }
        $_REQUEST ["root"] = Pelican::$config ["MEDIA_ROOT"] . "/";
        $label = "&nbsp;(Racine)";
        $this->id = $_GET ["view"] . $_SESSION [APP] ['SITE_ID'];
        $head->setJS(Pelican::$config ["MEDIA_LIB_PATH"] . "/js/media_hmvc.js");
        $head->setScript("
        if (!current) {
			var current = new Object;
		}
		current.mediaType='" . $_GET ["media"] . "';
		current.rootPath='" . $dir . "';
		current.zone=\"media\";
		initTree=false;
		current.physicalPath=current.rootPath;");

        //pré-sélection du ratio attendu par le champ
        $head->endScript("
                if(windowArguments['ratio'] != undefined){
                    document.getElementById('ratio').value = windowArguments['ratio'];
                     var oParent;
                     if (parent.activeOngletMedia) {
                     oParent = parent;
                     } else {
                     oParent = top;
                     }
                     oParent.activeOngletMedia(document, '1');
                }
        ");
        $this->noBegin = false;
        $this->buildTree($label);
        $this->fetch();
    }

    protected function buildTree($label = '', $currentDir = '') {
        $head = $this->getView()->getHead();

        /**
         * Cas général
         */
        if (!$label) {
            $label = t('POPUP_MEDIA_' . trim(strtoupper($_REQUEST ["type"])));
        }

        if (!$this->id) {
            "popup" . trim(strtoupper($_REQUEST ["type"]));
        }
        if ($this->noBegin) {
            $head->setTitle("Pelican");
            $head->setMeta("http-equiv", "content-type", "text/html" . (Pelican::$config ["CHARSET"] ? "; charset=" . Pelican::$config ["CHARSET"] : ""));
            $head->setBackofficeSkin(Pelican::$config ["SKIN"], "/library/Pelican/Index/Backoffice/public/skins", Pelican::$config ["DOCUMENT_INIT"], "screen,print");
            $head->setJs(Pelican::$config ["LIB_PATH"] . Pelican::$config ['LIB_FORM'] . "/js/xt_mozilla_fonctions.js");

            $head->setScript("var libDir='" . Pelican::$config ["LIB_PATH"] . "';");
            $head->setScript("var mediaDir='.';");
            $head->setScript("var httpMediaDir='" . Pelican::$config ["MEDIA_HTTP"] . "';");

            // Inclusion des librairies ExtJS si necessaire
            /*
             * if(Pelican::$config['BO_USE_EXTJS_TREE']){
             * $head->setJS(Pelican::$config["LIB_PATH"]."/External/ext-".Pelican::$config['BO_EXTJS_VERSION']."/adapter/jquery/ext-jquery-adapter.js");
             * $head->setJS(Pelican::$config["LIB_PATH"]."/External/ext-".Pelican::$config['BO_EXTJS_VERSION']."/adapter/ext/ext-base.js");
             * $head->setJS(Pelican::$config["LIB_PATH"]."/External/ext-".Pelican::$config['BO_EXTJS_VERSION']."/ext-all.js");
             * $head->setJS(Pelican::$config["LIB_PATH"]."/Pelican/Hierarchy/Tree/public/extjs/Ext.Pelican.Tree.js");
             * $head->setCSS(Pelican::$config["LIB_PATH"]."/External/ext-".Pelican::$config['BO_EXTJS_VERSION']."/resources/css/ext-all.css");
             * }
             */

            $header = $head->getHeader();

            $this->begin = "
			<html>
			" . $header . "
			<body bgcolor=\"white\" leftmargin=\"3\" topmargin=\"3\">
			";
            $this->end = "</body>
			</html>";
        }

        $this->displaySearch = true;
        if (!isset($currentDir)) {
            $currentDir = "";
        }


        $this->rubrique = $this->getMediaTree($_SESSION [APP] ["SITE_MEDIA"], 1, $currentDir, $this->rootImage, $this->allowAdd, $this->allowDel);

        $this->assign('begin', $this->begin, false);

        if (!isset($this->end)) {
            $this->end = "";
        }
        if ($this->end) {
            // $cboSite = getComboValuesFromCache("Frontend/Site");
            if ($cboSite) {
                $cbo = "<center><select name=\"site_media\" onchange=\"document.location.href='" . str_replace("&SITE_MEDIA=" . $_GET ["SITE_MEDIA"], "", $_SERVER ["REQUEST_URI"]) . "&SITE_MEDIA=' + this.value\">";
                foreach ($cboSite as $key => $value) {
                    $cbo .= "<option value=\"" . $key . "\" " . ($key == $_SESSION [APP] ["SITE_MEDIA"] ? "selected" : "") . ">" . $value . "</option>";
                }
                $cbo .= "</select></center>";
            }
            $this->assign('cbo', $cbo);
        }

        if (Pelican::$config ['BO_USE_EXTJS_TREE'] && !$this->noBegin) {
            $tree = $this->showTree("extjs", "media", array('target' => 'divMedia0', 'defaultnode' => ''));
            $this->getView()->default = $this->execDefault("extjs");
        } else {
            $tree = $this->showTree("dtree", "media");
            $this->getView()->default = $this->execDefault("dtree");
        }

        $this->assign('tree', $tree, false);
        $this->assign('end', $this->end);
    }

    /**
     * Tri du tableau $this->rubrique sur la propriété "order" et affichage de
     * l'arborescence avec dtree
     *
     * @return string
     * @param $this->rubrique mixed
     *       	 Tableau contenant les informations liées aux répertoires
     *        	parcourus : contient
     *        	id,pid,lib,order,url,icon,iconOpen,isFolder,pathInfo
     * @param $this->id integer
     *       	 Identifiant du tableau
     * @param $type string
     *       	 "dtree" ou "xloadtree"
     * @param $complement string
     *       	 "paramètre supplémentaire pour "xloadtree"
     */
    protected function showTree($type = "extjs", $complement = "", $options = array()) {

        $object = $this->getMediaTree($_SESSION [APP] ["SITE_MEDIA"], 2, $this->id, $this->rootImage, $complement, $type, $options, rand(0, 1000));

        $tree = $this->addSearch($object);

        return $tree;
    }

    /**
     * Création du javascript permettant le lancement de la fonction javascript
     * appelée par le noeud de l'arborescence dont l'id est en cookie
     *
     * @return string
     * @param $this->id integer
     *       	 id du noeud de l'arbre enregistré en cookie
     */
    protected function execDefault($type = "extjs") {

        if (Pelican::$config ['BO_USE_EXTJS_TREE'] && $type == "dtree") {
            $type = "extjs";
        }

        if ($type == "dtree") {
            $default = "<script type=\"text/javascript\">
			dtree" . $this->id . ".doDefault(parent.initTree);
			parent.initTree=false;
			</script>";
        } else if ($type == "extjs") {
            $default = "<script type=\"text/javascript\">
		Ext.onReady(function (){
			Ext.getCmp('dtree" . $this->id . "').doDefault('');
		});
		</script>";
        }
        return $default;
    }

    /**
     * Ajout d'un onglet de recherche à du code Pelican_Html existant
     *
     * @return string
     * @param $tree string
     *       	 Code Pelican_Html de l'arborescence
     */
    protected function addSearch($tree) {
        if ($this->displaySearch) {
            if (!valueExists($_GET, "oMedia")) {
                $_GET ["oMedia"] = "0";
            }

            $oTab = Pelican_Factory::getInstance('Form.Tab', "tabSearch");

            $oTab->addTab(t("Dossiers"), "ongletMedia0", ($_GET ["oMedia"] == "0"), "", "var oParent;
				if (parent.activeOngletMedia) {
				oParent = parent;
				} else {
				oParent = top;
				}
                self.activeOngletMedia(document, '0');", "", "petit");
            $oTab->addTab(t('POPUP_SEARCH_TITLE'), "ongletMedia1", ($_GET ["oMedia"] == "1"), "", "var oParent;
				if (parent.activeOngletMedia) {
				oParent = parent;
				} else {
				oParent = top;
				}
                self.activeOngletMedia(document, '1');", "", "petit");

            $return = Pelican_Html::div(array("style" => "height:31px"), $oTab->getTabs());

            $return .= "<div id=\"divMedia0\">" . $tree . "</div>";
            $return .= "<div id=\"divMedia1\" style=\"display:none;\" >";
            $return .= $this->getSearchMediaForm();
            $return .= "</div>";
        } else {
            $return = $tree;
        }
        return $return;
    }

    /**
     * Fonction de création des éléments du menu contextuel de l'arbre
     *
     * @return void
     */
    protected function rightClick() {
        $menu ["tree"] = array(array(t('EDITOR_CUT'), "interceptHref(obj.href,\"parent.goMedia(\",\"moveFolder('cut',\");"), array(t('EDITOR_PASTE'), "interceptHref(obj.href,\"parent.goMedia(\",\"moveFolder('paste',\");", "!(parent.current.move['cut'])"), array(), array(t('POPUP_LABEL_ADD'), "interceptHref(obj.href,\"parent.goMedia\",\"top.setFolder\");top.setAction('add','folder')"), array("Editer", "interceptHref(obj.href,\"parent.goMedia\",\"top.setFolder\");top.setAction('edit','folder')"), array(t('POPUP_LABEL_DEL'), "interceptHref(obj.href,\"parent.goMedia\",\"top.setFolder\");top.setAction('del','folder')"));
        $return = getContextMenu($menu);

        $head = $this->getView()->getHead();
        $head->setJs(Pelican::$config ["MEDIA_LIB_PATH"] . '/js/rightClick.js');

        return $return;
    }

    // ///////////////////////////////////////
    // A déplacer à terme dans un modèle Pelican_Hierarchy_Tree nouvelle version
    // Ceci est une méthode allégée par rapport aux méthodes Tree+Hierarchy qui
    // se faisaient en plusieurs étapes
    protected function getSearchMediaForm() {

        $aAllowedExtensions = getAllowedExtensions();
        $aBind = array(':SITE_ID' => $_SESSION[APP]['SITE_ID']);
        $sYoutubeChannelsSql = 'SELECT SITE_YOUTUBE_USERS FROM #pref#_site WHERE SITE_ID=:SITE_ID';
        $oConnection = Pelican_Db::getInstance();
        $aChannelsResult = $oConnection->queryRow($sYoutubeChannelsSql, $aBind);
        if (isset($aChannelsResult) && !empty($aChannelsResult)) {
            $aChannels = explode(',', $aChannelsResult['SITE_YOUTUBE_USERS']);
            if (count($aChannels)) {
                $aChannelsForList = array();
                foreach ($aChannels as $sOneChannel) {
                    $aChannelsForList[$sOneChannel] = $sOneChannel;
                }
            }
        }
        $form = "<br /><div class=\"title\">" . t('POPUP_SEARCH_TITLE') . "</div><br />";
        $oForm = Pelican_Factory::getInstance('Form', false, "vertical");
        $form .= $oForm->open("/_/Index/child", "get", "fFormMediaSearch", false, true, "CheckForm", "iframeRight", false);
        $form .= beginFormTable("0", "0", "form", false);

        $form .= $oForm->createHidden("action", "search");
        $form .= $oForm->createHidden("type", "");
        $form .= $oForm->createHidden("tid", '17');
        // $form .= $oForm->createHidden("view", $_REQUEST["view"]);
        $form .= $oForm->createHidden("zone", $this->getParam('zone'));
        $form .= $oForm->createHidden("root", "");
        $form .= $oForm->createHidden("path", "");
        $form .= $oForm->createHidden("lib", "");

        $form .= $oForm->createInput("recherche", t("Rechercher les medias nommés ou avec l'id :"), 50, "", false, "", false, 25);
        if (true) {
            $form .= $oForm->createComboFromList("ratio", t("RECHERCHE_RATIO"), Pelican:: $config['RECHERCHE_RATIO'], $this->values["ratio"], false, $this->readO);
        }
        $form .= $oForm->createComboFromList("youtube_channel", t("RECHERCHE_PAR_CHANNEL"), $aChannelsForList, $this->values["youtube_channel"], false, $this->readO);
        $aYoutubeStatus = array("private" => "private", "public" => "public", "unlisted" => "unlisted");
        ;
        $form .= $oForm->createComboFromList("youtube_status", t("RECHERCHE_PAR_YOUTUBE_STATUS"), $aYoutubeStatus, $this->values["youtube_status"], false, $this->readO);

        $form .= $oForm->createDateTime("date_from", t("DATE_FROM"), false, $this->values["date_from"], false, $this->readO);
        $form .= $oForm->createDateTime("date_to", t("DATE_TO"), false, $this->values["date_to"], false, $this->readO);
        $form .= $oForm->createFreeHtml("<tr><td>&nbsp;</td></tr>");
        $form .= $oForm->createLabel(t("Rechercher dans :"), "<div id=\"mediaFolder\">&nbsp;</div>");

        /**
         * bouton
         */
        $form .= $oForm->createFreeHtml("<tr><td>&nbsp;</td></tr>");
        $form .= $oForm->createFreeHtml("<tr>");
        $form .= $oForm->createFreeHtml("<td class=\"formval\" align=\"center\">");
        $form .= $oForm->createSubmit("submitRecherche", t('FORM_BUTTON_SEARCH'));
        $form .= $oForm->createFreeHtml("</td></tr>");
        /**
         * Supprimé
         */
        /*
         * $form .= "<tr><td>&nbsp;</td></tr>"; $form .=
         * $oForm->showSeparator(); $form .=
         * $oForm->createCheckBoxFromList("filterType", "", array("1" =>
         * "Format"), "", false, $readO, "h", false,
         * "onclick='document.fFormMediaSearch.formatMedia.disabled=(!this.checked);'"
         * ); $form .= "<tr>"; $form .= "<tr><td class=\"formval\"><select
         * name=\"formatMedia\" style=\"width:180px\">"; foreach
         * ($aAllowedExtensions as $key=>$value) { $form .= "<optgroup
         * label=\"".$aAllowedExtensions[$key]["libelle"]."\">"; foreach($value
         * as $ext=>$lib) { if ($ext != "libelle") { $form .= "<option
         * value=\"".$ext."\">".$ext." (".$lib.")</option>"; } } $form .=
         * "</optgroup>"; } $form .= "<select></td></tr>";
         */

        $form .= endFormTable(false);
        $Form->_sDefaultFocus = "";
        //$oForm->createJs("if (curralert(current.mediaType); ent.mediaType)
        //docSearch.fFormMediaSearch.type.value = current.mediaType;");
        $form .= $oForm->close();

        // Zend_Form start
        if (($oForm instanceof Zend_Form)) {
            /**
             * ******** Pour faire correspondre *********
             */
            $form = "<br /><div class=\"title\">" . t('POPUP_SEARCH_TITLE') . "</div><br />";
            $form .= formToString($oForm, $form);
            /**
             * ******************************************
             */
        }
        // Zend_Form stop

        return $form;
    }

    protected function getMediaTree($site, $typeTree, $path, $image, $allowAdd, $allowDel, $options = array()) {

        $aOtherLevel = $resultat = $aFirstLevel = array();
        $oConnection = Pelican_Db::getInstance();
        if ($typeTree == 1) {

            $strSQL = "select
					" . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"] . " as \"id\",
					" . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PARENT"] . " as \"pid\",
					" . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_NAME"] . " as \"lib\",
					" . $oConnection->getConcatClause(array("LOWER(" . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_NAME"] . ")", "'_'", Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"])) . " as \"order\",
					" . $oConnection->getConcatClause(array("'javascript:parent.goMedia('", Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"], "', '", $oConnection->getNVLClause(Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PARENT"], 1), "', " . $allowAdd . ", " . $allowDel . ", '", "''''", Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"], "''''", "');'")) . " as \"url\",
					" . $oConnection->getCaseClause(Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PARENT"], array("NULL" => "'" . $image . "'"), "'" . Pelican::$config ["SKIN_PATH"] . "/images/folder.gif'") . "  as \"icon\",
					" . $oConnection->getCaseClause(Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PARENT"], array("NULL" => "'" . $image . "'"), "'" . Pelican::$config ["SKIN_PATH"] . "/images/folderOpen.gif'") . "  as \"iconOpen\",
					SITE_ID
					from
					" . Pelican::$config ["FW_MEDIA_FOLDER_TABLE_NAME"];
            if ($path) {
                $strSQL .= "where " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"] . " = " . $path;
                $strSQL .= " and (MEDIA_DIRECTORY_ID in(" . Pelican::$config ["MEDIA_DIRECTORY_ALLCOUNTRIES"]['ALL'] . "))";
            } else {
                $strSQL .= " where (MEDIA_DIRECTORY_ID in(" . Pelican::$config ["MEDIA_DIRECTORY_ALLCOUNTRIES"]['ALL'] . "))";
            }

            $aFirstLevel = $oConnection->queryTab($strSQL);
            if (sizeof($aFirstLevel) > 0) {
                foreach ($aFirstLevel as $key => $aLevel) {
                    //$aOtherLevel[] = self::getDirectorytree($aLevel["id"], $path, &$resultat, true, $allowAdd, $allowDel);
                    $level_resultat = Pelican_Cache::fetch('StaticMethod', array($_SESSION[APP]['user']['id'], 'Media_Navigation_Controller', 'getDirectorytree', array($aLevel["id"], $path, true, $allowAdd, $allowDel)));
                    if (!empty($level_resultat)) {
                        $resultat = array_merge($resultat, $level_resultat);
                    }
                }
            }

            $return = array_merge($resultat, $aFirstLevel);
        } else {

            $oTree = Pelican_Factory::getInstance('Hierarchy.Tree', "dtree" . $path, "id", "pid");

            $oTree->addTabNode($this->rubrique);
            $oTree->setOrder("order", "ASC");
            if ($type == "xmltree") {
                $oTree->rootParams = implode("\",\"", array("Media", "javascript:parent.resetPath(document);", "explorer", $image, $image));
            }
            $oTree->setTreeType($allowDel, $allowAdd, $options);
            $return = $oTree->getTree();
        }

        return $return;
    }

    static public function getDirectorytree($iDirectoryId, $path, $bFirstCall = false, $allowAdd, $allowDel) {

        $aResult = $resultat = array();

        $oConnection = Pelican_Db::getInstance();

        $sSiteCode = Citroen_Form::getSiteCode(self::SEPARATOR);


        $strSQL = "select
                        " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"] . " as \"id\",
                        " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PARENT"] . " as \"pid\",
                        " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_NAME"] . " as \"lib\",
                        " . $oConnection->getConcatClause(array("LOWER(" . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_NAME"] . ")", "'_'", Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"])) . " as \"order\",
                        " . $oConnection->getConcatClause(array("'javascript:parent.goMedia('", Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"], "', '", $oConnection->getNVLClause(Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PARENT"], 1), "', " . $allowAdd . ", " . $allowDel . ", '", "''''", Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"], "''''", "');'")) . " as \"url\",
                        " . $oConnection->getCaseClause(Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PARENT"], array("NULL" => "'" . $image . "'"), "'" . Pelican::$config ["SKIN_PATH"] . "/images/folder.gif'") . "  as \"icon\",
                        " . $oConnection->getCaseClause(Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PARENT"], array("NULL" => "'" . $image . "'"), "'" . Pelican::$config ["SKIN_PATH"] . "/images/folderOpen.gif'") . "  as \"iconOpen\",
                        SITE_ID
                        from
                        " . Pelican::$config ["FW_MEDIA_FOLDER_TABLE_NAME"];
        if ($path) {
            $strSQL .= "where " . Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"] . " = " . $path;
            $strSQL .= " and (MEDIA_DIRECTORY_PARENT_ID = " . $iDirectoryId . ")";
            if ($bFirstCall) {
                $strSQL .= " and (MEDIA_DIRECTORY_LABEL REGEXP '" . $sSiteCode . "')";
            }
        } else {
            $strSQL .= " where (MEDIA_DIRECTORY_PARENT_ID = " . $iDirectoryId . ")";

            // if($bFirstCall){
            // $strSQL .= " and (MEDIA_DIRECTORY_LABEL REGEXP '".$sSiteCode."')";
            // }
        }

        $sSql = $oConnection->getCountSQL($strSQL, Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"]);
        $count = $oConnection->queryRow($sSql);
//        list($sSql,$aResult,$aResultChildren) = Pelican_Cache::fetch('Backend/Navigation/DirectoryTree', array($iDirectoryId, $path, $bFirstCall, $allowAdd, $allowDel));
        if (current($count) > 0) {
            $aResult = $oConnection->queryTab($strSQL);
            $resultat = array_merge($resultat,$aResult);
            foreach ($aResult as $iKey => $aValeur) {
                $aResultChildren = self::getDirectorytree($aValeur['id'], $path, false, $allowAdd, $allowDel);
                if (!empty($aResultChildren)) {
                    $resultat = array_merge($resultat, $aResultChildren);
                }
            }
        }

        return $resultat;
    }

}
