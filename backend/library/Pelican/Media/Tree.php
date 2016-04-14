<?php
/**
 * Librairie d'affichage de la navigation dans la mediathèque.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com/license/phpfactory
 *
 * @link http://www.interakting.com
 * @since 02/02/2004
 */
include_once "config.php";

/** Gestion d'un menu contextuel */
//if(!Pelican::$config['BO_USE_EXTJS_TREE']){
//}


/** Librairie de gestion de la mediathèque */
require_once pelican_path('Media');
$allowAdd = (Pelican::$config["FW_MEDIA_ALLOW_ADD"] ? "true" : "false");
$allowDel = (Pelican::$config["FW_MEDIA_ALLOW_DEL"] ? "true" : "false");
$rootImage = Pelican::$config["SKIN_PATH"]."/images/tree_media.gif";

/**
 * __DESC__.
 *
 * @author __AUTHOR__
 */
class Pelican_Media_Tree
{
    /**
     * Tri du tableau $rubrique sur la propriété "order" et affichage de
     * l'arborescence avec dtree.
     *
     * @access public
     *
     * @param mixed    $rubrique   Tableau contenant les informations liées aux
     *                             répertoires parcourus : contient
     *                             id,pid,lib,order,url,icon,iconOpen,isFolder,pathInfo
     * @param int      $id         Identifiant du tableau
     * @param string   $type       (option) "dtree" ou "xloadtree"
     * @param string   $complement (option) "paramètre supplémentaire pour "xloadtree"
     * @param array $options    (option) __DESC__
     *
     * @return string
     */
    public function showTree(&$rubrique, $id, $type = "extjs", $complement = "", $options = array())
    {
        global $rootImage;
        echo  Pelican_Cache::fetch("Backend/MediaTree", array($_SESSION[APP]["SITE_MEDIA"], 2, $id, $rootImage, $complement, $type, $options, rand(0, 1000)));
        $default = self::execDefault($id, $type);

        return $default;
    }

    /**
     * Récupération des données hiérarchiques en BDD ou physiquement suivant la
     * configuration : création du tableau $rubrique.
     *
     * @access public
     *
     * @param string $dir       Identifiant racine (Base de données) ou Chemin physique du
     *                          répertoire racine (parcours physique)
     * @param mixed  $rubrique  Tableau contenant les informations liées aux
     *                          répertoires parcourus : contient
     *                          id,pid,lib,order,url,icon,iconOpen,isFolder,pathInfo
     * @param string $rootlabel (option) Libellé du répertoire racine à afficher :
     *                          " " par défaut
     * @param string $type      (option) Utilisation du xml comme format de données
     *                          ("xml") ou non ("")
     */
    public function initTree($dir = "", &$rubrique, $rootlabel = " ", $type = "")
    {
        Pelican_Media_Tree::initDbTree("", $rubrique, $rootlabel);
    }

    /**
     * Récupération des données hiérarchiques en base de données : création du
     * tableau $rubrique.
     *
     * @access public
     *
     * @param string $dir       Identifiant racine : "" si pas précisé
     * @param mixed  $rubrique  Tableau contenant les informations liées aux
     *                          répertoires parcourus : contient
     *                          id,pid,lib,order,url,icon,iconOpen,isFolder,pathInfo
     * @param string $rootlabel (option) Libellé du répertoire racine à afficher :
     *                          " " par défaut
     */
    public function initDbTree($dir = "", &$rubrique, $rootlabel = " ")
    {
        global $i, $allowAdd, $allowDel, $rootImage;
        $oConnection = Pelican_Db::getInstance();
        $rubrique = Pelican_Cache::fetch("Backend/MediaTree", array($_SESSION[APP]["SITE_MEDIA"], 1, $dir, $rootImage, $allowAdd, $allowDel));
    }

    /**
     * Lancement du parcours des sous-répertoires d'un répertoire donné : création
     * du tableau $rubrique.
     *
     * @access public
     *
     * @param string $dir       Chemin physique du répertoire racine (avec "/" à la fin)
     * @param mixed  $rubrique  Tableau contenant les informations liées aux
     *                          répertoires parcourus : contient
     *                          id,pid,lib,order,url,icon,iconOpen,isFolder,pathInfo
     * @param string $rootlabel (option) Libellé du répertoire racine à afficher :
     *                          " " par défaut
     */
    public function initPhysicalTree($dir, &$rubrique, $rootlabel = " ")
    {
        global $i;
        $rubrique[] = array("id" => 1, "pid" => "", "lib" => $rootlabel, "order" => 0, "url" => "javascript:top.showFolder(1, 0, '".str_replace("//", "/", $dir)."', true, ".(Pelican::$config["FW_MEDIA_ALLOW_ADD"] ? "true" : "false").", ".(Pelican::$config["FW_MEDIA_ALLOW_DEL"] ? "true" : "false").")", "icon" => Pelican::$config["SKIN_PATH"]."/images/tree_media.gif", "iconOpen" => Pelican::$config["SKIN_PATH"]."/images/tree_media.gif");
        $i = 2;
        self::getDir($rubrique, 1, $dir);
    }

    /**
     * Fonction recursive de parcours des répertoires physique.
     *
     * @access public
     *
     * @param mixed  $rubrique   Tableau contenant les informations liées aux
     *                           répertoires parcourus : contient
     *                           id,pid,lib,order,url,icon,iconOpen,isFolder,pathInfo
     * @param int    $idParent   Identifiant du noeud
     * @param string $parentPath Chemin physique du répertoire parcouru (avec "/" à
     *                           la fin)
     */
    public function getDir(&$rubrique, $idParent, $parentPath)
    {
        global $i;
        $dir = opendir($parentPath);
        while ($file = readdir($dir)) {
            $detail = array();
            if (is_dir($parentPath.$file) && $file != "." && $file != "..") {
                if (self::folderAllowed($file)) {
                    $temp = pathinfo($parentPath.$file);
                    $detail["id"] = $i++;
                    $detail["pid"] = $idParent;
                    $detail["lib"] = $file;
                    $detail["order"] = $file;
                    $detail["url"] = "javascript:top.showFolder(".$detail["id"].", ".$idParent.", '".str_replace("//", "/", $parentPath.$file)."/', true, ".(Pelican::$config["FW_MEDIA_ALLOW_ADD"] ? "true" : "false").", ".(Pelican::$config["FW_MEDIA_ALLOW_DEL"] ? "true" : "false").")";
                    $detail["icon"] = Pelican::$config["SKIN_PATH"]."/images/folder.gif";
                    $detail["iconOpen"] = Pelican::$config["SKIN_PATH"]."/images/folderOpen.gif";
                    $detail["isFolder"] = is_dir($parentPath.$file);
                    $detail["pathInfo"] = pathinfo($parentPath.$file);
                    $rubrique[] = $detail;
                    if (is_dir($parentPath.$file)) {
                        self::getDir($rubrique, $detail["id"], $parentPath.$file."/");
                    }
                }
            }
        }
        closedir($dir);
    }

    /**
     * Vérifie si le fichier est un dossier est qu'il n'est pas exclu dans
     * Pelican::$config["FW_MEDIA_PREVIEW_STOP_LIST"].
     *
     * @access public
     *
     * @param string $folder Nom de dossier
     *
     * @return bool
     */
    public function folderAllowed($folder)
    {
        if (is_array(Pelican::$config["FW_MEDIA_PREVIEW_STOP_LIST"])) {
            if (in_array($folder, Pelican::$config["FW_MEDIA_PREVIEW_STOP_LIST"])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Création du javascript permettant le lancement de la fonction javascript
     * appelée par le noeud de l'arborescence dont l'id est en cookie.
     *
     * @access public
     *
     * @param int      $id   Id du noeud de l'arbre enregistré en cookie
     * @param __TYPE__ $type (option) __DESC__
     *
     * @return string
     */
    public function execDefault($id, $type = "extjs")
    {
        global $allowAdd, $allowDel;
        if (Pelican::$config['BO_USE_EXTJS_TREE'] && $type == "dtree") {
            //$type = "extjs";
        }
        //echo "<script type=\"text/javascript\">alert(1);</script>";
        if ($type == "dtree") {
            $default = "<script type=\"text/javascript\">
			dtree".$id.".doDefault(parent.initTree);
			parent.initTree=false;
			</script>";
        } elseif ($type == "extjs") {
            $default = "<script type=\"text/javascript\">
		Ext.onReady(function (){
			Ext.getCmp('dtree".$id."').doDefault('');
		});
		</script>";
        }

        return $default;
    }
}
