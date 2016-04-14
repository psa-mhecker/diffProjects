<?php
/**
 * Formulaire de gestion des médias.
 *
 * @author Pierre Moiré <pierre.moire@businessdecision.com>
 *
 * @since 04/03/2011
 */
require_once pelican_path('Media');
pelican_import('Hierarchy');
pelican_import('Hierarchy.Tree');
/*
 * Librairie de création d'onglets
 */
pelican_import('Index.Tab');
pelican_import('Index');

class Media_Navigation_Controller extends Pelican_Controller_Back
{
    protected $allowAdd;

    protected $allowDel;

    protected $rootImage;

    protected $begin;

    protected $rubrique;

    protected $end;

    protected $displaySearch;

    protected $noBegin = true;

    public function init()
    {
        $this->allowAdd = (Pelican::$config ["FW_MEDIA_ALLOW_ADD"] ? "true" : "false");
        $this->allowDel = (Pelican::$config ["FW_MEDIA_ALLOW_DEL"] ? "true" : "false");
        $this->rootImage = Pelican::$config ["SKIN_PATH"]."/images/tree_media.gif";
    }

    /**
     * Servira à présenter la médiathèque en mode popup.
     */
    public function indexAction()
    {
        /** @var Pelican_Index $head */
        $head = $this->getView()->getHead();

        if (! isset($dir)) {
            $dir = "";
        }

        $_REQUEST ["root"] = Pelican::$config ["MEDIA_ROOT"]."/";
        $label = "&nbsp;(Racine)";
        $this->id = $_GET ["view"].$_SESSION [APP] ['SITE_ID'];
        $head->setJS(Pelican::$config ["MEDIA_LIB_PATH"]."/js/media_hmvc.js");
        $head->setScript("
        if (!current) {
			var current = new Object;
		}
		current.mediaType='".$_GET ["media"]."';
		current.rootPath='".$dir."';
		current.zone=\"media\";
		initTree=false;
		current.physicalPath=current.rootPath;");

        //pré-sélection du ratio attendu par le champ
        $head->endScript(<<<EOF
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
                if(windowArguments['dimension'] != undefined){
                    var \$dimension = $('#dimension');
                    \$dimension.val(windowArguments['dimension']);
                     var oParent;
                     if (parent.activeOngletMedia) {
                     oParent = parent;
                     } else {
                     oParent = top;
                     }
                     oParent.activeOngletMedia(document, '1');
                     \$dimension.prop('disabled',true);
                }
EOF
    );
        $this->noBegin = false;
        $this->buildTree($label);
        $this->fetch();
    }

    protected function buildTree()
    {
        $head = $this->getView()->getHead();

        /*
         * Cas général
         */

        if (! $this->id) {
            "popup".trim(strtoupper($_REQUEST ["type"]));
        }
        if ($this->noBegin) {
            $head->setTitle("Pelican");
            $head->setMeta("http-equiv", "content-type", "text/html".(Pelican::$config ["CHARSET"] ? "; charset=".Pelican::$config ["CHARSET"] : ""));
            $head->setBackofficeSkin(Pelican::$config ["SKIN"], "/library/Pelican/Index/Backoffice/public/skins", Pelican::$config ["DOCUMENT_INIT"], "screen,print");
            $head->setJs(Pelican::$config ["LIB_PATH"].Pelican::$config ['LIB_FORM']."/js/xt_mozilla_fonctions.js");

            $head->setScript("var libDir='".Pelican::$config ["LIB_PATH"]."';");
            $head->setScript("var mediaDir='.';");
            $head->setScript("var httpMediaDir='".Pelican::$config ["MEDIA_HTTP"]."';");


            $header = $head->getHeader();

            $this->begin = "
			<html>
			".$header."
			<body bgcolor=\"white\" leftmargin=\"3\" topmargin=\"3\">
			";
            $this->end = "</body>
			</html>";
        }

        $this->displaySearch = true;

        if(!isset($_SESSION [APP]['showAllSite'])) {
            $_SESSION [APP]['showAllSite'] = 0;
        }
        if(isset($_POST['showAllSite'])) {
            $_SESSION [APP]['showAllSite'] = $_POST['showAllSite'];
        }

        $this->rubrique = $this->getMediaTree(1, '', $this->rootImage, $this->allowAdd, $this->allowDel);

        $this->assign('begin', $this->begin, false);

        if (! isset($this->end)) {
            $this->end = "";
        }

        if (Pelican::$config ['BO_USE_EXTJS_TREE'] && ! $this->noBegin) {
            $tree = $this->showTree("extjs", "media", array('target' => 'divMedia0', 'defaultnode' => '' ));
            $this->getView()->default = $this->execDefault("extjs");
        } else {
            $tree = $this->showTree("dtree", "media");
            $this->getView()->default = $this->execDefault("dtree");
        }
        if($_SESSION [APP]['showAllSite'] == 0) {
            $tree .= '<form action=""  method="post" >';
            $tree .= '<input type="submit" value="'.t('NDP_SHOW_ALL_COUNTRY').'" class="button"  />';
            $tree .= '<input type="hidden" value="1"  name="showAllSite"   />';
            $tree .= '</form>';
        }
        if($_SESSION [APP]['showAllSite'] == 1) {
            $tree .= '<form action=""  method="post" >';
            $tree .= '<input type="submit" value="'.t('NDP_HIDE_ALL_COUNTRY').'" class="button"  />';
            $tree .= '<input type="hidden" value="0"  name="showAllSite"   />';
            $tree .= '</form>';
        }

        $this->assign('tree', $tree, false);
        $this->assign('end', $this->end);
    }

    /**
     * Tri du tableau $this->rubrique sur la propriété "order" et affichage de
     * l'arborescence avec dtree.
     *
     *
     * @param $type string "dtree" ou "xloadtree"
     * @param $complement string "paramètre supplémentaire pour "xloadtree"
     * @param array $options
     *
     * @return string
     */
    protected function showTree($type = "extjs", $complement = "", $options = array())
    {
        $object = $this->getMediaTree(2, $this->id, $this->rootImage, $complement, $type, $options, rand(0, 1000));

        $tree = $this->addSearch($object);

        return $tree;
    }

    /**
     * Création du javascript permettant le lancement de la fonction javascript
     * appelée par le noeud de l'arborescence dont l'id est en cookie.
     *
     *
     * @param string $type
     * @return string
     */
    protected function execDefault($type = "extjs")
    {
        if (Pelican::$config ['BO_USE_EXTJS_TREE'] && $type == "dtree") {
            $type = "extjs";
        }

        if ($type == "dtree") {
            $default = "<script type=\"text/javascript\">
			dtree".$this->id.".doDefault(parent.initTree);
			parent.initTree=false;
			</script>";
        } elseif ($type == "extjs") {
            $default = "<script type=\"text/javascript\">
		Ext.onReady(function (){
			Ext.getCmp('dtree".$this->id."').doDefault('');
		});
		</script>";
        }

        return $default;
    }

    /**
     * Ajout d'un onglet de recherche à du code Pelican_Html existant.
     *
     * @return string
     *
     * @param $tree string
     *                Code Pelican_Html de l'arborescence
     */
    protected function addSearch($tree)
    {
        if ($this->displaySearch) {
            if (! valueExists($_GET, "oMedia")) {
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

            $return = Pelican_Html::div(array("style" => "height:31px" ), $oTab->getTabs());

            $return .= "<div id=\"divMedia0\">".$tree."</div>";
            $return .= "<div id=\"divMedia1\" style=\"display:none;\" >";
            $return .= $this->getSearchMediaForm();
            $return .= "</div>";
        } else {
            $return = $tree;
        }

        return $return;
    }

    /**
     * Fonction de création des éléments du menu contextuel de l'arbre.
     */
    protected function rightClick()
    {
        $menu ["tree"] = array(array(t('EDITOR_CUT'), "interceptHref(obj.href,\"parent.goMedia(\",\"moveFolder('cut',\");" ), array(t('EDITOR_PASTE'), "interceptHref(obj.href,\"parent.goMedia(\",\"moveFolder('paste',\");", "!(parent.current.move['cut'])" ), array(), array(t('POPUP_LABEL_ADD'), "interceptHref(obj.href,\"parent.goMedia\",\"top.setFolder\");top.setAction('add','folder')" ), array("Editer", "interceptHref(obj.href,\"parent.goMedia\",\"top.setFolder\");top.setAction('edit','folder')" ), array(t('POPUP_LABEL_DEL'), "interceptHref(obj.href,\"parent.goMedia\",\"top.setFolder\");top.setAction('del','folder')" ) );
        $return = getContextMenu($menu);

        $head = $this->getView()->getHead();
        $head->setJs(Pelican::$config ["MEDIA_LIB_PATH"].'/js/rightClick.js');

        return $return;
    }

    // ///////////////////////////////////////

    // A déplacer à terme dans un modèle Pelican_Hierarchy_Tree nouvelle version
    // Ceci est une méthode allégée par rapport aux méthodes Tree+Hierarchy qui
    // se faisaient en plusieurs étapes
    protected function getSearchMediaForm()
    {

        $form = "<br /><div class=\"title\">".t('POPUP_SEARCH_TITLE')."</div><br />";
        $oForm = Pelican_Factory::getInstance('Form', false, "vertical");
        $form .= $oForm->open("/_/Index/child", "get", "fFormMediaSearch", false, true, "CheckForm", "iframeRight", false);
        $form .= beginFormTable("0", "0", "form", false);

        $form .= $oForm->createHidden("action", "search");
        $form .= $oForm->createHidden("type", "");
        $form .= $oForm->createHidden("tid", '17');
        $form .= $oForm->createHidden("zone", $this->getParam('zone'));
        $form .= $oForm->createHidden("root", "");
        $form .= $oForm->createHidden("path", "");
        $form .= $oForm->createHidden("lib", "");

        $formats = Pelican_Cache::fetch("Media/MediaFormat", array());

        $listFormat = array();
        foreach ($formats as $format) {
            $listFormat[$format['MEDIA_FORMAT_ID']] = t($format['MEDIA_FORMAT_LABEL']);
        }

        $form .= $oForm->createInput("recherche", t("Rechercher les medias nommés ou avec l'id :"), 50, "", false, "", false, 25);
        $form .= $oForm->createComboFromList("ratio", t("RECHERCHE_RATIO"), $listFormat, $this->values["ratio"], false, $this->readO);
        $listFormat = array();
        foreach ($formats as $format) {
            if ($format['MEDIA_FORMAT_HEIGHT'] > 0) {
                $listFormat[$format['MEDIA_FORMAT_ID']] = $format['MEDIA_FORMAT_WIDTH'].'x'.$format['MEDIA_FORMAT_HEIGHT'];
            }
        }

        $form .= $oForm->createComboFromList("dimension", t("NDP_RECHERCHE_DIMENSION"), $listFormat, $this->values["dimension"], false, false);

        // streamlike
        $form .= $oForm->createInput("by_streamlike_id", t("SEARCH_STREAMLIKE_ID"), 50, "", false, "", false, 25);
        $form .= $oForm->createInput("by_streamlike_keyword", t("SEARCH_STREAMLIKE_KEYWORD"), 50, "", false, "", false, 25);


        $form .= $oForm->createDateTime("date_from", t("DATE_FROM"), false, $this->values["date_from"], false, $this->readO);
        $form .= $oForm->createDateTime("date_to", t("DATE_TO"), false, $this->values["date_to"], false, $this->readO);
        $form .= $oForm->createFreeHtml("<tr><td>&nbsp;</td></tr>");
        $form .= $oForm->createLabel(t("Rechercher dans :"), "<div id=\"mediaFolder\">&nbsp;</div>");

        /*
         * bouton
         */
        $form .= $oForm->createFreeHtml("<tr><td>&nbsp;</td></tr>");
        $form .= $oForm->createFreeHtml("<tr>");
        $form .= $oForm->createFreeHtml("<td class=\"formval\" align=\"center\">");
        $form .= $oForm->createSubmit("submitRecherche", t('FORM_BUTTON_SEARCH'));
        $form .= $oForm->createFreeHtml("</td></tr>");

        $form .= endFormTable(false);
        $form .= $oForm->close();

        return $form;
    }

    protected function getMediaTree($typeTree, $path, $image, $allowAdd, $allowDel, $options = array())
    {
        $connection = Pelican_Db::getInstance();

        if ($typeTree == 1) {
            $sql = "select
					".Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"]." as \"id\",
					".Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PARENT"]." as \"pid\",
					".Pelican::$config ["FW_MEDIA_FIELD_FOLDER_NAME"]." as \"lib\",
					".$connection->getConcatClause(array("LOWER(".Pelican::$config ["FW_MEDIA_FIELD_FOLDER_NAME"].")", "'_'", Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"] ))." as \"order\",
					".$connection->getConcatClause(array("'javascript:parent.goMedia('", Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"], "', '", $connection->getNVLClause(Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PARENT"], 1), "', ".$allowAdd.", ".$allowDel.", '", "''''", Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PATH"], "''''", "');'" ))." as \"url\",
					".$connection->getCaseClause(Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PARENT"], array("NULL" => "'".$image."'" ), "'".Pelican::$config ["SKIN_PATH"]."/images/folder.gif'")."  as \"icon\",
					".$connection->getCaseClause(Pelican::$config ["FW_MEDIA_FIELD_FOLDER_PARENT"], array("NULL" => "'".$image."'" ), "'".Pelican::$config ["SKIN_PATH"]."/images/folderOpen.gif'")."  as \"iconOpen\"
					from
					".Pelican::$config ["FW_MEDIA_FOLDER_TABLE_NAME"];
            if($_SESSION[APP]['showAllSite'] == 0) {
                $sql .= " where SITE_ID = " . $_SESSION[APP]['SITE_ID'] . ' OR SITE_ID=' . Pelican::$config["SITE_MASTER"];
            }
            if ($path) {
                $sql .= "where ".Pelican::$config ["FW_MEDIA_FIELD_FOLDER_ID"]." = ".$path;
            }

            $return = $connection->queryTab($sql);

        } else {
            $tree = Pelican_Factory::getInstance('Hierarchy.Tree', "dtree".$path, "id", "pid");
            $tree->addTabNode($this->rubrique);
            $tree->setOrder("order", "ASC");
            $tree->setTreeType($allowDel, $allowAdd, $options);
            $return = $tree->getTree();
        }

        return $return;
    }
}
