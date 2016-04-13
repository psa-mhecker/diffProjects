<?php
/**
 * Librairie d'affichage de la navigation dans la mediathèque
 *
 * @package Pelican
 * @subpackage Media
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 * @link http://www.interakting.com
 * @since 02/02/2004
 */
include_once ("config.php");

/** Gestion d'un menu contextuel */
//if(!Pelican::$config['BO_USE_EXTJS_TREE']){
//}


/** Librairie de gestion de la mediathèque */
require_once (pelican_path('Media'));
$allowAdd = (Pelican::$config["FW_MEDIA_ALLOW_ADD"] ? "true" : "false");
$allowDel = (Pelican::$config["FW_MEDIA_ALLOW_DEL"] ? "true" : "false");
$rootImage = Pelican::$config["SKIN_PATH"] . "/images/tree_media.gif";

/**
 * __DESC__
 *
 * @package Pelican
 * @subpackage Media
 * @author __AUTHOR__
 */
class Pelican_Media_Tree {
    
    /**
     * Tri du tableau $rubrique sur la propriété "order" et affichage de
     * l'arborescence avec dtree
     *
     * @access public
     * @param mixed $rubrique Tableau contenant les informations liées aux
     * répertoires parcourus : contient
     * id,pid,lib,order,url,icon,iconOpen,isFolder,pathInfo
     * @param int $id Identifiant du tableau
     * @param string $type (option) "dtree" ou "xloadtree"
     * @param string $complement (option) "paramètre supplémentaire pour "xloadtree"
     *
     * @param __TYPE__ $options (option) __DESC__
     * @return string
     */
    function showTree(&$rubrique, $id, $type = "extjs", $complement = "", $options = array()) {
        global $rootImage;
        $object = Pelican_Cache::fetch("Backend/MediaTree", array($_SESSION[APP]["SITE_MEDIA"], 2, $id, $rootImage, $complement, $type, $options, rand(0, 1000)));
        echo (self::addSearch($object));
        $default.= self::execDefault($id, $type);
        return $default;
    }
    
    /**
     * Récupération des données hiérarchiques en BDD ou physiquement suivant la
     * configuration : création du tableau $rubrique
     *
     * @access public
     * @param string $dir Identifiant racine (Base de données) ou Chemin physique du
     * répertoire racine (parcours physique)
     * @param mixed $rubrique Tableau contenant les informations liées aux
     * répertoires parcourus : contient
     * id,pid,lib,order,url,icon,iconOpen,isFolder,pathInfo
     * @param string $rootlabel (option) Libellé du répertoire racine à afficher :
     * " " par défaut
     * @param string $type (option) Utilisation du xml comme format de données
     * ("xml") ou non ("")
     * @return void
     */
    function initTree($dir = "", &$rubrique, $rootlabel = " ", $type = "") {
        Pelican_Media_Tree::initDbTree("", $rubrique, $rootlabel);
    }
    
    /**
     * Récupération des données hiérarchiques en base de données : création du
     * tableau $rubrique
     *
     * @access public
     * @param string $dir Identifiant racine : "" si pas précisé
     * @param mixed $rubrique Tableau contenant les informations liées aux
     * répertoires parcourus : contient
     * id,pid,lib,order,url,icon,iconOpen,isFolder,pathInfo
     * @param string $rootlabel (option) Libellé du répertoire racine à afficher :
     * " " par défaut
     * @return void
     */
    function initDbTree($dir = "", &$rubrique, $rootlabel = " ") {
        global $i, $allowAdd, $allowDel, $rootImage;
        $oConnection = Pelican_Db::getInstance();
        $rubrique = Pelican_Cache::fetch("Backend/MediaTree", array($_SESSION[APP]["SITE_MEDIA"], 1, $dir, $rootImage, $allowAdd, $allowDel));
    }
    
    /**
     * Lancement du parcours des sous-répertoires d'un répertoire donné : création
     * du tableau $rubrique
     *
     * @access public
     * @param string $dir Chemin physique du répertoire racine (avec "/" à la fin)
     * @param mixed $rubrique Tableau contenant les informations liées aux
     * répertoires parcourus : contient
     * id,pid,lib,order,url,icon,iconOpen,isFolder,pathInfo
     * @param string $rootlabel (option) Libellé du répertoire racine à afficher :
     * " " par défaut
     * @return void
     */
    function initPhysicalTree($dir, &$rubrique, $rootlabel = " ") {
        global $i;
        $rubrique[] = array("id" => 1, "pid" => "", "lib" => $rootlabel, "order" => 0, "url" => "javascript:top.showFolder(1, 0, '" . str_replace("//", "/", $dir) . "', true, " . (Pelican::$config["FW_MEDIA_ALLOW_ADD"] ? "true" : "false") . ", " . (Pelican::$config["FW_MEDIA_ALLOW_DEL"] ? "true" : "false") . ")", "icon" => Pelican::$config["SKIN_PATH"] . "/images/tree_media.gif", "iconOpen" => Pelican::$config["SKIN_PATH"] . "/images/tree_media.gif");
        $i = 2;
        self::getDir($rubrique, 1, $dir);
    }
    
    /**
     * Fonction recursive de parcours des répertoires physique
     *
     * @access public
     * @param mixed $rubrique Tableau contenant les informations liées aux
     * répertoires parcourus : contient
     * id,pid,lib,order,url,icon,iconOpen,isFolder,pathInfo
     * @param int $idParent Identifiant du noeud
     * @param string $parentPath Chemin physique du répertoire parcouru (avec "/" à
     * la fin)
     * @return void
     */
    function getDir(&$rubrique, $idParent, $parentPath) {
        global $i;
        $dir = opendir($parentPath);
        while ($file = readdir($dir)) {
            $detail = array();
            if (is_dir($parentPath . $file) && $file != "." && $file != "..") {
                if (self::folderAllowed($file)) {
                    $temp = pathinfo($parentPath . $file);
                    $detail["id"] = $i++;
                    $detail["pid"] = $idParent;
                    $detail["lib"] = $file;
                    $detail["order"] = $file;
                    $detail["url"] = "javascript:top.showFolder(" . $detail["id"] . ", " . $idParent . ", '" . str_replace("//", "/", $parentPath . $file) . "/', true, " . (Pelican::$config["FW_MEDIA_ALLOW_ADD"] ? "true" : "false") . ", " . (Pelican::$config["FW_MEDIA_ALLOW_DEL"] ? "true" : "false") . ")";
                    $detail["icon"] = Pelican::$config["SKIN_PATH"] . "/images/folder.gif";
                    $detail["iconOpen"] = Pelican::$config["SKIN_PATH"] . "/images/folderOpen.gif";
                    $detail["isFolder"] = is_dir($parentPath . $file);
                    $detail["pathInfo"] = pathinfo($parentPath . $file);
                    $rubrique[] = $detail;
                    if (is_dir($parentPath . $file)) {
                        self::getDir($rubrique, $detail["id"], $parentPath . $file . "/");
                    }
                }
            }
        }
        closedir($dir);
    }
    
    /**
     * Vérifie si le fichier est un dossier est qu'il n'est pas exclu dans
     * Pelican::$config["FW_MEDIA_PREVIEW_STOP_LIST"]
     *
     * @access public
     * @param string $folder Nom de dossier
     * @return bool
     */
    function folderAllowed($folder) {
        if (is_array(Pelican::$config["FW_MEDIA_PREVIEW_STOP_LIST"])) {
            if (in_array($folder, Pelican::$config["FW_MEDIA_PREVIEW_STOP_LIST"])) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Création du javascript permettant le lancement de la fonction javascript
     * appelée par le noeud de l'arborescence dont l'id est en cookie
     *
     * @access public
     * @param int $id Id du noeud de l'arbre enregistré en cookie
     * @param __TYPE__ $type (option) __DESC__
     * @return string
     */
    function execDefault($id, $type = "extjs") {
        global $allowAdd, $allowDel;
        if (Pelican::$config['BO_USE_EXTJS_TREE'] && $type == "dtree") {
            //$type = "extjs";
            
        }
        //echo "<script type=\"text/javascript\">alert(1);</script>";
        if ($type == "dtree") {
            $default = "<script type=\"text/javascript\">
			dtree" . $id . ".doDefault(parent.initTree);
			parent.initTree=false;
			</script>";
        } else if ($type == "extjs") {
            $default = "<script type=\"text/javascript\">
		Ext.onReady(function (){   
			Ext.getCmp('dtree" . $id . "').doDefault('');
		});
		</script>";
        }
        return $default;
    }
    
    /**
     * Ajout d'un onglet de recherche à du code Pelican_Html existant
     *
     * @access public
     * @param string $tree Code Pelican_Html de l'arborescence
     * @return string
     */
    function addSearch($tree) {
        global $displaySearch;
        if ($displaySearch) {
            if (!valueExists($_GET, "oMedia")) {
                $_GET["oMedia"] = "0";
            }
            $return = "<div style=\"height:31px\">";
            $return.= buildTab("Dossiers", "ongletMedia0", ($_GET["oMedia"] == "0"), "", "var oParent;
				if (parent.activeOngletMedia) {
				oParent = parent;
				} else {
				oParent = top;
				}
				oParent.activeOngletMedia(document, '0');", "", "petit") . buildTab(t('POPUP_SEARCH_TITLE'), "ongletMedia1", ($_GET["oMedia"] == "1"), "", "var oParent;
				if (parent.activeOngletMedia) {
				oParent = parent;
				} else {
				oParent = top;
				}
				oParent.activeOngletMedia(document, '1');", "", "petit");
            $return.= "</div>";
            $return.= "<div id=\"divMedia0\">" . $tree . "</div>";
            $return.= "<div id=\"divMedia1\" style=\"display:none;\" >";
            require_once (Pelican::$config['LIB_ROOT'] . Pelican::$config['LIB_MEDIA'] . "/media_search.php");
            $return.= getSearchMediaForm();
            $return.= "</div>";
        } else {
            $return = $tree;
        }
        return $return;
    }
    
    /**
     * Fonction de création des éléments du menu contextuel de l'arbre
     *
     * @access public
     * @return void
     */
    function rightClick() {
        $menu["tree"] = array(array(t('EDITOR_CUT'), "interceptHref(obj.href,\"parent.goMedia(\",\"moveFolder('cut',\");"), array(t('EDITOR_PASTE'), "interceptHref(obj.href,\"parent.goMedia(\",\"moveFolder('paste',\");", "!(parent.current.move['cut'])"), array(), array(t('POPUP_LABEL_ADD'), "interceptHref(obj.href,\"parent.goMedia\",\"top.setFolder\");top.setAction('add','folder')"), array("Editer", "interceptHref(obj.href,\"parent.goMedia\",\"top.setFolder\");top.setAction('edit','folder')"), array(t('POPUP_LABEL_DEL'), "interceptHref(obj.href,\"parent.goMedia\",\"top.setFolder\");top.setAction('del','folder')"));
        echo (getContextMenu($menu));
        echo Pelican_Html::script(array(src => Pelican::$config["MEDIA_LIB_PATH"] . '/js/rightClick.js'));
    }
}
